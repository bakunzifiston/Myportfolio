<div class="card overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="border-b border-zinc-200 bg-zinc-50/80 text-xs tracking-wide text-zinc-500 uppercase dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
            {{ $head }}
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
            {{ $slot }}
        </tbody>
    </table>
</div>
