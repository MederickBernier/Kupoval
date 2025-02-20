<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;

    public function __construct($user, $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        return $this->subject(__('emails.auth.reset_subject'))
            ->view('emails.auth.password_reset')
            ->with([
                'greeting' => __('emails.auth.reset_greeting', ['name' => $this->user->username]),
                'body' => __('emails.auth.reset_body'),
                'buttonText' => __('emails.auth.reset_button'),
                'buttonUrl' => $this->resetUrl,
                'footer' => __('emails.auth.reset_footer'),
            ]);
    }
}
