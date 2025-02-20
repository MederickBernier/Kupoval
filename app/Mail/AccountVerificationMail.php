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

class AccountVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;
    public $fullName;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->fullName = trim("{$user->profile->title} {$user->profile->first_name} {$user->profile->last_name}");
    }

    public function build()
    {
        $this->verificationUrl = URL::signedRoute(
            'verification.verify',
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)],
            Carbon::now()->addMinutes(60)
        );

        Log::info('🔍 Debugging Email Verification:', [
            'User ID' => $this->user->id,
            'Email' => $this->user->email,
            'Verification URL' => $this->verificationUrl
        ]);

        return $this->subject(__('emails.auth.verify_subject'))
            ->view('emails.auth.account_verification')
            ->with([
                'name' => $this->fullName ?: $this->user->username,
                'verificationUrl' => $this->verificationUrl,  // Make sure it's correctly passed
            ]);
    }
}
