<x-layouts.site title="Page not found">
    <section class="container-page flex min-h-[60vh] flex-col items-start justify-center py-24">
        <p class="font-mono text-sm text-accent-700 dark:text-accent-400">404</p>
        <h1 class="mt-3 text-4xl font-semibold tracking-tight">This page could not be found.</h1>
        <p class="mt-4 max-w-lg text-zinc-600 dark:text-zinc-400">The page may have moved, or the project may not be published yet.</p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('home') }}" class="btn-primary"><i data-lucide="arrow-left"></i> Back to home</a>
            <a href="{{ route('projects.index') }}" class="btn-secondary">Browse projects</a>
        </div>
    </section>
</x-layouts.site>
