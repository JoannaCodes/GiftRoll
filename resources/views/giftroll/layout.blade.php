<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>@yield('title', 'GiftRoll') · GiftRoll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="theme-@yield('theme', 'evergreen')">
    <div class="site-shell">
        <header class="site-header">
            <a class="brand" href="{{ route('home') }}" aria-label="GiftRoll home">
                <span class="brand-mark" aria-hidden="true">g</span>
                <span>giftroll</span>
            </a>
            <span class="header-note">A little magic for your gift exchange</span>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="site-footer">
            <span>Made for the people you love.</span>
            <span>Free to use · No accounts</span>
        </footer>
    </div>
</body>
</html>
