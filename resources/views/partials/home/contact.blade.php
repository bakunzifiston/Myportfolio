<section id="contact" class="section bg-zinc-50/70 dark:bg-zinc-900/25" aria-labelledby="contact-heading">
    <div class="container-page grid gap-12 lg:grid-cols-[1fr_1.3fr] lg:gap-16">
        <div>
            <x-site.section-heading eyebrow="Contact" title="Let's build something useful together." id="contact-heading">
                Whether you are hiring, planning a digital project or exploring a collaboration, I would be glad to hear from you.
            </x-site.section-heading>

            <ul class="reveal mt-10 space-y-3">
                @if (filled($settings['contact_email']))
                    <li>
                        <a href="mailto:{{ $settings['contact_email'] }}" class="card group flex items-center gap-4 p-4 transition hover:border-zinc-300 dark:hover:border-zinc-700">
                            <span class="grid size-10 place-items-center rounded-lg bg-accent-50 text-accent-700 dark:bg-accent-950/50 dark:text-accent-300"><i data-lucide="mail"></i></span>
                            <span><span class="block text-xs text-zinc-500 dark:text-zinc-400">Email</span><span class="font-medium text-zinc-950 dark:text-white">{{ $settings['contact_email'] }}</span></span>
                        </a>
                    </li>
                @endif
                @if (filled($settings['phone']))
                    <li>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['phone']) }}" class="card group flex items-center gap-4 p-4 transition hover:border-zinc-300 dark:hover:border-zinc-700">
                            <span class="grid size-10 place-items-center rounded-lg bg-accent-50 text-accent-700 dark:bg-accent-950/50 dark:text-accent-300"><i data-lucide="phone"></i></span>
                            <span><span class="block text-xs text-zinc-500 dark:text-zinc-400">Phone</span><span class="font-medium text-zinc-950 dark:text-white">{{ $settings['phone'] }}</span></span>
                        </a>
                    </li>
                @endif
                @foreach ($socialLinks as $link)
                    <li>
                        <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer me" class="card group flex items-center gap-4 p-4 transition hover:border-zinc-300 dark:hover:border-zinc-700">
                            <span class="grid size-10 place-items-center rounded-lg bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200"><x-site.social-icon :link="$link" /></span>
                            <span class="flex-1"><span class="block text-xs text-zinc-500 dark:text-zinc-400">{{ $link->label }}</span><span class="font-medium text-zinc-950 dark:text-white">{{ \Illuminate\Support\Str::of($link->url)->after('://')->rtrim('/') }}</span></span>
                            <i data-lucide="arrow-up-right" class="text-zinc-400 group-hover:text-accent-700 dark:group-hover:text-accent-400"></i>
                        </a>
                    </li>
                @endforeach
                @if (filled($settings['location']))
                    <li class="flex items-center gap-4 p-4 text-sm text-zinc-600 dark:text-zinc-400"><i data-lucide="map-pin"></i> {{ $settings['location'] }}</li>
                @endif
            </ul>
        </div>

        <div class="reveal card p-6 sm:p-8">
            @if (session('contact_status'))
                <div class="mb-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                    <i data-lucide="circle-check" class="mt-0.5"></i>
                    <p>{{ session('contact_status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-900 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200" role="alert">
                    <i data-lucide="circle-alert" class="mt-0.5"></i>
                    <p>Your message was not sent. Please check the highlighted fields and try again.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="grid gap-5 sm:grid-cols-2" data-submit-once>
                @csrf
                <input type="hidden" name="form_token" value="{{ $contactToken }}">
                {{-- Honeypot: invisible to people and assistive technology. --}}
                <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                @foreach ([
                    ['name', 'Name', 'text', 'name', 'sm:col-span-1'],
                    ['email', 'Email', 'email', 'email', 'sm:col-span-1'],
                    ['subject', 'Subject', 'text', 'off', 'sm:col-span-2'],
                ] as [$field, $label, $type, $autocomplete, $span])
                    <div class="{{ $span }}">
                        <label for="contact-{{ $field }}" class="form-label">{{ $label }}</label>
                        <input id="contact-{{ $field }}" name="{{ $field }}" type="{{ $type }}" value="{{ old($field) }}" autocomplete="{{ $autocomplete }}" required
                               maxlength="{{ $field === 'email' ? 190 : ($field === 'name' ? 120 : 180) }}"
                               class="form-input" @error($field) aria-invalid="true" aria-describedby="contact-{{ $field }}-error" @enderror>
                        @error($field)
                            <p id="contact-{{ $field }}-error" class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="sm:col-span-2">
                    <label for="contact-message" class="form-label">Message</label>
                    <textarea id="contact-message" name="message" rows="6" required maxlength="5000" class="form-input resize-y"
                              @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror>{{ old('message') }}</textarea>
                    @error('message')
                        <p id="contact-message-error" class="form-error">{{ $message }}</p>
                    @enderror
                    @error('form_token')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    @error('website')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 sm:col-span-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Your details are used only to reply to your message.</p>
                    <button type="submit" class="btn-accent px-6 py-3"><i data-lucide="send"></i> <span data-label>Send message</span></button>
                </div>
            </form>
        </div>
    </div>
</section>
