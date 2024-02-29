<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeBusinessEmailMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $businessName;
    private string $email;
    private string $token;

    public function __construct(string $businessName, string $email, string $token)
    {
        $this->businessName     = $businessName;
        $this->email    = $email;
        $this->token    = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'メールアドレス変更',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.changeBusinessEmail',
            with: [
                'businessName' => $this->businessName,
                'url' => config('app.url') . '/business-operator/change-email?businessName=' . $this->businessName . '&email=' . $this->email . '&token=' . $this->token
            ],
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
