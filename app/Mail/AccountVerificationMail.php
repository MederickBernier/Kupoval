<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct($user, $verificationUrl)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->subject(__('emails.auth.verify_subject'))
            ->view('emails.auth.verify')
            ->with([
                'greeting' => __('emails.auth.verify_greeting', ['name' => $this->user->username]),
                'body' => __('emails.auth.verify_body'),
                'buttonText' => __('emails.auth.verify_button'),
                'buttonUrl' => $this->verificationUrl,
                'footer' => __('emails.auth.verify_footer'),
            ]);
    }
}
