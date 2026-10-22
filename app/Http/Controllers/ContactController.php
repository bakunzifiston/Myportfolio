<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Support\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    public function store(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'subject', 'message']),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        $recipient = config('portfolio.contact_recipient') ?: Settings::get('contact_email');

        if ($recipient) {
            try {
                Mail::to($recipient)->send(new ContactMessageReceived($message));
                $message->forceFill(['emailed_at' => now()])->save();
            } catch (Throwable $e) {
                // The message is already stored and visible in the admin inbox, so the visitor's submission still succeeded.
                Log::error('Contact message email notification failed.', ['id' => $message->id, 'error' => $e->getMessage()]);
            }
        }

        return redirect()->to(route('home').'#contact')
            ->with('contact_status', "Thank you, {$message->name}. Your message has been received and I will reply to {$message->email} as soon as I can.");
    }
}
