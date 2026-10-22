New message from the portfolio contact form

Name:    {{ $contactMessage->name }}
Email:   {{ $contactMessage->email }}
Subject: {{ $contactMessage->subject }}
Received: {{ $contactMessage->created_at?->toDayDateTimeString() }}

{{ $contactMessage->message }}

--
Reply directly to this email to respond to {{ $contactMessage->name }}.
View all messages: {{ route('admin.messages.index') }}
