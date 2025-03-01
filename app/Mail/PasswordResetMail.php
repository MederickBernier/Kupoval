<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PasswordResetMail
 *
 * This class is responsible for sending password reset emails to users.
 * It extends the Mailable class and implements the ShouldQueue interface
 * to allow the email to be queued for sending.
 *
 * @package App\Mail
 */

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * PasswordResetMail constructor.
 *
 * @param \App\Models\User $user The user who requested the password reset.
 * @param string $resetUrl The URL for resetting the password.
 */
public function __construct($user, $resetUrl)
{
    // ...
}

/**
 * Build the message.
 *
 * @return $this
 */
public function build()
{
    // ...
}

/**
 * Get the subject line for the email based on the locale.
 *
 * @return string
 */
private function getSubject()
{
    // ...
}
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
            ->view("emails.{$this->lang}.auth.password_reset")
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
