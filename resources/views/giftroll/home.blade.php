@extends('giftroll.layout')

@section('title', 'Make a gift exchange')

@section('content')
<section class="hero-grid">
    <div class="hero-copy">
        <p class="eyebrow"><span class="eyebrow-dot"></span> The easy, lovely way to swap gifts</p>
        <h1>Give a little<br><em>joy.</em></h1>
        <p class="hero-intro">Bring your favorite people together for a gift exchange. We’ll do the name drawing, so you can enjoy the surprise.</p>
        <div class="hero-stamp" aria-hidden="true"><span>✳</span><small>good things<br>are coming</small></div>
    </div>

    <div class="form-card">
        <div class="card-heading">
            <div>
                <p class="eyebrow">First, the details</p>
                <h2>Start your exchange</h2>
            </div>
            <span class="step-chip">01 / 03</span>
        </div>

        <form action="{{ route('exchange.create') }}" method="POST" class="stack-form">
            @csrf
            <label for="title">What are we celebrating?</label>
            <input id="title" name="title" type="text" maxlength="80" placeholder="The Lopez family gift swap" value="{{ old('title') }}" required autofocus>
            @error('title') <p class="field-error">{{ $message }}</p> @enderror

            <div class="form-row">
                <div class="field-group">
                    <label for="event_date">Exchange date <span class="optional">optional</span></label>
                    <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}">
                    @error('event_date') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="field-group">
                    <label for="budget">Gift budget <span class="optional">optional</span></label>
                    <input id="budget" name="budget" type="text" maxlength="40" placeholder="₱500" value="{{ old('budget') }}">
                    @error('budget') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <fieldset class="theme-picker">
                <legend>Pick a little personality</legend>
                <div class="theme-options">
                    <label class="theme-option theme-choice-evergreen">
                        <input type="radio" name="theme" value="evergreen" @checked(old('theme', 'evergreen') === 'evergreen')>
                        <span class="theme-swatch"></span><span>Evergreen</span>
                    </label>
                    <label class="theme-option theme-choice-berry">
                        <input type="radio" name="theme" value="berry" @checked(old('theme') === 'berry')>
                        <span class="theme-swatch"></span><span>Berry</span>
                    </label>
                    <label class="theme-option theme-choice-starlight">
                        <input type="radio" name="theme" value="starlight" @checked(old('theme') === 'starlight')>
                        <span class="theme-swatch"></span><span>Starlight</span>
                    </label>
                </div>
                @error('theme') <p class="field-error">{{ $message }}</p> @enderror
            </fieldset>

            <button class="button button-primary" type="submit">Create my exchange <span aria-hidden="true">↗</span></button>
            <p class="form-footnote">No sign-up. Your private creator code is shown once—save it somewhere safe.</p>
        </form>
    </div>
</section>

<section class="how-strip" aria-label="How GiftRoll works">
    <div><span class="how-number">01</span><span>Make your exchange</span></div>
    <span class="how-divider" aria-hidden="true"></span>
    <div><span class="how-number">02</span><span>Share the invite</span></div>
    <span class="how-divider" aria-hidden="true"></span>
    <div><span class="how-number">03</span><span>Reveal the surprise</span></div>
</section>
@endsection
