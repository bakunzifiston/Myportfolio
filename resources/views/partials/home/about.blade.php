@php
    $focusAreas = \App\Support\Settings::lines('focus_areas');
    $paragraphs = \App\Support\Settings::paragraphs('about');
    $portrait = \App\Support\Settings::fileUrl('portrait');
@endphp
<section id="about" class="section" aria-labelledby="about-heading">
    <div class="container-page grid gap-12 lg:grid-cols-[1.4fr_1fr] lg:gap-16">
        <div>
            <x-site.section-heading eyebrow="About" title="Practical software, built on solid foundations." id="about-heading" />
            <div class="prose-body reveal mt-8 text-base leading-relaxed text-zinc-700 sm:text-[17px] dark:text-zinc-300">
                @foreach ($paragraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>

        <aside class="reveal lg:pt-14" aria-label="At a glance">
            <div class="card p-6 sm:p-8">
                @if ($portrait)
                    <img src="{{ $portrait }}" alt="Portrait of {{ $settings['full_name'] }}" class="mb-6 size-20 rounded-2xl object-cover" loading="lazy" width="80" height="80">
                @endif
                <h3 class="text-sm font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400">At a glance</h3>
                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex gap-3">
                        <dt class="sr-only">Role</dt>
                        <i data-lucide="briefcase" class="mt-0.5 text-accent-700 dark:text-accent-400"></i>
                        <dd class="text-zinc-800 dark:text-zinc-200">{{ $settings['professional_title'] }}</dd>
                    </div>
                    <div class="flex gap-3">
                        <dt class="sr-only">Academic background</dt>
                        <i data-lucide="graduation-cap" class="mt-0.5 text-accent-700 dark:text-accent-400"></i>
                        <dd class="text-zinc-800 dark:text-zinc-200">Studies in Computer Science and Software Engineering</dd>
                    </div>
                    @if (filled($settings['location']))
                        <div class="flex gap-3">
                            <dt class="sr-only">Location</dt>
                            <i data-lucide="map-pin" class="mt-0.5 text-accent-700 dark:text-accent-400"></i>
                            <dd class="text-zinc-800 dark:text-zinc-200">{{ $settings['location'] }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($focusAreas)
                    <h3 class="mt-8 text-sm font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400">Focus areas</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        @foreach ($focusAreas as $area)
                            <li class="flex gap-2.5 text-zinc-800 dark:text-zinc-200"><i data-lucide="check" class="mt-0.5 text-accent-700 dark:text-accent-400"></i>{{ $area }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-8 flex flex-wrap gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-800">
                    <a href="{{ route('resume.show') }}" class="btn-secondary"><i data-lucide="file-text"></i> View CV</a>
                    <a href="#contact" class="btn-ghost">Get in touch</a>
                </div>
            </div>
        </aside>
    </div>
</section>
