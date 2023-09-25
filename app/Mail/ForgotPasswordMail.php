<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public $token, public $url)
    {
    }
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset hasła',
        );
    }

    public function content(): Content
    {
        $view = 'emails.forgot_password';
            
        return new Content(
            view: $view,
        );
    }
}
