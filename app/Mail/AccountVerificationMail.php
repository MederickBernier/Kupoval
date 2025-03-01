<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

/**
 * Class AccountVerificationMail
 *
 * This class is responsible for sending account verification emails to users.
 * It extends the Mailable class and implements the ShouldQueue interface to allow queuing of the email.
 *
 * @package App\Mail
 *
 * @property User $user The user to whom the verification email is sent.
 * @property string $verificationUrl The signed verification URL.
 * @property string $fullName The full name of the user.
 * @property string $lang The language locale for the email.
 *
 * @method __construct(User $user) Initializes the mail instance with the user and sets the language and full name.
 * @method build() Builds the email message, including generating the signed verification URL and setting the email subject and view.
 * @method getSubject() Returns the email subject based on the language locale.
 */
class AccountVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;
    public $fullName;
    public $lang;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->lang = app()->getLocale();

        $this->fullName = trim("{$user->profile->title} {$user->profile->first_name} {$user->profile->last_name}");
        $this->fullName =  $this->fullName ?: $user->username;
    }

    public function build()
    {
        $this->verificationUrl = URL::signedRoute(
            'verification.verify',
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)],
            Carbon::now()->addMinutes(60)
        );

        Log::info('🔍 Sending Account Verification Email:', [
            'User ID' => $this->user->id,
            'Email' => $this->user->email,
            'Verification URL' => $this->verificationUrl,
            'Language' => $this->lang
        ]);

        return $this->subject($this->getSubject())
            ->view("emails.{$this->lang}.auth.account_verification")
            ->with([
                'name' => $this->fullName,
                'verificationUrl' => $this->verificationUrl,
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => 'Vérifiez votre compte - ' . config('app.name'),
            default => 'Verify your account - ' . config('app.name'),
        };
    }
}
