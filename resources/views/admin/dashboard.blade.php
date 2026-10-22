<x-layouts.admin title="Dashboard">
    <x-admin.header title="Dashboard" description="Manage the content shown on the public portfolio and CV.">
        <a href="{{ route('admin.projects.create') }}" class="btn-primary"><i data-lucide="plus"></i> New project</a>
    </x-admin.header>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($stats as $stat)
            <a href="{{ route($stat['route']) }}" class="card p-5 transition hover:border-zinc-300 dark:hover:border-zinc-700">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $stat['label'] }}</p>
                <p class="mt-2 font-mono text-3xl font-semibold text-zinc-950 dark:text-white">{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="card p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold">Recent messages @if ($unreadMessages)<span class="ml-1 text-sm font-normal text-accent-700 dark:text-accent-400">({{ $unreadMessages }} unread)</span>@endif</h2>
                <a href="{{ route('admin.messages.index') }}" class="link-arrow">View all</a>
            </div>
            <ul class="mt-4 divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                @forelse ($recentMessages as $message)
                    <li class="py-3">
                        <a href="{{ route('admin.messages.show', $message) }}" class="block hover:text-zinc-950 dark:hover:text-white">
                            <span @class(['font-medium', 'text-zinc-950 dark:text-white' => ! $message->read_at])>{{ $message->subject }}</span>
                            <span class="block text-zinc-500">{{ $message->name }} · {{ $message->created_at->diffForHumans() }}</span>
                        </a>
                    </li>
                @empty
                    <li class="py-3 text-zinc-500">No messages yet.</li>
                @endforelse
            </ul>
        </section>

        <section class="card p-6">
            <h2 class="font-semibold">Projects awaiting details</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">These have no overview, contribution, features or challenges yet. Add confirmed details to complete their case studies.</p>
            <ul class="mt-4 divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                @forelse ($incompleteProjects as $project)
                    <li class="flex items-center justify-between py-2.5">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="font-medium hover:underline">{{ $project->title }}</a>
                        <x-admin.status :on="$project->isPublished()" offLabel="Draft" />
                    </li>
                @empty
                    <li class="py-3 text-zinc-500">All projects have case-study content.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-layouts.admin>
