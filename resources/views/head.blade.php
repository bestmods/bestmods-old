<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="opt-targeting" content="{&quot;type&quot;:&quot;Browse&quot;}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <title>{{ isset($headinfo['title']) ? $headinfo['title'] : 'Best Mods - Find The Best Mods For You!' }}</title>
    <meta name="description" content="{{ isset($headinfo['description']) ? $headinfo['description'] : 'Browse the best mods in gaming from many sources on the Internet! Project ran by The Modding Community!' }}" />
    <meta name="keywords" content="mods, modding, games, gaming, communities, best, servers, directory, discovery" />
    <meta name="robots" content="{{ isset($headinfo['robots']) ? $headinfo['robots'] : 'index, follow' }}" />

    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="{{ isset($headinfo['title']) ? $headinfo['title'] : 'Best Mods - Find The Best Mods For You!' }}">
    <meta property="twitter:description" content="{{ isset($headinfo['description']) ? $headinfo['description'] : 'Browse the best mods in gaming from many sources on the Internet! Project ran by The Modding Community!' }}">
    <meta property="twitter:site" content="@modcommunity_">
    <meta property="twitter:creator" content="@modcommunity_">
    <meta property="twitter:image" content="{{ isset($headinfo['image']) ? $headinfo['image'] : Illuminate\Support\Facades\URL::to('/images/bestmods-filled.png') }}">

    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="{{ isset($headinfo['title']) ? $headinfo['title'] : 'Best Mods - Find The Best Mods For You!' }}">
    <meta property="og:description" content="{{ isset($headinfo['description']) ? $headinfo['description'] : 'Browse for the best mods in gaming from many sources on the Internet! Project ran by The Modding Community!' }}">
    <meta property="og:site_name" content="Best Mods">
    <meta property="og:type" content="{{ isset($headinfo['type']) ? $headinfo['type'] : 'website' }}">
    @if (isset($headinfo['type']) && $headinfo['type'] == 'article')
    <meta property="article:published_time" content="{{ isset($headinfo['ptime']) ? $headinfo['ptime'] : '' }}">
    <meta property="article:modified_time" content="{{ isset($headinfo['mtime']) ? $headinfo['mtime'] : '' }}">
    <meta property="article:expiration_time " content="{{ isset($headinfo['etime']) ? $headinfo['etime'] : '' }}">
    <meta property="article:author " content="{{ isset($headinfo['author']) ? $headinfo['author'] : 'Best Mods' }}">
    <meta property="article:section " content="{{ isset($headinfo['section']) ? $headinfo['section'] : 'Technology' }}">
    <meta property="article:tag " content="{{ isset($headinfo['tags']) ? $headinfo['section'] : 'mod' }}">
    @endif
    <meta property="og:url" content="{{ isset($headinfo['url']) ? $headinfo['url'] : Illuminate\Support\Facades\URL::to('/') }}">
    <meta property="og:image" content="{{ isset($headinfo['image']) ? $headinfo['image'] : Illuminate\Support\Facades\URL::to('/images/bestmods-filled.png') }}">

    <link rel="canonical" href="{{ isset($headinfo['url']) ? $headinfo['url'] : Illuminate\Support\Facades\URL::to('/') }}">

    <meta name="msapplication-starturl" content="{{ isset($headinfo['url']) ? $headinfo['url'] : Illuminate\Support\Facades\URL::to('/') }}">
    <meta name="application-name" content="Best Mods">
    <meta name="apple-mobile-web-app-title" content="Best Mods">
    <meta name="theme-color" content="#181a1b">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="{{ isset($headinfo['icon']) ? $headinfo['icon'] : Illuminate\Support\Facades\URL::to('/images/bestmods-filled.png') }}">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- CSS and JavaScript -->
    @vite('resources/js/app.js')
</head>