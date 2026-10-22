@props(['title' => 'Admin'])
@php
    $nav = [
        ['Dashboard', 'admin.dashboard', 'layout-dashboard', 'admin.dashboard'],
        ['Projects', 'admin.projects.index', 'folder-git-2', 'admin.projects.*'],
        ['Project categories', 'admin.project-categories.index', 'tags', 'admin.project-categories.*'],
        ['Skills', 'admin.skill-categories.index', 'code-xml', 'admin.skill-categories.*'],
        ['Experience', 'admin.experiences.index', 'briefcase', 'admin.experiences.*'],
        ['Education', 'admin.education.index', 'graduation-cap', 'admin.education.*'],
        ['Certifications', 'admin.certifications.index', 'award', 'admin.certifications.*'],
        ['Social links', 'admin.social-links.index', 'share-2', 'admin.social-links.*'],
        ['Messages', 'admin.messages.index', 'inbox', 'admin.messages.*'],
        ['Settings', 'admin.settings.edit', 'settings', 'admin.settings.*'],
    ];
    $unread = \App\Models\ContactMessage::whereNull('read_at')->count();
@endphp
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} · Admin</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script>try { if (localStorage.getItem('theme') === 'light') document.documentElement.classList.remove('dark'); } catch (e) {}</script>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-950">
    <div class="lg:grid lg:min-h-screen lg:grid-cols-[250px_1fr]">
        <aside class="border-b border-zinc-200 bg-white lg:border-r lg:border-b-0 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex h-16 items-center justify-between px-5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 font-semibold text-zinc-950 dark:text-white">
                    <span class="grid size-8 place-items-center rounded-lg bg-zinc-950 font-mono text-xs text-white dark:bg-white dark:text-zinc-950">BF</span>
                    Portfolio admin
                </a>
                <button type="button" data-menu-button class="btn-ghost size-9 p-0 lg:hidden" aria-expanded="false" aria-controls="admin-nav" aria-label="Open menu">
                    <i data-lucide="menu" data-menu-open></i><i data-lucide="x" data-menu-close class="hidden"></i>
                </button>
            </div>
            <nav id="admin-nav" data-menu="attribute" class="px-3 pb-4 max-lg:hidden max-lg:data-[open]:block" aria-label="Admin">
                <ul class="space-y-0.5">
                    @foreach ($nav as [$label, $route, $icon, $pattern])
                        <li>
                            <a href="{{ route($route) }}" @if (request()->routeIs($pattern)) aria-current="page" @endif
                               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950 aria-[current]:bg-zinc-100 aria-[current]:text-zinc-950 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white dark:aria-[current]:bg-zinc-800 dark:aria-[current]:text-white">
                                <i data-lucide="{{ $icon }}"></i> {{ $label }}
                                @if ($route === 'admin.messages.index' && $unread)
                                    <span class="ml-auto rounded-full bg-accent-600 px-2 text-xs text-white">{{ $unread }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6 space-y-0.5 border-t border-zinc-200 pt-4 dark:border-zinc-800">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800"><i data-lucide="eye"></i> View site</a>
                    <button type="button" data-theme-toggle class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800" aria-pressed="false"><i data-lucide="moon"></i> Toggle theme</button>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800"><i data-lucide="log-out"></i> Sign out</button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="min-w-0 px-5 py-8 sm:px-8 lg:px-10">
            <div class="mx-auto max-w-5xl">
                @if (session('status'))
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                        <i data-lucide="circle-check"></i> {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200" role="alert">
                        <p class="flex items-center gap-2 font-medium"><i data-lucide="circle-alert"></i> Please correct the errors below.</p>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
