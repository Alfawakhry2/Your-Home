<?php

namespace App\Listeners;

use App\Mail\EstateAvailableMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\EstateAvailableForNotification;

class SendEstateAvailableNotificationToUsers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EstateAvailableForNotification $event): void
    {
        $estate = $event->estate;

        $notifications = $estate->notifications;

        foreach ($notifications as $notification) {
            $user = $notification->user;

            if ($user && $user->email) {
                Mail::to($user->email)->send(new EstateAvailableMail($estate , $user));
            }
        }

        // حذف الإشعارات بعد الإرسال
        // $estate->notifications()->delete();
    }
}
