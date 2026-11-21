@extends('giftroll.layout')

@section('title', 'Join '.$exchange->title)
@section('theme', $exchange->theme)

@section('content')
<section class="single-column join-column">
    <p class="eyebrow"><span class="eyebrow-dot"></span> You’re invited</p>
    <h1 class="page-title">{{ $exchange->title }}</h1>
    <div class="event-meta">
        @if ($exchange->event_date)<span>{{ \Illuminate\Support\Carbon::parse($exchange->event_date)->format('F j, Y') }}</span>@endif
        @if ($exchange->budget)<span>Gift budget: {{ $exchange->budget }}</span>@endif
        <span>{{ $participantCount }} {{ $participantCount === 1 ? 'person' : 'people' }} joined</span>
    </div>
    <p class="page-intro">Add your name to the exchange. We’ll give you a private link to your reveal—save it so you can come back after the draw.</p>

    <div class="form-card join-card">
        <form action="{{ route('exchange.join.store', $token) }}" method="POST" class="stack-form">
            @csrf
            <label for="name">Your name</label>
            <input id="name" name="name" type="text" maxlength="60" placeholder="How your family knows you" value="{{ old('name') }}" required autofocus>
            @error('name') <p class="field-error">{{ $message }}</p> @enderror
            <button class="button button-primary" type="submit">Count me in <span aria-hidden="true">↗</span></button>
            <p class="form-footnote">No account or email needed.</p>
        </form>
    </div>
</section>
@endsection
