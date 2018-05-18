<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailManager extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    private $pathToReport;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject, $view, $pathToReport = null)
    {

        $this->data = $data;
        $this->subject = $subject;
        $this->view = $view;
        $this->pathToReport = $pathToReport;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $queue = $this->subject($this->subject)
            ->view($this->view, [
                'data' => $this->data
            ]);

        return $queue;
    }
}
