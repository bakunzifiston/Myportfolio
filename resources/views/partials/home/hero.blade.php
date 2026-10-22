<section id="top" class="relative overflow-hidden" aria-labelledby="hero-heading">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_70%_60%_at_60%_30%,black,transparent)]" aria-hidden="true"></div>

    <div class="container-page relative grid items-center gap-14 pt-14 pb-28 sm:pt-20 lg:grid-cols-[1.15fr_1fr] lg:gap-12 lg:pt-24 lg:pb-28">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white/70 py-1 pr-3 pl-1.5 text-xs font-medium text-zinc-700 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/70 dark:text-zinc-300">
                <span class="grid size-5 place-items-center rounded-full bg-accent-100 text-accent-800 dark:bg-accent-900/60 dark:text-accent-300"><i data-lucide="code-xml" class="size-3"></i></span>
                {{ $settings['availability'] ?: 'Software Developer · Full-Stack Developer' }}
            </p>

            @php
                // Highlight the name when it appears in the heading; everything stays escaped.
                $heading = e($settings['hero_heading']);
                $name = e($settings['full_name']);
                if ($name !== '' && str_contains($heading, $name)) {
                    $heading = str_replace($name, '<span class="text-accent-700 dark:text-accent-400">'.$name.'</span>', $heading);
                }
            @endphp
            <h1 id="hero-heading" class="mt-6 text-4xl leading-[1.08] font-semibold tracking-tight sm:text-5xl lg:text-[3.5rem]">
                {!! $heading !!}
            </h1>

            <p class="mt-6 max-w-xl text-lg leading-relaxed text-zinc-600 dark:text-zinc-400">
                {{ $settings['hero_text'] }}
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                <a href="#projects" class="btn-accent px-6 py-3">Explore My Projects <i data-lucide="arrow-right"></i></a>
                <a href="{{ route('resume.download') }}" class="btn-secondary px-6 py-3"><i data-lucide="download"></i> Download My CV</a>
                <a href="#contact" class="link-arrow justify-center px-2 py-3 sm:justify-start">Let's Connect <i data-lucide="arrow-up-right" class="size-4"></i></a>
            </div>

            @php
                $technologyCount = $skillCategories->flatMap->visibleSkills->pluck('name')->unique()->count();
            @endphp
            @if ($projectCount || $technologyCount)
                <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-zinc-200 pt-6 dark:border-zinc-800">
                    <div>
                        <dt class="text-xs text-zinc-500 dark:text-zinc-400">Projects</dt>
                        <dd class="mt-1 font-mono text-2xl font-semibold text-zinc-950 dark:text-white">{{ $projectCount }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-zinc-500 dark:text-zinc-400">Skill areas</dt>
                        <dd class="mt-1 font-mono text-2xl font-semibold text-zinc-950 dark:text-white">{{ $skillCategories->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-zinc-500 dark:text-zinc-400">Tools &amp; practices</dt>
                        <dd class="mt-1 font-mono text-2xl font-semibold text-zinc-950 dark:text-white">{{ $technologyCount }}</dd>
                    </div>
                </dl>
            @endif
        </div>

        @include('partials.home.hero-visual')
    </div>
</section>
