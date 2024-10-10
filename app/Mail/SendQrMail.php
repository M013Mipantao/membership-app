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

    public $guest_name;
    public $member_name;
    public $visit;
    public $visit_duration;
    public $qr_id;
    public $qr_code;

    public function __construct($guest_name, $member_name, $visit, $visit_duration, $qr_id, $qr_code)
    {
        $this->guest_name = $guest_name;
        $this->member_name = $member_name;
        $this->visit = $visit;
        $this->visit_duration = $visit_duration;
        $this->qr_id = $qr_id;
        $this->qr_code = $qr_code;
    }

    public function build()
    {
        return $this->view('emails.emailVerification')->with(
            [
                'guest_name' => $this->guest_name,
                'member_name' => $this->member_name,
                'visit' => $this->visit,
                'visit_duration' => $this->visit_duration,
                'qr_id' => $this->qr_id,
                'qrCodeUrl' => $this->qr_code
            ]
        );
    }
}
