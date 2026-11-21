<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExchangeController extends Controller
{
    public function home(): View
    {
        return view('giftroll.home');
    }

    public function create(Request $request): View
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'budget' => ['nullable', 'string', 'max:40'],
            'theme' => ['required', 'in:evergreen,berry,starlight'],
        ]);

        $inviteToken = Str::random(48);
        $creatorCode = Str::upper(Str::random(10));
        $exchangeId = (string) Str::uuid();

        DB::table('exchanges')->insert([
            'id' => $exchangeId,
            'title' => $data['title'],
            'event_date' => $data['event_date'] ?? null,
            'budget' => $data['budget'] ?? null,
            'theme' => $data['theme'],
            'status' => 'open',
            'invite_token_hash' => hash('sha256', $inviteToken),
            'creator_code_hash' => Hash::make($creatorCode),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exchange = DB::table('exchanges')->where('id', $exchangeId)->first();

        return view('giftroll.created', [
            'exchange' => $exchange,
            'inviteUrl' => route('exchange.join', $inviteToken),
            'manageUrl' => route('exchange.manage', $inviteToken),
            'creatorCode' => $creatorCode,
        ]);
    }

    public function joinPage(string $token): View
    {
        $exchange = $this->findExchange($token);

        return view('giftroll.join', [
            'exchange' => $exchange,
            'participantCount' => DB::table('exchange_participants')->where('exchange_id', $exchange->id)->count(),
        ]);
    }

    public function join(Request $request, string $token): View
    {
        $exchange = $this->findExchange($token);

        if ($exchange->status !== 'open') {
            return view('giftroll.join-closed', ['exchange' => $exchange]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
        ]);
        $name = trim($data['name']);

        $duplicate = DB::table('exchange_participants')
            ->where('exchange_id', $exchange->id)
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'name' => 'That name has already joined. Add a last initial so everyone can tell you apart.',
            ]);
        }

        $revealToken = Str::random(48);
        $participantId = (string) Str::uuid();

        DB::table('exchange_participants')->insert([
            'id' => $participantId,
            'exchange_id' => $exchange->id,
            'name' => $name,
            'reveal_token_hash' => hash('sha256', $revealToken),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('giftroll.joined', [
            'exchange' => $exchange,
            'name' => $name,
            'revealUrl' => route('exchange.reveal', [$token, $revealToken]),
        ]);
    }

    public function managePage(Request $request, string $token): View
    {
        $exchange = $this->findExchange($token);
        $isCreator = $request->session()->get('giftroll_creator_'.$exchange->id) === true;

        if (! $isCreator) {
            return view('giftroll.creator-access', compact('exchange', 'token'));
        }

        return $this->managerView($exchange, $token);
    }

    public function verifyCreator(Request $request, string $token): RedirectResponse
    {
        $exchange = $this->findExchange($token);
        $data = $request->validate([
            'creator_code' => ['required', 'string', 'max:40'],
        ]);

        if (! Hash::check($data['creator_code'], $exchange->creator_code_hash)) {
            return back()->withErrors(['creator_code' => 'That creator code doesn’t match.']);
        }

        $request->session()->put('giftroll_creator_'.$exchange->id, true);

        return redirect()->route('exchange.manage', $token);
    }

    public function draw(Request $request, string $token): View|RedirectResponse
    {
        $exchange = $this->findExchange($token);

        if ($request->session()->get('giftroll_creator_'.$exchange->id) !== true) {
            abort(403);
        }

        DB::transaction(function () use ($exchange): void {
            $lockedExchange = DB::table('exchanges')->where('id', $exchange->id)->lockForUpdate()->first();

            if ($lockedExchange->status !== 'open') {
                throw ValidationException::withMessages(['exchange' => 'This exchange has already been closed.']);
            }

            $participants = DB::table('exchange_participants')
                ->where('exchange_id', $exchange->id)
                ->orderBy('created_at')
                ->get();

            if ($participants->count() < 2) {
                throw ValidationException::withMessages(['exchange' => 'At least two people need to join before the draw.']);
            }

            $recipients = null;

            for ($attempt = 0; $attempt < 10000; $attempt++) {
                $candidate = $participants->shuffle()->values();
                $hasSelfMatch = $participants->values()->contains(
                    fn ($giver, $index) => $giver->id === $candidate[$index]->id,
                );

                if (! $hasSelfMatch) {
                    $recipients = $candidate;
                    break;
                }
            }

            if ($recipients === null) {
                throw ValidationException::withMessages(['exchange' => 'The draw could not be prepared. Please try again.']);
            }

            foreach ($participants->values() as $index => $giver) {
                DB::table('exchange_participants')->where('id', $giver->id)->update([
                    'recipient_id' => $recipients[$index]->id,
                    'updated_at' => now(),
                ]);
            }

            DB::table('exchanges')->where('id', $exchange->id)->update([
                'status' => 'drawn',
                'updated_at' => now(),
            ]);
        });

        return $this->managerView($this->findExchange($token), $token);
    }

    public function reveal(string $token, string $revealToken): View
    {
        $exchange = $this->findExchange($token);
        $participant = DB::table('exchange_participants')
            ->where('exchange_id', $exchange->id)
            ->where('reveal_token_hash', hash('sha256', $revealToken))
            ->firstOrFail();

        $recipient = $participant->recipient_id
            ? DB::table('exchange_participants')->where('id', $participant->recipient_id)->first()
            : null;

        return view('giftroll.reveal', compact('exchange', 'participant', 'recipient'));
    }

    private function findExchange(string $token): object
    {
        return DB::table('exchanges')
            ->where('invite_token_hash', hash('sha256', $token))
            ->firstOrFail();
    }

    private function managerView(object $exchange, string $token): View
    {
        $participants = DB::table('exchange_participants')
            ->where('exchange_id', $exchange->id)
            ->orderBy('created_at')
            ->get();

        return view('giftroll.manage', compact('exchange', 'participants', 'token'));
    }
}
