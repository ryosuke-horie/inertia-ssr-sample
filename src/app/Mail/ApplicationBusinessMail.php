<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationBusinessMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '関連店舗追加申請',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.applicationBusiness',
            with: [
                'businessName'          => $this->data['business_name'],
                'corporationName'       => $this->data['corporation_name'],
                'applicant_name'        => $this->data['applicant_name'],
                'applicant_name_kana'   => $this->data['applicant_name_kana'],
                'zip_code'              => $this->data['zip_code'],
                'pref_code'             => $this->data['pref_code'],
                'city'                  => $this->data['city'],
                'phone'                 => $this->data['phone'],
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
