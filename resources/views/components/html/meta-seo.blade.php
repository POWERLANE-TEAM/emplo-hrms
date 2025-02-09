@aware(['no_crawl' => false, 'description' => ''])

<meta name="twitter:card" content="{{ Vite::asset('resources/images/logo/pri-thumbnail.webp') }}">

<meta property="og:title" content="Emplo • HRMS">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ config('app.url') }}">
<meta property="og:image" content="{{ Vite::asset('resources/images/logo/pri-thumbnail.webp') }}">
<meta property="og:description" content="{{ $description }}">
<meta name="description" content="{{ $description }}">

@if ($no_crawl)
    <meta name="robots" content="noindex" />
    <meta name="referrer" content="never">
    <meta name="referrer" content="no-referrer">
@endif
