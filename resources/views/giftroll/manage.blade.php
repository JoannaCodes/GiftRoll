@extends('giftroll.layout')

@section('title', 'Manage '.$exchange->title)
@section('theme', $exchange->theme)

@section('content')
<section class="single-column manager-column">
    <p class="eyebrow">Your exchange · Creator page</p>
    <h1 class="page-title">{{ $exchange->title }}</h1>
    <div class="manager-status"><span class="status-dot status-{{ $exchange->status }}"></span>{{ $exchange->status === 'open' ? 'Taking names' : 'Names have been drawn' }}<span class="status-count">{{ $participants->count() }} joined</span></div>

    <div class="detail-card">
        <div class="detail-topline"><span>01</span><h2>Invite link</h2></div>
        <p>Share this link so everyone can add their name.</p>
        <div class="copy-row"><input id="manager-invite-link" value="{{ route('exchange.join', $token) }}" readonly aria-label="Invite link"><button class="button button-secondary" type="button" data-copy="manager-invite-link">Copy link</button></div>
    </div>

    <div class="detail-card">
        <div class="detail-topline"><span>02</span><h2>Your people <span class="count-pill">{{ $participants->count() }}</span></h2></div>
        @if ($participants->isEmpty())
            <p>No one has joined yet. Share the invite to get things rolling.</p>
        @else
            <ul class="participant-list">
                @foreach ($participants as $participant)
                    <li><span class="participant-avatar">{{ mb_strtoupper(mb_substr($participant->name, 0, 1)) }}</span><span>{{ $participant->name }}</span><span class="joined-label">Joined</span></li>
                @endforeach
            </ul>
        @endif
    </div>

    @if ($exchange->status === 'open')
        <div class="draw-panel">
            <div><p class="eyebrow">Ready when you are</p><h2>Close the exchange & draw names</h2><p>Once you draw, no one else can join. Each person gets one private match.</p></div>
            <form action="{{ route('exchange.draw', $token) }}" method="POST">
                @csrf
                <button class="button button-primary" type="submit" @disabled($participants->count() < 2)>Close & draw</button>
            </form>
        </div>
        @if ($participants->count() < 2)<p class="form-footnote centered">At least two people must join before you can draw.</p>@endif
    @else
        <div class="draw-panel draw-complete"><div><p class="eyebrow">The draw is complete</p><h2>Let the surprises begin.</h2><p>Everyone can open their private reveal link to see who they’re gifting. You won’t see anyone else’s match here.</p></div><span class="draw-sparkle" aria-hidden="true">✳</span></div>
    @endif
    @error('exchange') <p class="field-error centered">{{ $message }}</p> @enderror
</section>
@endsection
