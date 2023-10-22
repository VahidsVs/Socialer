<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class storeLogsJob implements ShouldQueue
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
        Log::debug("Username: {$this->data["username"]} -  Action: {$this->data["action"]}");

    }
}
