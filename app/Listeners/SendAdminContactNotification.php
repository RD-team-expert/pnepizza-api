<?php

namespace App\Listeners;

use App\Events\ContactCreated;
use App\Mail\AdminContactNotification;
use App\Models\Contact;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAdminContactNotification
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
     * @param ContactCreated $event
     */
    public function handle(ContactCreated $event): void
    {
        Mail::to('admin@example.com')->send(new AdminContactNotification($event->contact));
    }
}
