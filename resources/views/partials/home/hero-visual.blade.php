{{--
    Hero visual: the system-architecture diagram. This static SVG is shown by default; on capable devices
    app.js lazy-loads the Three.js version into this container and cross-fades to it (see hero-scene.js).
--}}
<div class="hero-visual relative mx-auto aspect-[6/5] w-full max-w-[30rem] lg:max-w-[38rem]" data-hero-visual aria-hidden="true">
    <svg viewBox="0 0 480 400" class="hero-fallback absolute inset-0 h-full w-full font-sans" role="presentation">
        <g class="fill-none stroke-zinc-300 dark:stroke-zinc-700" stroke-width="1.5">
            <path d="M240 108 V158" />
            <path d="M190 192 H95 V272" />
            <path d="M290 192 H385 V272" />
            <path d="M240 226 V272" />
        </g>
        <g class="fill-accent-600 dark:fill-accent-400">
            <circle cx="240" cy="133" r="3" />
            <circle cx="140" cy="192" r="3" />
            <circle cx="385" cy="240" r="3" />
            <circle cx="240" cy="250" r="3" />
        </g>

        <rect x="130" y="48" width="220" height="60" rx="12" class="fill-white stroke-zinc-200 dark:fill-zinc-900 dark:stroke-zinc-700" />
        <text x="240" y="74" text-anchor="middle" class="fill-zinc-900 text-[14px] font-semibold dark:fill-zinc-100">Web &amp; Mobile Clients</text>
        <text x="240" y="93" text-anchor="middle" class="fill-zinc-500 font-mono text-[10px] dark:fill-zinc-400">HTML · Tailwind · Next.js</text>

        <rect x="185" y="158" width="110" height="68" rx="12" class="fill-zinc-950 dark:fill-white" />
        <text x="240" y="188" text-anchor="middle" class="fill-white text-[14px] font-semibold dark:fill-zinc-950">REST API</text>
        <text x="240" y="207" text-anchor="middle" class="fill-accent-300 font-mono text-[10px] dark:fill-accent-700">Laravel · Node</text>

        <rect x="25" y="272" width="140" height="64" rx="12" class="fill-white stroke-zinc-200 dark:fill-zinc-900 dark:stroke-zinc-700" />
        <text x="95" y="299" text-anchor="middle" class="fill-zinc-900 text-[12px] font-semibold dark:fill-zinc-100">Auth &amp; Access</text>
        <text x="95" y="318" text-anchor="middle" class="fill-zinc-500 font-mono text-[10px] dark:fill-zinc-400">roles · policies</text>

        <rect x="214" y="272" width="52" height="64" rx="12" class="fill-accent-50 stroke-accent-200 dark:fill-accent-950/60 dark:stroke-accent-800" />
        <text x="240" y="308" text-anchor="middle" class="fill-accent-800 font-mono text-[10px] font-medium dark:fill-accent-300">jobs</text>

        <g class="fill-white stroke-zinc-200 dark:fill-zinc-900 dark:stroke-zinc-700">
            <path d="M325 286 v36 a60 12 0 0 0 120 0 v-36" />
            <ellipse cx="385" cy="286" rx="60" ry="12" />
        </g>
        <path d="M325 304 a60 12 0 0 0 120 0" class="fill-none stroke-accent-600/60 dark:stroke-accent-400/60" />
        <text x="385" y="318" text-anchor="middle" class="fill-zinc-900 text-[12px] font-semibold dark:fill-zinc-100">Data Layer</text>
        <text x="385" y="360" text-anchor="middle" class="fill-zinc-500 font-mono text-[10px] dark:fill-zinc-400">MySQL · PostgreSQL</text>
    </svg>
</div>
