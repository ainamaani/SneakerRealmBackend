<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $sneaker;
    public $sneaker_variant;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $order, $sneaker, $sneaker_variant)


    {   

        //
        $this->user = $user;
        $this->order = $order;
        $this->sneaker = $sneaker;
        $this->sneaker_variant = $sneaker_variant;
    
    }

/**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order confirmation')
                    ->view('emails.order')
                    ->with([
                        'user' => $this->user,
                        'order' => $this->order,
                        'sneaker' => $this->sneaker,
                        'sneaker_variant' => $this->sneaker_variant
                    ]);
    }
}
