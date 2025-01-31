<?php

namespace App\Listeners;

use App\Events\AcquisitionCreated;
use App\Mail\AdminAcquisitionMail;
use App\Mail\UserAcquisitionMail;
use App\Models\Acquisition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAcquisitionNotification
{
    public $acquisition;

    /**
     * Create a new event instance.
     * @param Acquisition $acquisition
     */
    public function __construct(Acquisition $acquisition)
    {
        $this->acquisition = $acquisition;
    }


    /**
     * Handle the event.
     * @param AcquisitionCreated $event
     */
    public function handle(AcquisitionCreated $event): void
    {
        $acquisition = $event->acquisition;

        // Send email to the user who created the acquisition
        Mail::to($acquisition->email)->send(new UserAcquisitionMail($event->acquisition));

        // Send email to the admin
        Mail::to(config('mail.admin_email'))->send(new AdminAcquisitionMail($event->acquisition));
    }
}
