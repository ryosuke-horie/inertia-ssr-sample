<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeStaffEmailMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $name;
    private string $email;
    private string $token;

    public function __construct(string $name, string $email, string $token)
    {
        $this->name     = $name;
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
            view: 'mails.changeStaffEmail',
            with: [
                'name' => $this->name,
                'url' => config('app.url') . '/staff/change-email?name=' . $this->name . '&email=' . $this->email . '&token=' . $this->token
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
