<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactArtistMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $lang;

    public function __construct($details)
    {
        $this->details = $details;
        $this->lang = app()->getLocale();
    }

    public function build() {
        return $this->subject($this->getSubject())
        ->from($this->details['email'], $this->details['name'])
        ->view("emails.{$this->lang}.contact.contact-artist")
        ->with([
            'name' => $this->details['name'],
            'email' => $this->details['email'],
            'subject' => $this->details['subject'] ?? $this->getFallbackSubject(),
            'messageContent' => $this->details['message'],
        ]);
    }

    private function getSubject()
    {
        return $this->details['subject'] ?? $this->getFallbackSubject();
    }

    private function getFallbackSubject()
    {
        return match ($this->lang) {
            'frca' => 'Nouveau message de contact',
            default => 'New contact message'
        };
    }
}
