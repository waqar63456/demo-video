<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verification_token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verification_token)
    {
        $this->verification_token = $verification_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.verify')
        ->subject('SignUp Request From - ' . config('app.name'))
                    ->with([
                        'verification_token' => $this->verification_token,
                    ]);
    }
}
