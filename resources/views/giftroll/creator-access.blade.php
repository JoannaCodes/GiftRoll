@extends('giftroll.layout')

@section('title', 'Creator access')
@section('theme', $exchange->theme)

@section('content')
<section class="single-column access-column">
    <p class="eyebrow">A note for the host</p>
    <h1 class="page-title">Creator<br><em>access.</em></h1>
    <p class="page-intro">Enter the private code you saved when you created “{{ $exchange->title }}”.</p>
    <div class="form-card">
        <form action="{{ route('exchange.manage.verify', $token) }}" method="POST" class="stack-form">
            @csrf
            <label for="creator_code">Creator code</label>
            <input id="creator_code" name="creator_code" type="password" maxlength="40" autocomplete="current-password" required autofocus>
            @error('creator_code') <p class="field-error">{{ $message }}</p> @enderror
            <button class="button button-primary" type="submit">Open my exchange <span aria-hidden="true">↗</span></button>
        </form>
    </div>
</section>
@endsection
