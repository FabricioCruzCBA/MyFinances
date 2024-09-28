<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     */
    public function __construct($mail, $assunto)
    {
        //
        $this->mail = $mail;
        $this->assunto = $assunto;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $this->to($this->mail->mail,$this->mail->mail);
        return new Envelope(
            subject: $this->assunto, 
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.sendmail',
            with: [
                'mail'=> $this->mail
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
