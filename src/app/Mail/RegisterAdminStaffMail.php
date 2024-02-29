<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterAdminStaffMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $name;
    private string $email;
    private string $token;
    private array $staffIds;

    public function __construct(string $name, string $email, array $staffIds, string $token)
    {
        $this->name     = $name;
        $this->email    = $email;
        $this->staffIds = $staffIds;
        $this->token    = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '管理者スタッフ新規登録メール',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.registerAdminStaff',
            with: [
                'name' => $this->name,
                'url' =>
                    config('app.url') . '/admin-staff/register?name=' . $this->name . '&email=' . $this->email . '&token=' . $this->token . '&ids=' . implode(',', $this->staffIds)
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
