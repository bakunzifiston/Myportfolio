<section id="skills" class="section" aria-labelledby="skills-heading">
    <div class="container-page">
        <x-site.section-heading eyebrow="Toolkit" title="Technical skills" id="skills-heading">
            The languages, frameworks and engineering practices I use to design and build applications.
        </x-site.section-heading>

        <dl class="mt-14 divide-y divide-zinc-200/80 border-y border-zinc-200/80 dark:divide-zinc-800 dark:border-zinc-800">
            @foreach ($skillCategories as $category)
                <div class="reveal grid gap-4 py-7 md:grid-cols-[18rem_1fr] md:gap-10">
                    <dt class="flex items-start gap-3.5">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-accent-50 text-accent-700 dark:bg-accent-950/40 dark:text-accent-400">
                            <i data-lucide="{{ $category->icon ?: 'layers' }}" class="size-[1.05em]"></i>
                        </span>
                        <span>
                            <span class="block font-semibold text-zinc-950 dark:text-white">{{ $category->name }}</span>
                            @if ($category->description)
                                <span class="mt-1 block text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $category->description }}</span>
                            @endif
                        </span>
                    </dt>
                    <dd>
                        <ul class="flex flex-wrap gap-2 md:pt-1" aria-label="{{ $category->name }}">
                            @foreach ($category->visibleSkills as $skill)
                                <li class="rounded-full bg-zinc-100 px-3.5 py-1.5 text-sm text-zinc-800 dark:bg-zinc-800/70 dark:text-zinc-200">{{ $skill->name }}</li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
