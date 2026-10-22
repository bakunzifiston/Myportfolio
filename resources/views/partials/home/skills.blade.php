<section id="skills" class="section border-t border-zinc-200/80 dark:border-zinc-800" aria-labelledby="skills-heading">
    <div class="container-page">
        <x-site.section-heading eyebrow="Toolkit" title="Technical skills" id="skills-heading">
            The languages, frameworks and engineering practices I use to design and build applications.
        </x-site.section-heading>

        <div class="mt-12 grid gap-5 md:grid-cols-2">
            @foreach ($skillCategories as $category)
                <div @class([
                    'reveal card flex flex-col p-6',
                    'md:col-span-2' => $loop->last && $loop->odd,
                ])>
                    <div class="flex items-start gap-4">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-accent-50 text-accent-700 ring-1 ring-accent-100 dark:bg-accent-950/50 dark:text-accent-300 dark:ring-accent-900">
                            <i data-lucide="{{ $category->icon ?: 'layers' }}"></i>
                        </span>
                        <div>
                            <h3 class="font-semibold">{{ $category->name }}</h3>
                            @if ($category->description)
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $category->description }}</p>
                            @endif
                        </div>
                    </div>
                    <ul class="mt-5 flex flex-wrap gap-2">
                        @foreach ($category->visibleSkills as $skill)
                            <li class="rounded-lg border border-zinc-200 px-3 py-1.5 text-sm text-zinc-800 dark:border-zinc-700 dark:text-zinc-200">{{ $skill->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
