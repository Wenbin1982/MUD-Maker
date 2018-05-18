<?php

namespace App\Jobs;

use App\Mail\MailManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data, $pathToReport, $subject, $view;



    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data['email'])->queue(new MailManager($this->data, $this->subject, $this->view, $this->pathToReport));
    }
}
