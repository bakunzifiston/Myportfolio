<x-layouts.site :title="$project->title" :description="$project->short_description ?: $project->title.' — a project by '.$settings['full_name']" :image="$project->thumbnailUrl()" type="article" active="projects">
    <article>
        <header class="relative overflow-hidden border-b border-zinc-200/80 dark:border-zinc-800">
            <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_60%_80%_at_70%_0%,black,transparent)]" aria-hidden="true"></div>
            <div class="container-page relative py-14 sm:py-20">
                <nav aria-label="Breadcrumb" class="text-sm text-zinc-500 dark:text-zinc-400">
                    <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white">Home</a>
                    <span class="mx-2" aria-hidden="true">/</span>
                    <a href="{{ route('projects.index') }}" class="hover:text-zinc-900 dark:hover:text-white">Projects</a>
                    <span class="mx-2" aria-hidden="true">/</span>
                    <span aria-current="page" class="text-zinc-900 dark:text-white">{{ $project->title }}</span>
                </nav>

                <div class="mt-8 flex flex-wrap items-center gap-3 text-sm">
                    @if ($project->category)
                        <a href="{{ route('projects.index', ['category' => $project->category->slug]) }}" class="badge bg-accent-50 text-accent-800 ring-1 ring-accent-200 hover:bg-accent-100 dark:bg-accent-950/50 dark:text-accent-300 dark:ring-accent-900">{{ $project->category->name }}</a>
                    @endif
                    @if ($project->is_featured)
                        <span class="inline-flex items-center gap-1 text-zinc-500 dark:text-zinc-400"><i data-lucide="star" class="size-3.5"></i> Featured project</span>
                    @endif
                </div>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">{{ $project->title }}</h1>
                @if ($project->short_description)
                    <p class="mt-5 max-w-3xl text-lg leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $project->short_description }}</p>
                @endif

                @if ($project->live_url || $project->repository_url)
                    <div class="mt-8 flex flex-wrap gap-3">
                        @if ($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="btn-accent"><i data-lucide="external-link"></i> Visit live site</a>
                        @endif
                        @if ($project->repository_url)
                            <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="btn-secondary"><x-site.social-icon :link="(object) ['icon' => 'github', 'platform' => 'github']" /> View repository</a>
                        @endif
                    </div>
                @endif
            </div>
        </header>

        <div class="container-page py-12 sm:py-16">
            <div class="card aspect-[16/7] overflow-hidden">
                <x-site.project-cover :project="$project" size="hero" />
            </div>

            <div class="mt-12 grid gap-12 lg:grid-cols-[1fr_300px] lg:gap-16">
                <div class="min-w-0 space-y-12">
                    @unless ($project->hasCaseStudy())
                        <div class="flex gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-5 text-sm text-zinc-700 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                            <i data-lucide="file-text" class="mt-0.5 text-zinc-500"></i>
                            <p>A detailed case study for this project is being prepared. In the meantime, feel free to <a href="{{ route('home') }}#contact" class="font-medium text-accent-700 underline underline-offset-4 dark:text-accent-400">get in touch</a> to learn more.</p>
                        </div>
                    @endunless

                    @foreach ([
                        'overview' => 'Overview',
                        'contribution' => 'My role and contribution',
                    ] as $field => $heading)
                        @if (filled($project->$field))
                            <section>
                                <h2 class="text-xl font-semibold tracking-tight">{{ $heading }}</h2>
                                <div class="prose-body mt-4 leading-relaxed text-zinc-700 dark:text-zinc-300">
                                    @foreach (preg_split('/\R\s*\R/', $project->$field) as $paragraph)
                                        <p>{{ $paragraph }}</p>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    @endforeach

                    @if (filled($project->key_features))
                        <section>
                            <h2 class="text-xl font-semibold tracking-tight">Key features</h2>
                            <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                                @foreach ($project->key_features as $feature)
                                    <li class="flex gap-3 rounded-xl border border-zinc-200 p-4 text-sm text-zinc-800 dark:border-zinc-800 dark:text-zinc-200">
                                        <i data-lucide="check" class="mt-0.5 text-accent-700 dark:text-accent-400"></i>{{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if (filled($project->challenges) || filled($project->solutions))
                        <section>
                            <h2 class="text-xl font-semibold tracking-tight">Challenges and solutions</h2>
                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                @foreach (['challenges' => 'Challenges', 'solutions' => 'Solutions'] as $field => $label)
                                    @if (filled($project->$field))
                                        <div class="card p-6">
                                            <h3 class="text-sm font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400">{{ $label }}</h3>
                                            <div class="prose-body mt-3 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                                                @foreach (preg_split('/\R\s*\R/', $project->$field) as $paragraph)
                                                    <p>{{ $paragraph }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($project->screenshotUrls())
                        <section>
                            <h2 class="text-xl font-semibold tracking-tight">Screenshots</h2>
                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                @foreach ($project->screenshotUrls() as $i => $url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener" class="card block overflow-hidden">
                                        <img src="{{ $url }}" alt="{{ $project->title }} screenshot {{ $i + 1 }}" loading="lazy" decoding="async" class="aspect-video w-full object-cover object-top">
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start" aria-label="Project details">
                    <dl class="card divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
                        @if ($project->role)
                            <div class="p-5"><dt class="text-xs text-zinc-500 dark:text-zinc-400">Role</dt><dd class="mt-1 font-medium text-zinc-950 dark:text-white">{{ $project->role }}</dd></div>
                        @endif
                        <div class="p-5"><dt class="text-xs text-zinc-500 dark:text-zinc-400">Category</dt><dd class="mt-1 font-medium text-zinc-950 dark:text-white">{{ $project->category?->name ?? 'Project' }}</dd></div>
                        @if (filled($project->technologies))
                            <div class="p-5">
                                <dt class="text-xs text-zinc-500 dark:text-zinc-400">Technologies</dt>
                                <dd class="mt-2.5"><ul class="flex flex-wrap gap-1.5">@foreach ($project->technologies as $tech)<li class="tag">{{ $tech }}</li>@endforeach</ul></dd>
                            </div>
                        @endif
                        @if ($project->live_url)
                            <div class="p-5"><dt class="text-xs text-zinc-500 dark:text-zinc-400">Website</dt><dd class="mt-1"><a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="link-arrow break-all">{{ \Illuminate\Support\Str::of($project->live_url)->after('://')->rtrim('/') }} <i data-lucide="arrow-up-right" class="size-3.5"></i></a></dd></div>
                        @endif
                    </dl>
                    <a href="{{ route('projects.index') }}" class="link-arrow"><i data-lucide="arrow-left" class="size-4"></i> All projects</a>
                </aside>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="border-t border-zinc-200/80 bg-zinc-50/70 py-16 dark:border-zinc-800 dark:bg-zinc-900/30" aria-labelledby="related-heading">
            <div class="container-page">
                <h2 id="related-heading" class="text-2xl font-semibold tracking-tight">More projects</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-site.project-card :project="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
