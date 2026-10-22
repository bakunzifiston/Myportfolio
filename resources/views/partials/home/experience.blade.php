@php
    $employment = $experiences['employment'];
    $projectEntries = $experiences['project'];
@endphp
<section id="experience" class="section border-t border-zinc-200/80 bg-zinc-50/70 dark:border-zinc-800 dark:bg-zinc-900/30" aria-labelledby="experience-heading">
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
                    <ul @class(['mt-6 grid gap-3', 'sm:grid-cols-2 lg:grid-cols-3' => $employment->isEmpty()])>
                        @foreach ($projects as $project)
                            <li class="reveal">
                                <a href="{{ route('projects.show', $project) }}" class="card group flex items-center justify-between gap-4 px-5 py-4 transition hover:border-zinc-300 dark:hover:border-zinc-700">
                                    <span>
                                        <span class="block font-medium text-zinc-950 dark:text-white">{{ $project->title }}</span>
                                        <span class="mt-0.5 block text-xs text-zinc-500 dark:text-zinc-400">{{ $project->category?->name ?? 'Project contribution' }}</span>
                                    </span>
                                    <i data-lucide="arrow-up-right" class="text-zinc-400 transition group-hover:text-accent-700 dark:group-hover:text-accent-400"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>
