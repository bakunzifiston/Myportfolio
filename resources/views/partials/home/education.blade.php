<section id="education" class="section" aria-labelledby="education-heading">
    <div class="container-page grid gap-12 lg:grid-cols-[1fr_1.6fr] lg:gap-16">
        <x-site.section-heading eyebrow="Education" title="Academic background" id="education-heading">
            Studies in Computer Science and Software Engineering, pursued in China and the Turkish Republic of Northern Cyprus, together with master's-level studies.
        </x-site.section-heading>

        <div>
            @if ($education->isNotEmpty())
                <ol class="relative space-y-4 border-l border-zinc-200 pl-6 sm:pl-8 dark:border-zinc-800">
                    @foreach ($education as $entry)
                        <li class="reveal relative">
                            <span class="absolute top-6 -left-[31px] grid size-3.5 place-items-center rounded-full border-2 border-white bg-accent-600 ring-1 ring-accent-200 sm:-left-[39px] dark:border-zinc-950 dark:bg-accent-400 dark:ring-accent-900" aria-hidden="true"></span>
                            <article class="card p-6">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($entry->isAwarded())
                                        <span class="badge bg-accent-50 text-accent-800 ring-1 ring-accent-200 dark:bg-accent-950/50 dark:text-accent-300 dark:ring-accent-900"><i data-lucide="badge-check" class="size-3.5"></i> {{ $entry->statusLabel() }}</span>
                                    @else
                                        <span class="badge bg-zinc-100 text-zinc-700 ring-1 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700">{{ $entry->statusLabel() }}</span>
                                    @endif
                                    @if ($entry->period())
                                        <span class="font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $entry->period() }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-3 text-lg font-semibold">{{ $entry->displayTitle() }}</h3>
                                @if ($entry->institution || $entry->locationLabel())
                                    <p class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                                        @if ($entry->institution)
                                            <span class="inline-flex items-center gap-1.5"><i data-lucide="graduation-cap" class="size-4"></i>{{ $entry->institution }}</span>
                                        @endif
                                        @if ($entry->locationLabel())
                                            <span class="inline-flex items-center gap-1.5"><i data-lucide="map-pin" class="size-4"></i>{{ $entry->locationLabel() }}</span>
                                        @endif
                                    </p>
                                @endif
                                @if ($entry->description)
                                    <p class="mt-3 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{{ $entry->description }}</p>
                                @endif
                            </article>
                        </li>
                    @endforeach
                </ol>
            @endif

            @if ($certifications->isNotEmpty())
                <h3 class="mt-12 flex items-center gap-2 text-sm font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400"><i data-lucide="award"></i> Certifications</h3>
                <ul class="mt-4 grid gap-3">
                    @foreach ($certifications as $cert)
                        <li class="reveal card p-5">
                            <p class="font-medium text-zinc-950 dark:text-white">{{ $cert->name }}</p>
                            @if ($cert->description)
                                <p class="mt-1.5 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $cert->description }}</p>
                            @endif
                            @if ($cert->credential_id)
                                <p class="mt-1 font-mono text-xs text-zinc-500 dark:text-zinc-500">Certificate no. {{ $cert->credential_id }}</p>
                            @endif
                            @if ($cert->credential_url)
                                <a href="{{ $cert->credential_url }}" class="link-arrow mt-2" target="_blank" rel="noopener noreferrer">View credential <i data-lucide="external-link" class="size-3.5"></i></a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>
