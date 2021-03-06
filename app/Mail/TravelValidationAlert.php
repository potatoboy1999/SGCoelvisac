<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TravelValidationAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $link;
    public $schedule;

    public function __construct($name, $link, $schedule)
    {
        $this->name = $name;
        $this->link = $link;
        $this->schedule = $schedule;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('COELVISAC - Validacion de agenda de viaje')
                    ->markdown('emails.travel_validation_alert')
                    ->with([
                        'name'      =>$this->name,
                        'link'      =>$this->link,
                        'schedule'  =>$this->schedule
                    ]);
    }
}
