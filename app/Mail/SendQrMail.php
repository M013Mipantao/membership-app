<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendQrMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $messageContent;
    public function __construct($messageContent)
    {
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->view('emails.emailVerification')->with(
            [
                'message' => $this->messageContent
            ]
        );
    }
}
