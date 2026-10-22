<x-layouts.admin title="Message">
    <x-admin.header :title="$message->subject">
        <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}" class="btn-primary"><i data-lucide="mail"></i> Reply</a>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Delete this message?">@csrf @method('DELETE')<button class="btn-secondary text-red-600 dark:text-red-400"><i data-lucide="trash-2"></i> Delete</button></form>
    </x-admin.header>
    <article class="card p-6">
        <dl class="grid gap-4 border-b border-zinc-200 pb-5 text-sm sm:grid-cols-3 dark:border-zinc-800">
            <div><dt class="text-zinc-500">From</dt><dd class="mt-1 font-medium text-zinc-950 dark:text-white">{{ $message->name }}</dd></div>
            <div><dt class="text-zinc-500">Email</dt><dd class="mt-1 break-all"><a href="mailto:{{ $message->email }}" class="link-arrow">{{ $message->email }}</a></dd></div>
            <div><dt class="text-zinc-500">Received</dt><dd class="mt-1">{{ $message->created_at->toDayDateTimeString() }}</dd></div>
        </dl>
        <div class="mt-5 leading-relaxed whitespace-pre-line text-zinc-800 dark:text-zinc-200">{{ $message->message }}</div>
    </article>
    <a href="{{ route('admin.messages.index') }}" class="link-arrow mt-6"><i data-lucide="arrow-left" class="size-4"></i> All messages</a>
</x-layouts.admin>
