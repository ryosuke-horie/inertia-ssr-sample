<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterStaffMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $staffName;
    private string $email;
    private string $token;

    public function __construct(string $staffName, string $email, string $token)
    {
        $this->staffName = $staffName;
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'スタッフ新規登録メール',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.registerStaff',
            with: [
                'staffName' => $this->staffName,
                'url' => config('app.url') . '/staff/register?name=' . $this->staffName . '&email=' . $this->email . '&token=' . $this->token
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
