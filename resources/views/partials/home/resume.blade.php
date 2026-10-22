<section class="border-t border-zinc-200/80 dark:border-zinc-800" aria-labelledby="resume-cta-heading">
    <div class="container-page py-16">
        <div class="reveal relative overflow-hidden rounded-3xl bg-zinc-950 px-6 py-12 sm:px-12 dark:bg-zinc-900 dark:ring-1 dark:ring-zinc-800">
            <div class="bg-grid pointer-events-none absolute inset-0 opacity-60 [mask-image:linear-gradient(to_left,black,transparent)] dark:opacity-100" aria-hidden="true" style="background-image: linear-gradient(to right, rgb(255 255 255 / .06) 1px, transparent 1px), linear-gradient(to bottom, rgb(255 255 255 / .06) 1px, transparent 1px);"></div>
            <div class="relative flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                <div class="max-w-xl">
                    <p class="font-mono text-xs tracking-wider text-accent-400 uppercase">Resume</p>
                    <h2 id="resume-cta-heading" class="mt-3 text-2xl font-semibold tracking-tight text-white sm:text-3xl">Prefer the one-page version?</h2>
                    <p class="mt-3 text-zinc-400">View my CV online or download a clean, printable PDF with my summary, skills, projects and education.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('resume.download') }}" class="btn bg-white px-5 py-3 text-zinc-950 hover:bg-zinc-200"><i data-lucide="download"></i> Download CV (PDF)</a>
                    <a href="{{ route('resume.show') }}" class="btn border border-zinc-700 px-5 py-3 text-white hover:border-zinc-500 hover:bg-white/5">View online</a>
                </div>
            </div>
        </div>
    </div>
</section>
