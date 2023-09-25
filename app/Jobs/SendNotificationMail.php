<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendNotificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data=$data;
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    Mail::to($this->data['sendTo'])->send(new NotificationMail(['subject'=>$this->data['subject'],'username'=>$this->data['username'],'title'=>$this->data['title']]));

    }
}
