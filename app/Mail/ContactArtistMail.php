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

    public function __construct($details){
        $this->details = $details;
    }

    public function build(){
        return $this->subject($this->details['subject'] ?? 'New Contact Message')
        ->from($this->details['email'], $this->details['name'])
        ->view('emails.contact-artist');
    }
}
