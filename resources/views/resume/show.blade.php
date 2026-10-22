@php
    $contactItems = collect([
        filled($settings['contact_email']) ? ['icon' => 'mail', 'text' => $settings['contact_email'], 'href' => 'mailto:'.$settings['contact_email']] : null,
        filled($settings['phone']) ? ['icon' => 'phone', 'text' => $settings['phone'], 'href' => 'tel:'.preg_replace('/[^0-9+]/', '', $settings['phone'])] : null,
        filled($settings['location']) ? ['icon' => 'map-pin', 'text' => $settings['location'], 'href' => null] : null,
    ])->filter();
    $sectionTitle = 'mb-4 border-b border-zinc-200 pb-2 text-xs font-semibold tracking-[0.14em] text-accent-800 uppercase dark:border-zinc-800 dark:text-accent-400 print:text-black';
@endphp
<x-layouts.site title="Resume" :description="'CV of '.$settings['full_name'].': '.$settings['professional_title']" active="resume">
    <div class="bg-zinc-50 py-10 sm:py-14 dark:bg-zinc-900/40 print:bg-white print:py-0">
        <div class="no-print container-page mb-6 flex max-w-4xl flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Curriculum Vitae</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">The same content is available as a printable, text-selectable PDF.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="btn-secondary"><i data-lucide="printer"></i> Print</button>
                <a href="{{ route('resume.download') }}" class="btn-accent"><i data-lucide="download"></i> Download CV as PDF</a>
            </div>
        </div>

        <article class="print-sheet mx-auto max-w-4xl border-y border-zinc-200 bg-white px-6 py-10 shadow-sm sm:rounded-2xl sm:border sm:px-12 sm:py-12 dark:border-zinc-800 dark:bg-zinc-950 print:text-black" aria-label="Curriculum vitae">
            <header class="border-b border-zinc-200 pb-8 dark:border-zinc-800">
                <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl print:text-black">{{ $settings['full_name'] }}</h2>
                <p class="mt-2 text-base font-medium text-accent-800 dark:text-accent-400 print:text-black">{{ $settings['professional_title'] }}</p>
                @if ($contactItems->isNotEmpty() || $socialLinks->isNotEmpty())
                    <ul class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm text-zinc-700 dark:text-zinc-300">
                        @foreach ($contactItems as $item)
                            <li class="inline-flex items-center gap-2">
                                <i data-lucide="{{ $item['icon'] }}" class="size-4 text-zinc-400 print:hidden"></i>
                                @if ($item['href'])<a href="{{ $item['href'] }}" class="hover:underline">{{ $item['text'] }}</a>@else{{ $item['text'] }}@endif
                            </li>
                        @endforeach
                        @foreach ($socialLinks as $link)
                            <li class="inline-flex items-center gap-2">
                                <x-site.social-icon :link="$link" class="size-4 text-zinc-400 print:hidden" />
                                <a href="{{ $link->url }}" class="hover:underline" rel="noopener noreferrer">{{ \Illuminate\Support\Str::of($link->url)->after('://')->rtrim('/') }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </header>

            <section class="mt-8 print-avoid-break" aria-labelledby="cv-summary">
                <h3 id="cv-summary" class="{{ $sectionTitle }}">Professional Summary</h3>
                <p class="leading-relaxed text-zinc-800 dark:text-zinc-200 print:text-black">{{ $settings['summary'] }}</p>
            </section>

            @if ($skillCategories->isNotEmpty())
                <section class="mt-8 print-avoid-break" aria-labelledby="cv-skills">
                    <h3 id="cv-skills" class="{{ $sectionTitle }}">Technical Skills</h3>
                    <dl class="space-y-2 text-sm">
                        @foreach ($skillCategories as $category)
                            <div class="grid gap-1 sm:grid-cols-[220px_1fr] sm:gap-4">
                                <dt class="font-semibold text-zinc-900 dark:text-zinc-100 print:text-black">{{ $category->name }}</dt>
                                <dd class="text-zinc-700 dark:text-zinc-300 print:text-black">{{ $category->visibleSkills->pluck('name')->implode(', ') }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>
            @endif

            @if ($experiences['employment']->isNotEmpty())
                <section class="mt-8" aria-labelledby="cv-experience">
                    <h3 id="cv-experience" class="{{ $sectionTitle }}">Professional Experience</h3>
                    <div class="space-y-6">
                        @foreach ($experiences['employment'] as $item)
                            @include('resume.partials.entry', ['item' => $item])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($experiences['project']->isNotEmpty())
                <section class="mt-8" aria-labelledby="cv-contributions">
                    <h3 id="cv-contributions" class="{{ $sectionTitle }}">Project Contributions</h3>
                    <div class="space-y-6">
                        @foreach ($experiences['project'] as $item)
                            @include('resume.partials.entry', ['item' => $item])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($projects->isNotEmpty() || $otherProjects->isNotEmpty())
                <section class="mt-8" aria-labelledby="cv-projects">
                    <h3 id="cv-projects" class="{{ $sectionTitle }}">Selected Projects</h3>
                    <ul class="space-y-3 text-sm">
                        @foreach ($projects as $project)
                            <li class="print-avoid-break">
                                <span class="font-semibold text-zinc-900 dark:text-zinc-100 print:text-black">{{ $project->title }}</span>@if ($project->category)<span class="text-zinc-500"> · {{ $project->category->name }}</span>@endif
                                @if ($project->role)<span class="text-zinc-500"> · {{ $project->role }}</span>@endif
                                @if ($project->short_description)
                                    <p class="mt-0.5 text-zinc-700 dark:text-zinc-300 print:text-black">{{ $project->short_description }}</p>
                                @endif
                                @if (filled($project->technologies))
                                    <p class="mt-0.5 text-zinc-500">Technologies: {{ implode(', ', $project->technologies) }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if ($otherProjects->isNotEmpty())
                        <p class="mt-3 text-sm text-zinc-700 dark:text-zinc-300 print:text-black"><span class="font-semibold text-zinc-900 dark:text-zinc-100 print:text-black">Other projects:</span> {{ $otherProjects->implode(', ') }}</p>
                    @endif
                </section>
            @endif

            @if ($education->isNotEmpty())
                <section class="mt-8 print-avoid-break" aria-labelledby="cv-education">
                    <h3 id="cv-education" class="{{ $sectionTitle }}">Education</h3>
                    <ul class="space-y-4 text-sm">
                        @foreach ($education as $entry)
                            <li class="flex flex-col gap-0.5 sm:flex-row sm:justify-between sm:gap-6">
                                <div>
                                    <p class="font-semibold text-zinc-900 dark:text-zinc-100 print:text-black">{{ $entry->displayTitle() }}</p>
                                    <p class="text-zinc-700 dark:text-zinc-300 print:text-black">{{ collect([$entry->institution, $entry->locationLabel()])->filter()->implode(', ') }}@unless ($entry->isAwarded()){{ $entry->institution || $entry->locationLabel() ? ' · ' : '' }}{{ $entry->statusLabel() }}@endunless</p>
                                </div>
                                @if ($entry->period())
                                    <p class="shrink-0 text-zinc-500">{{ $entry->period() }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($certifications->isNotEmpty())
                <section class="mt-8 print-avoid-break" aria-labelledby="cv-certifications">
                    <h3 id="cv-certifications" class="{{ $sectionTitle }}">Certifications</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($certifications as $cert)
                            <li><span class="font-semibold text-zinc-900 dark:text-zinc-100 print:text-black">{{ $cert->name }}</span>{{ $cert->issuer ? ' — '.$cert->issuer : '' }}{{ $cert->issued_on ? ', '.$cert->issued_on->format('M Y') : '' }}@if ($cert->credential_id)<span class="text-zinc-500"> · Certificate no. {{ $cert->credential_id }}</span>@endif</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </article>
    </div>
</x-layouts.site>
