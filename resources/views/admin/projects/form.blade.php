<x-layouts.admin :title="$project->exists ? 'Edit '.$project->title : 'New project'">
    <x-admin.header :title="$project->exists ? 'Edit project' : 'New project'" description="Only add details that are confirmed. Empty fields are simply not shown on the site.">
        @if ($project->exists && $project->isPublished())
            <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn-secondary"><i data-lucide="eye"></i> View</a>
        @endif
    </x-admin.header>

    <form method="POST" enctype="multipart/form-data" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" class="space-y-6">
        @csrf
        @if ($project->exists) @method('PUT') @endif

        <section class="card grid gap-5 p-6 sm:grid-cols-2">
            <h2 class="font-semibold sm:col-span-2">Basics</h2>
            <x-admin.field name="title" label="Title" :value="$project->title" required />
            <x-admin.field name="slug" label="URL slug" :value="$project->slug" help="Leave empty to generate from the title." />
            <x-admin.field name="project_category_id" label="Category" type="select" :value="$project->project_category_id"
                           :options="['' => '— None —'] + $categories->pluck('name', 'id')->all()" />
            <x-admin.field name="role" label="My role" :value="$project->role" placeholder="e.g. Full-stack developer" />
            <x-admin.field name="short_description" label="Short description" type="textarea" rows="2" :value="$project->short_description" help="Shown on project cards and in search results (max 300 characters)." class="sm:col-span-2" />
        </section>

        <section class="card grid gap-5 p-6">
            <h2 class="font-semibold">Case study</h2>
            <x-admin.field name="overview" label="Detailed overview" type="textarea" rows="5" :value="$project->overview" help="Separate paragraphs with a blank line." />
            <x-admin.field name="contribution" label="My contribution" type="textarea" rows="4" :value="$project->contribution" />
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field name="technologies" label="Technologies used" type="lines" rows="5" :value="$project->technologies" help="One per line." />
                <x-admin.field name="key_features" label="Key features" type="lines" rows="5" :value="$project->key_features" help="One per line." />
                <x-admin.field name="challenges" label="Challenges" type="textarea" rows="4" :value="$project->challenges" />
                <x-admin.field name="solutions" label="Solutions" type="textarea" rows="4" :value="$project->solutions" />
            </div>
        </section>

        <section class="card grid gap-5 p-6 sm:grid-cols-2">
            <h2 class="font-semibold sm:col-span-2">Media and links</h2>
            <div class="space-y-3">
                @if ($project->thumbnailUrl())
                    <img src="{{ $project->thumbnailUrl() }}" alt="" class="aspect-video w-full rounded-lg border border-zinc-200 object-cover dark:border-zinc-800">
                    <x-admin.field name="remove_thumbnail" label="Remove current thumbnail" type="checkbox" :value="false" />
                @endif
                <x-admin.field name="thumbnail_upload" label="Thumbnail image" type="file" accept="image/jpeg,image/png,image/webp" help="JPG, PNG or WebP, up to 4 MB. Without one, a neutral generated cover is used." />
            </div>
            <x-admin.field name="thumbnail_alt" label="Thumbnail alt text" :value="$project->thumbnail_alt" help="Describe the image for screen-reader users." />

            <div class="sm:col-span-2">
                @if ($project->screenshots)
                    <p class="form-label">Current screenshots</p>
                    <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($project->screenshots as $i => $path)
                            <label class="block space-y-2 text-xs">
                                <img src="{{ $project->screenshotUrls()[$i] }}" alt="" class="aspect-video w-full rounded-lg border border-zinc-200 object-cover dark:border-zinc-800">
                                <span class="flex items-center gap-2"><input type="checkbox" name="remove_screenshots[]" value="{{ $path }}" class="accent-red-600"> Remove</span>
                            </label>
                        @endforeach
                    </div>
                @endif
                <x-admin.field name="screenshot_uploads[]" label="Add screenshots" type="file" accept="image/jpeg,image/png,image/webp" multiple help="Up to 8 images per upload." />
            </div>

            <x-admin.field name="live_url" label="Live demo URL" type="url" :value="$project->live_url" placeholder="https://" />
            <x-admin.field name="repository_url" label="GitHub repository URL" type="url" :value="$project->repository_url" placeholder="https://github.com/…" />
        </section>

        <section class="card grid gap-5 p-6 sm:grid-cols-3">
            <h2 class="font-semibold sm:col-span-3">Publishing</h2>
            <x-admin.field name="status" label="Status" type="select" :value="$project->status" :options="\App\Models\Project::STATUSES" required />
            <x-admin.field name="sort_order" label="Display order" type="number" :value="$project->sort_order" help="Lower numbers appear first." />
            <x-admin.field name="is_featured" label="Featured project" type="checkbox" :value="$project->is_featured" help="Shown on the home page." class="sm:pt-7" />
        </section>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.projects.index') }}" class="btn-ghost">Cancel</a>
            <button class="btn-primary">Save project</button>
        </div>
    </form>
</x-layouts.admin>
