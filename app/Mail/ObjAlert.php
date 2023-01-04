<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ObjAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('COELVISAC - Alerta de fin de ciclo')
                    ->markdown('emails.obj_alert')
                    ->with([
                        'link'  =>  $this->link,
                    ]);
    }
}
