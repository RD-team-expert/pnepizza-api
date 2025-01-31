<?php

namespace App\Listeners;

use App\Events\ContactCreated;
use App\Mail\UserContactConfirmation;
use App\Models\Contact;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserContactNotification
{
    public $contact;

    /**
     * Create a new event instance.
     * @param Contact $contact
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Handle the event.
     */
    public function handle(ContactCreated $event): void
    {
        Mail::to($event->contact->email)->send(new UserContactConfirmation($event->contact));

    }
}
