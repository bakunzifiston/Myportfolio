@php
    $employment = $experiences['employment'];
    $projectEntries = $experiences['project'];
@endphp
<section id="experience" class="section bg-zinc-50/70 dark:bg-zinc-900/25" aria-labelledby="experience-heading">
    <div class="container-page">
        <x-site.section-heading eyebrow="Experience" title="Professional experience" id="experience-heading">
            Work experience and project-based contributions across digital transformation, information systems and online platforms.
        </x-site.section-heading>

        <div class="mt-12 grid gap-12 lg:grid-cols-2 lg:gap-10">
            @if ($employment->isNotEmpty())
                <div>
                    <h3 class="flex items-center gap-2 text-sm font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400"><i data-lucide="building-2"></i> Work experience</h3>
                    <ol class="mt-6 space-y-4">
                        @foreach ($employment as $item)
                            @include('partials.experience-item', ['item' => $item])
                        @endforeach
                    </ol>
                </div>
            @endif

            <div @class(['lg:col-span-2' => $employment->isEmpty()])>
                <h3 class="flex items-center gap-2 text-sm font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400"><i data-lucide="folder-git-2"></i> Project contributions</h3>

                @if ($projectEntries->isNotEmpty())
                    <ol class="mt-6 space-y-4">
                        @foreach ($projectEntries as $item)
                            @include('partials.experience-item', ['item' => $item])
                        @endforeach
                    </ol>
                @elseif ($projects->isNotEmpty())
                    {{-- Until detailed roles are published, list the projects contributed to without assuming a position. --}}
                    <ol @class(['mt-6 divide-y divide-zinc-200/80 border-y border-zinc-200/80 dark:divide-zinc-800 dark:border-zinc-800', 'md:grid md:grid-cols-2 md:gap-x-12 md:divide-y-0 md:border-b-0' => $employment->isEmpty()])>
                        @foreach ($projects as $project)
                            <li @class(['reveal', 'md:border-b md:border-zinc-200/80 md:dark:border-zinc-800' => $employment->isEmpty()])>
                                <a href="{{ route('projects.show', $project) }}" class="group flex items-center gap-5 py-5">
                                    <span class="font-mono text-xs text-zinc-400 dark:text-zinc-600">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block font-medium text-zinc-950 transition group-hover:text-accent-700 dark:text-white dark:group-hover:text-accent-400">{{ $project->title }}</span>
                                        <span class="mt-0.5 block text-sm text-zinc-500 dark:text-zinc-400">{{ $project->category?->name ?? 'Project contribution' }}</span>
                                    </span>
                                    <i data-lucide="arrow-up-right" class="size-4 text-zinc-400 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-accent-700 dark:group-hover:text-accent-400"></i>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
</section>
