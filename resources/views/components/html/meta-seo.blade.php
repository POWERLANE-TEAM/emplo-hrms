@props(['no_crawl' => false, 'description' => ''])

<meta name="twitter:card" content="{{ Vite::asset('resources/images/logo/pri-thumbnail.webp') }}">

<meta property="og:title" content="Title Here">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ Vite::asset('resources/images/logo/pri-thumbnail.webp') }}">
<meta property="og:image" content="{{ Vite::asset('resources/images/logo/pri-thumbnail.webp') }}">
<meta property="og:description" content="Description Here">
<meta name="description" content="Free Web tutorials">

@if ($no_crawl)
    <meta name="robots" content="noindex" />
    <meta name="referrer" content="never">
    <meta name="referrer" content="no-referrer">
@endif
