<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · Admin</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script>try { if (localStorage.getItem('theme') === 'light') document.documentElement.classList.remove('dark'); } catch (e) {}</script>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-zinc-50 px-5 dark:bg-zinc-950">
    <main class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <span class="mx-auto grid size-11 place-items-center rounded-xl bg-zinc-950 font-mono text-sm font-semibold text-white dark:bg-white dark:text-zinc-950">BF</span>
            <h1 class="mt-5 text-xl font-semibold">Sign in to manage the portfolio</h1>
        </div>
        <form method="POST" action="{{ route('admin.login.store') }}" class="card space-y-5 p-6 sm:p-8">
            @csrf
            <x-admin.field name="email" label="Email" type="email" required />
            <x-admin.field name="password" label="Password" type="password" required />
            <x-admin.field name="remember" label="Keep me signed in" type="checkbox" />
            <button type="submit" class="btn-primary w-full py-3">Sign in</button>
        </form>
        <p class="mt-6 text-center text-sm"><a href="{{ route('home') }}" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">← Back to website</a></p>
    </main>
</body>
</html>
