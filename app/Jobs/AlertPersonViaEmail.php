<?php

namespace App\Jobs;

use App\Models\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class AlertPersonViaEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Newely added person.
     *
     * @var \App\Models\Person $person
     */
    public $person;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Person $person
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $person = $this->person;

        Mail::raw(
            "Hi {$person->first_name} {$person->last_name}!\n\nYour information has been captured!",
            function ($message) use ($person) {
                $message->to($person->email_address)->subject('You information has been captured!');
            }
        );
    }
}
