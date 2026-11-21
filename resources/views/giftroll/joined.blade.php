@extends('giftroll.layout')

@section('title', 'You’re in')
@section('theme', $exchange->theme)

@section('content')
<section class="single-column">
    <div class="success-icon" aria-hidden="true">♥</div>
    <p class="eyebrow">You’re on the list, {{ $name }}</p>
    <h1 class="page-title">Your secret has<br><em>a safe place.</em></h1>
    <p class="page-intro">Save this private link. When the host draws names, it will reveal who you’re gifting. Anyone with this link can see your match.</p>
    <div class="detail-card">
        <div class="detail-topline"><span>✦</span><h2>Your private reveal link</h2></div>
        <div class="copy-row"><input id="reveal-link" value="{{ $revealUrl }}" readonly aria-label="Your private reveal link"><button class="button button-secondary" type="button" data-copy="reveal-link">Copy link</button></div>
        <p class="form-footnote">Keep it somewhere you can find it later. GiftRoll can’t send reminders.</p>
    </div>
    <a class="text-link centered-link" href="{{ route('exchange.join', request()->route('token')) }}">Back to the exchange <span aria-hidden="true">↗</span></a>
</section>
@endsection
