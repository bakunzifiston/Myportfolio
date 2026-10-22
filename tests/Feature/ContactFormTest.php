<?php

namespace Tests\Feature;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ada Recruiter',
            'email' => 'ada@example.com',
            'subject' => 'Developer role',
            'message' => 'Hello, I would like to talk about an opportunity.',
            'website' => '',
            'form_token' => ContactRequest::freshToken(),
        ], $overrides);
    }

    public function test_validation_errors_are_returned(): void
    {
        $this->from('/')->post('/contact', $this->payload(['name' => '', 'email' => 'not-an-email', 'message' => 'short']))
            ->assertRedirect('/')
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_honeypot_submissions_are_rejected(): void
    {
        $payload = $this->payload(['website' => 'http://spam.example']);
        $this->travel(10)->seconds();

        $this->post('/contact', $payload)->assertSessionHasErrors('website');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_instant_submissions_are_rejected(): void
    {
        $this->post('/contact', $this->payload())->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_tampered_token_is_rejected(): void
    {
        $this->post('/contact', $this->payload(['form_token' => 'forged']))->assertSessionHasErrors('form_token');
    }

    public function test_message_is_stored_and_emailed_when_a_recipient_is_configured(): void
    {
        Mail::fake();
        config(['portfolio.contact_recipient' => 'owner@example.test']);
        $payload = $this->payload();
        $this->travel(10)->seconds();

        $this->post('/contact', $payload)
            ->assertRedirect(route('home').'#contact')
            ->assertSessionHas('contact_status');

        $message = ContactMessage::sole();
        $this->assertSame('ada@example.com', $message->email);
        $this->assertNotNull($message->emailed_at);
        Mail::assertSent(ContactMessageReceived::class, fn ($mail) => $mail->hasTo('owner@example.test') && $mail->hasReplyTo('ada@example.com'));
    }

    public function test_message_is_stored_without_email_when_no_recipient_is_configured(): void
    {
        Mail::fake();
        config(['portfolio.settings.Contact.contact_email.default' => null]);
        $payload = $this->payload();
        $this->travel(10)->seconds();

        $this->post('/contact', $payload)->assertSessionHas('contact_status');

        $this->assertNull(ContactMessage::sole()->emailed_at);
        Mail::assertNothingSent();
    }

    public function test_csrf_protection_is_active_for_the_contact_route(): void
    {
        $this->assertContains('web', app('router')->getRoutes()->getByName('contact.store')->gatherMiddleware());
    }
}
