<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPostEmail;

class SendNewPostEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $incoming) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->incoming['sendTo'])->send(
            new NewPostEmail([
                'name' => $this->incoming['name'],
                'title' => $this->incoming['title']
            ])
        );
    }
}
