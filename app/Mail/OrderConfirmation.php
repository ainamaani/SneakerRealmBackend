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
    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $order, $items)
    {   
        $this->user = $user;
        $this->order = $order;
        $this->items = $items;
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
                        'items' => $this->items
                    ]);
    }
}
