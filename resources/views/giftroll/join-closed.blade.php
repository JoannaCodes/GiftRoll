@extends('giftroll.layout')

@section('title', 'Sign-ups are closed')
@section('theme', $exchange->theme)

@section('content')
<section class="single-column">
    <p class="eyebrow">{{ $exchange->title }}</p>
    <h1 class="page-title">This exchange<br><em>is closed.</em></h1>
    <p class="page-intro">The host has closed sign-ups and drawn names. If you already joined, open the private reveal link you saved.</p>
</section>
@endsection
