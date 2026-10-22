<section id="top" class="relative overflow-hidden" aria-labelledby="hero-heading">
    <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_65%_55%_at_70%_40%,black,transparent)]" aria-hidden="true"></div>

    <div class="container-page relative grid items-center gap-12 pt-16 pb-24 sm:pt-24 md:gap-16 lg:min-h-[min(46rem,calc(100svh-4rem))] lg:grid-cols-[1.05fr_1fr] lg:gap-8 lg:py-20">
        <div class="max-w-2xl">
            <p class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white/70 py-1 pr-3.5 pl-2 text-xs font-medium text-zinc-600 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/60 dark:text-zinc-400">
                <span class="relative flex size-2"><span class="absolute inline-flex size-full rounded-full bg-accent-500 opacity-60 motion-safe:animate-ping"></span><span class="relative inline-flex size-2 rounded-full bg-accent-500"></span></span>
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
            <h1 id="hero-heading" class="mt-7 text-[2.6rem] leading-[1.05] font-semibold tracking-[-0.035em] sm:text-6xl lg:text-[4.25rem]">
                {!! $heading !!}
            </h1>

            <p class="mt-6 max-w-xl text-lg leading-relaxed text-zinc-600 sm:text-xl sm:leading-relaxed dark:text-zinc-400">
                {{ $settings['hero_text'] }}
            </p>

            <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-2.5">
                <a href="#projects" class="btn-accent px-5 py-3">Explore My Projects <i data-lucide="arrow-right"></i></a>
                <a href="{{ route('resume.download') }}" class="btn-secondary px-5 py-3"><i data-lucide="download"></i> Download My CV</a>
                <a href="#contact" class="link-arrow justify-center px-2 py-3 sm:justify-start">Let's Connect <i data-lucide="arrow-up-right" class="size-4"></i></a>
            </div>

            @php
                $technologyCount = $skillCategories->flatMap->visibleSkills->pluck('name')->unique()->count();
            @endphp
            @if ($projectCount || $technologyCount)
                <dl class="mt-14 flex gap-10 text-sm sm:gap-14">
                    @foreach ([['Projects', $projectCount], ['Skill areas', $skillCategories->count()], ['Tools & practices', $technologyCount]] as [$label, $value])
                        <div class="flex flex-col-reverse">
                            <dt class="mt-1 text-zinc-500 dark:text-zinc-400">{{ $label }}</dt>
                            <dd class="font-mono text-2xl font-semibold tracking-tight text-zinc-950 dark:text-white">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>

        <div class="hidden md:block">
            @include('partials.home.hero-visual')
        </div>
    </div>
</section>
