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
    public $lang;

    public function __construct($user, $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
        $this->lang = app()->getLocale();
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view("emails.{$this->lang}.auth.password_reset") // Load the email template based on locale
            ->with([
                'name' => $this->user->username,
                'resetUrl' => $this->resetUrl,
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => 'Réinitialisation de votre mot de passe - ' . config('app.name'),
            default => 'Password Reset Request - ' . config('app.name')
        };
    }
}
