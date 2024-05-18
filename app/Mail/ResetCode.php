<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetCode extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reset_code;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($user, $reset_code)
    {
        $this->user = $user;
        $this->reset_code = $reset_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset password code')
                    ->view('emails.reset_code')
                    ->with([
                        'user' => $this->user,
                        'reset_code' => $this->reset_code
                    ]);
    }
}
