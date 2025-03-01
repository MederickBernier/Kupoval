<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Class ContactAdminMail
 *
 * This class is responsible for creating and sending an email to the admin when a contact form is submitted.
 * It extends the Mailable class and uses the Queueable and SerializesModels traits.
 *
 * @property array $data The data from the contact form submission.
 * @property string $lang The current application locale.
 */
class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $lang;

    public function __construct($data)
    {
        $this->data = $data;
        $this->lang = app()->getLocale();
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->replyTo($this->data['email'])
            ->view("emails.{$this->lang}.contact.contact_admin")
            ->with([
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'messageContent' => $this->data['message'],
            ]);
    }

    private function getSubject()
    {
        return match ($this->lang) {
            'frca' => 'Nouveau message de contact de ' . $this->data['name'],
            default => 'New contact message from ' . $this->data['name'],
        };
    }
}
