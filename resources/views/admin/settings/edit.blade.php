<x-layouts.admin title="Settings">
    <x-admin.header title="Profile & site settings" description="Personal details used across the website, CV and search results." />
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @foreach ($groups as $group => $fields)
            <section class="card grid gap-5 p-6">
                <h2 class="font-semibold">{{ $group }}</h2>
                @foreach ($fields as $key => $def)
                    @if ($def['type'] === 'file')
                        @php $url = \App\Support\Settings::fileUrl($key); @endphp
                        <div class="grid gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                            <x-admin.field :name="$key" :label="$def['label']" type="file" :accept="$def['accept'] ?? null" />
                            @if ($url)
                                <div class="flex items-center gap-4 text-sm">
                                    <a href="{{ $url }}" target="_blank" class="link-arrow">Current file <i data-lucide="external-link" class="size-3.5"></i></a>
                                    <x-admin.field :name="'remove_'.$key" label="Remove" type="checkbox" :value="false" />
                                </div>
                            @endif
                        </div>
                    @else
                        <x-admin.field :name="$key" :label="$def['label']" :type="$def['type']" :rows="$def['rows'] ?? 3" :value="$values[$key] ?? null" />
                    @endif
                @endforeach
            </section>
        @endforeach
        <div class="sticky bottom-4 flex justify-end">
            <button class="btn-primary shadow-lg"><i data-lucide="check"></i> Save settings</button>
        </div>
    </form>
</x-layouts.admin>
