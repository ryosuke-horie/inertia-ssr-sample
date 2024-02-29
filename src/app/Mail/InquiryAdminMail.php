<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryAdminMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $name;
    private string $email;
    private string $content;

    public function __construct(string $name, string $email, string $content)
    {
        $this->name    = $name;
        $this->email   = $email;
        $this->content = $content;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'お問い合わせ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.inquiryAdmin',
            with: [
                'name'    => $this->name,
                'email'   => $this->email,
                'content' => $this->content,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
