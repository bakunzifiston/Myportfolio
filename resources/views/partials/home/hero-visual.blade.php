{{-- Decorative illustration of a layered system: clients → API → auth, services and data. --}}
<div class="relative mx-auto w-full max-w-lg lg:max-w-none" aria-hidden="true">
    <div class="card overflow-hidden shadow-[0_24px_60px_-28px_rgb(0_0_0/0.25)] dark:shadow-[0_24px_60px_-28px_rgb(0_0_0/0.8)]">
        <div class="flex items-center gap-2 border-b border-zinc-200/80 bg-zinc-50/80 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900">
            <span class="size-2.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
            <span class="size-2.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
            <span class="size-2.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
            <span class="ml-3 font-mono text-[11px] text-zinc-500 dark:text-zinc-400">system-architecture.svg</span>
        </div>

        <svg viewBox="0 0 480 340" class="block h-auto w-full font-sans" role="presentation">
            <defs>
                <pattern id="dots" width="16" height="16" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" class="fill-zinc-200 dark:fill-zinc-800" />
                </pattern>
            </defs>
            <rect width="480" height="340" fill="url(#dots)" />

            {{-- connections --}}
            <g class="fill-none stroke-zinc-300 dark:stroke-zinc-700" stroke-width="1.5">
                <path d="M240 98 V138" />
                <path d="M190 172 H140 V236" />
                <path d="M290 172 H340 V236" />
                <path d="M240 206 V236" />
            </g>
            <g class="flow-line fill-none stroke-accent-600 dark:stroke-accent-400" stroke-width="1.5">
                <path d="M240 98 V138" />
                <path d="M190 172 H140 V236" />
                <path d="M290 172 H340 V236" />
                <path d="M240 206 V236" />
            </g>

            {{-- clients --}}
            <g>
                <rect x="150" y="38" width="180" height="60" rx="12" class="fill-white stroke-zinc-200 dark:fill-zinc-900 dark:stroke-zinc-700" />
                <text x="240" y="63" text-anchor="middle" class="fill-zinc-900 text-[13px] font-semibold dark:fill-zinc-100">Web &amp; Mobile Clients</text>
                <text x="240" y="82" text-anchor="middle" class="fill-zinc-500 font-mono text-[10px] dark:fill-zinc-400">HTML · Tailwind · Next.js</text>
            </g>

            {{-- api --}}
            <g>
                <rect x="190" y="138" width="100" height="68" rx="12" class="fill-zinc-950 dark:fill-white" />
                <text x="240" y="168" text-anchor="middle" class="fill-white text-[13px] font-semibold dark:fill-zinc-950">REST API</text>
                <text x="240" y="187" text-anchor="middle" class="fill-accent-300 font-mono text-[10px] dark:fill-accent-700">Laravel · Node</text>
            </g>

            {{-- services row --}}
            <g>
                <rect x="70" y="236" width="140" height="64" rx="12" class="fill-white stroke-zinc-200 dark:fill-zinc-900 dark:stroke-zinc-700" />
                <text x="140" y="263" text-anchor="middle" class="fill-zinc-900 text-[12px] font-semibold dark:fill-zinc-100">Auth &amp; Access</text>
                <text x="140" y="282" text-anchor="middle" class="fill-zinc-500 font-mono text-[10px] dark:fill-zinc-400">roles · policies</text>
            </g>
            <g>
                <rect x="215" y="236" width="50" height="64" rx="12" class="fill-accent-50 stroke-accent-200 dark:fill-accent-950/60 dark:stroke-accent-800" />
                <text x="240" y="273" text-anchor="middle" class="fill-accent-800 font-mono text-[10px] font-medium dark:fill-accent-300">jobs</text>
            </g>
            <g>
                <rect x="270" y="236" width="140" height="64" rx="12" class="fill-white stroke-zinc-200 dark:fill-zinc-900 dark:stroke-zinc-700" />
                <text x="340" y="263" text-anchor="middle" class="fill-zinc-900 text-[12px] font-semibold dark:fill-zinc-100">Data Layer</text>
                <text x="340" y="282" text-anchor="middle" class="fill-zinc-500 font-mono text-[10px] dark:fill-zinc-400">MySQL · PostgreSQL</text>
            </g>
        </svg>
    </div>

    {{-- code snippet card --}}
    <div class="absolute -bottom-24 -left-3 hidden w-64 rounded-xl border border-zinc-800 bg-zinc-950 p-4 font-mono text-[11px] leading-relaxed text-zinc-300 shadow-xl sm:block lg:-left-10">
        <p><span class="text-accent-400">Route</span>::<span class="text-sky-300">get</span>(<span class="text-amber-200">'/projects'</span>,</p>
        <p class="pl-4">[<span class="text-accent-400">ProjectController</span>::<span class="text-zinc-500">class</span>,</p>
        <p class="pl-5"><span class="text-amber-200">'index'</span>])</p>
        <p class="pl-2">-&gt;<span class="text-sky-300">name</span>(<span class="text-amber-200">'projects.index'</span>);</p>
    </div>

    <div class="absolute -top-4 -right-2 hidden items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-xs font-medium text-zinc-700 shadow-lg sm:flex dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">
        <span class="size-2 rounded-full bg-emerald-500"></span> Secure · Scalable · Usable
    </div>
</div>
