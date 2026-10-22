<x-layouts.admin title="Messages">
    <x-admin.header title="Messages" description="Submissions from the contact form. Messages are stored here even when email delivery is not configured." />
    <x-admin.table>
        <x-slot:head><tr><th class="px-4 py-3">From</th><th class="px-4 py-3">Subject</th><th class="px-4 py-3">Received</th><th class="px-4 py-3">Email</th><th class="px-4 py-3"></th></tr></x-slot:head>
        @forelse ($messages as $message)
            <tr @class(['bg-accent-50/40 dark:bg-accent-950/20' => ! $message->read_at])>
                <td class="px-4 py-3"><span @class(['font-semibold text-zinc-950 dark:text-white' => ! $message->read_at])>{{ $message->name }}</span><span class="block text-xs text-zinc-500">{{ $message->email }}</span></td>
                <td class="px-4 py-3"><a href="{{ route('admin.messages.show', $message) }}" class="hover:underline">{{ \Illuminate\Support\Str::limit($message->subject, 60) }}</a></td>
                <td class="px-4 py-3 whitespace-nowrap text-zinc-500">{{ $message->created_at->diffForHumans() }}</td>
                <td class="px-4 py-3"><x-admin.status :on="(bool) $message->emailed_at" onLabel="Forwarded" offLabel="Stored only" /></td>
                <td class="px-4 py-3">
                    <div class="flex justify-end gap-1">
                        <a href="{{ route('admin.messages.show', $message) }}" class="btn-ghost px-2.5 py-1.5" aria-label="Open"><i data-lucide="eye" class="size-4"></i></a>
                        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Delete this message?">@csrf @method('DELETE')<button class="btn-ghost px-2.5 py-1.5 text-red-600 dark:text-red-400" aria-label="Delete"><i data-lucide="trash-2" class="size-4"></i></button></form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No messages yet.</td></tr>
        @endforelse
    </x-admin.table>
    <div class="mt-6">{{ $messages->links() }}</div>
</x-layouts.admin>
