@extends('giftroll.layout')

@section('title', 'Your gift match')
@section('theme', $exchange->theme)

@section('content')
<section class="single-column reveal-column">
    @if ($recipient)
        <div class="success-icon reveal-icon" aria-hidden="true">✳</div>
        <p class="eyebrow">{{ $exchange->title }} · Your match is</p>
        <h1 class="reveal-name">{{ $recipient->name }}</h1>
        <p class="page-intro">You’re the one making {{ $recipient->name }}’s day. Keep it a surprise!</p>
        @if ($exchange->budget)<div class="budget-note">Gift budget <strong>{{ $exchange->budget }}</strong></div>@endif
    @else
        <div class="success-icon" aria-hidden="true">◷</div>
        <p class="eyebrow">{{ $exchange->title }}</p>
        <h1 class="page-title">The surprise<br><em>is still wrapped.</em></h1>
        <p class="page-intro">You’re in, {{ $participant->name }}. Check this private link again after the host draws names.</p>
    @endif
</section>
@endsection
