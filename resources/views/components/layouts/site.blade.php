@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'active' => 'home',
])
@php
    $siteTitle = $settings['site_title'] ?: $settings['full_name'];
    $pageTitle = $title ? $title.' · '.$settings['full_name'] : $siteTitle;
    $metaDescription = \Illuminate\Support\Str::limit(strip_tags($description ?: ($settings['meta_description'] ?: $settings['summary'])), 160);
    $ogImage = $image ?: \App\Support\Settings::fileUrl('og_image');
    $onHome = request()->routeIs('home');
    $sectionUrl = fn (string $id) => $onHome ? '#'.$id : route('home').'#'.$id;
    $navItems = [
        ['label' => 'Home', 'section' => 'top'],
        ['label' => 'About', 'section' => 'about'],
        ['label' => 'Projects', 'section' => 'projects', 'route' => 'projects.*'],
        ['label' => 'Skills', 'section' => 'skills'],
        ['label' => 'Experience', 'section' => 'experience'],
        ['label' => 'Education', 'section' => 'education'],
        ['label' => 'Resume', 'href' => route('resume.show'), 'route' => 'resume.*'],
        ['label' => 'Contact', 'section' => 'contact'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="author" content="{{ $settings['full_name'] }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#09090b">

    <meta property="og:type" content="{{ $type }}">
    <meta property="og:site_name" content="{{ $settings['full_name'] }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">

    {{-- Dark is the default; switch to light before first paint if the visitor chose it. --}}
    <script>
        try { if (localStorage.getItem('theme') === 'light') document.documentElement.classList.remove('dark'); } catch (e) {}
    </script>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="min-h-screen">
    <a href="#main" class="sr-only z-[60] rounded-md bg-zinc-950 px-4 py-2 text-white focus:not-sr-only focus:fixed focus:top-3 focus:left-3">Skip to content</a>

    <header data-header class="no-print sticky top-0 z-50 border-b border-transparent bg-white/80 backdrop-blur-lg transition-[border-color,box-shadow] data-[scrolled]:border-zinc-200/80 data-[scrolled]:shadow-[0_1px_12px_-6px_rgb(0_0_0/0.08)] dark:bg-zinc-950/80 dark:data-[scrolled]:border-zinc-800">
        <nav class="container-page flex h-16 items-center justify-between gap-4" aria-label="Main">
            <a href="{{ $sectionUrl('top') }}" class="group flex items-center gap-2.5 rounded-md" aria-label="{{ $settings['full_name'] }} — home">
                <span class="grid size-9 place-items-center rounded-lg bg-zinc-950 font-mono text-[13px] font-semibold text-white transition group-hover:bg-accent-700 dark:bg-white dark:text-zinc-950 dark:group-hover:bg-accent-400">BF</span>
                <span class="hidden text-sm leading-tight font-semibold text-zinc-950 sm:block dark:text-white">
                    {{ $settings['full_name'] }}
                    <span class="block font-normal text-zinc-500 dark:text-zinc-400">Software Developer</span>
                </span>
            </a>

            <ul class="hidden items-center gap-0.5 lg:flex">
                @foreach ($navItems as $item)
                    @php
                        $href = $item['href'] ?? $sectionUrl($item['section']);
                        $current = isset($item['route']) && request()->routeIs($item['route']);
                    @endphp
                    <li>
                        <a href="{{ $href }}" class="nav-link"
                           @isset($item['section']) @if ($onHome) data-nav-section="{{ $item['section'] }}" @endif @endisset
                           @if ($current) aria-current="page" @endif>{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>

            <div class="flex items-center gap-1.5">
                <button type="button" data-theme-toggle class="btn-ghost size-10 p-0" aria-label="Toggle dark mode" aria-pressed="false">
                    <i data-lucide="moon" class="dark:hidden"></i>
                    <i data-lucide="sun" class="hidden dark:inline-block"></i>
                </button>
                <a href="{{ route('resume.download') }}" class="btn-primary hidden px-4 sm:inline-flex">
                    <i data-lucide="download"></i> CV
                </a>
                <button type="button" data-menu-button class="btn-ghost size-10 p-0 lg:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
                    <i data-lucide="menu" data-menu-open></i>
                    <i data-lucide="x" data-menu-close class="hidden"></i>
                </button>
            </div>
        </nav>

        <div id="mobile-menu" data-menu hidden class="border-t border-zinc-200 bg-white lg:hidden dark:border-zinc-800 dark:bg-zinc-950">
            <ul class="container-page flex max-h-[calc(100vh-4rem)] flex-col gap-1 overflow-y-auto py-4">
                @foreach ($navItems as $item)
                    @php $current = isset($item['route']) && request()->routeIs($item['route']); @endphp
                    <li>
                        <a href="{{ $item['href'] ?? $sectionUrl($item['section']) }}"
                           class="flex items-center justify-between rounded-lg px-3 py-3 text-base font-medium text-zinc-800 hover:bg-zinc-100 aria-[current]:bg-accent-50 aria-[current]:text-accent-800 dark:text-zinc-200 dark:hover:bg-zinc-900 dark:aria-[current]:bg-accent-950/50 dark:aria-[current]:text-accent-300"
                           @isset($item['section']) @if ($onHome) data-nav-section="{{ $item['section'] }}" @endif @endisset
                           @if ($current) aria-current="page" @endif>
                            {{ $item['label'] }}
                            <i data-lucide="chevron-right" class="text-zinc-400"></i>
                        </a>
                    </li>
                @endforeach
                <li class="mt-3 border-t border-zinc-200 pt-4 dark:border-zinc-800">
                    <a href="{{ route('resume.download') }}" class="btn-primary w-full py-3"><i data-lucide="download"></i> Download CV (PDF)</a>
                </li>
            </ul>
        </div>
    </header>

    <main id="main" tabindex="-1" class="focus:outline-none">
        {{ $slot }}
    </main>

    <footer class="no-print border-t border-zinc-200 bg-zinc-50/60 dark:border-zinc-800 dark:bg-zinc-950">
        <div class="container-page flex flex-col gap-8 py-12 md:flex-row md:items-start md:justify-between">
            <div class="max-w-sm">
                <p class="font-semibold text-zinc-950 dark:text-white">{{ $settings['full_name'] }}</p>
                <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $settings['professional_title'] }}</p>
            </div>
            <nav aria-label="Footer" class="grid grid-cols-2 gap-x-10 gap-y-2 text-sm sm:grid-cols-3">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] ?? $sectionUrl($item['section']) }}" class="text-zinc-600 hover:text-zinc-950 dark:text-zinc-400 dark:hover:text-white">{{ $item['label'] }}</a>
                @endforeach
            </nav>
            @if ($socialLinks->isNotEmpty() || filled($settings['contact_email']))
                <ul class="flex items-center gap-2" aria-label="Profiles">
                    @if (filled($settings['contact_email']))
                        <li><a href="mailto:{{ $settings['contact_email'] }}" class="btn-ghost size-10 p-0" aria-label="Email"><i data-lucide="mail"></i></a></li>
                    @endif
                    @foreach ($socialLinks as $link)
                        <li><a href="{{ $link->url }}" class="btn-ghost size-10 p-0" target="_blank" rel="noopener noreferrer me" aria-label="{{ $link->label }}"><x-site.social-icon :link="$link" /></a></li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="border-t border-zinc-200 dark:border-zinc-800">
            <p class="container-page py-6 text-xs text-zinc-500 dark:text-zinc-500">© {{ now()->year }} {{ $settings['full_name'] }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
