<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp_code;
    public $otp_expiry_minutes;

    public function __construct($otp_code, $otp_expiry_minutes)
    {
        $this->otp_code = $otp_code;
        $this->otp_expiry_minutes = $otp_expiry_minutes;
    }

    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->view('emails.otp')
                    ->with([
                        'otp_code' => $this->otp_code,
                        'otp_expiry_minutes' => $this->otp_expiry_minutes,
                    ]);
    }
}
