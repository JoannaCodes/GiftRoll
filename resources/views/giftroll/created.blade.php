@extends('giftroll.layout')

@section('title', 'Your exchange is ready')
@section('theme', $exchange->theme)

@section('content')
<section class="single-column">
    <div class="success-icon" aria-hidden="true">✳</div>
    <p class="eyebrow">Your little gathering is taking shape</p>
    <h1 class="page-title">{{ $exchange->title }}<br><em>is ready.</em></h1>
    <p class="page-intro">Share the invite with your people. When everyone has joined, come back to close the exchange and draw names.</p>

    <div class="detail-card">
        <div class="detail-topline"><span>01</span><h2>Invite your people</h2></div>
        <p>Anyone with this link can join while the exchange is open.</p>
        <div class="copy-row"><input id="invite-link" value="{{ $inviteUrl }}" readonly aria-label="Invite link"><button class="button button-secondary" type="button" data-copy="invite-link">Copy link</button></div>
    </div>

    <div class="detail-card creator-card">
        <div class="detail-topline"><span>02</span><h2>Save your creator code</h2></div>
        <p>You’ll need this code to manage the exchange. GiftRoll can’t recover it later.</p>
        <div class="code-row"><code>{{ $creatorCode }}</code><button class="button button-secondary" type="button" data-copy-text="{{ $creatorCode }}">Copy code</button></div>
        <a class="text-link" href="{{ $manageUrl }}">Open creator page <span aria-hidden="true">↗</span></a>
    </div>

    <p class="privacy-note"><span aria-hidden="true">✦</span> Your code and participants’ reveal links are private. Keep them to yourself.</p>
</section>
@endsection
