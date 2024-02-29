<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeCorporationEmailMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $corporationName;
    private string $email;
    private string $token;

    public function __construct(string $corporationName, string $email, string $token)
    {
        $this->corporationName     = $corporationName;
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
            view: 'mails.changeCorporationEmail',
            with: [
                'corporationName' => $this->corporationName,
                'url' => config('app.url') . '/corporation/change-email?corporationName=' . $this->corporationName . '&email=' . $this->email . '&token=' . $this->token
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
