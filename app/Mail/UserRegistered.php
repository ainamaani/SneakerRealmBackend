<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $account_number;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($user, $account_number)
    {
        $this->user = $user;
        $this->account_number = $account_number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Our Application')
                    ->view('emails.user_registered');
    }
}







