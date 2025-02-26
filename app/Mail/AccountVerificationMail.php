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
        // Generate signed verification URL valid for 60 minutes
        $this->verificationUrl = URL::signedRoute(
            'verification.verify',
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)],
            Carbon::now()->addMinutes(60)
        );

        // Debug log
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
