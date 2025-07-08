<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use App\Events\UserVerified;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
{

    public function __construct()
    {
        //
    }

     ## Verified => is predefined event , fire when user verfy email , after verfy
     ##  lister will know that event fire and will send welcome email , and edit the email_sent_at
    public function handle(Verified $event): void
    {
        $user = $event->user;

        if (is_null($user->welcome_email_sent_at)) {
            try {
                Mail::to($user->email)->send(new WelcomeEmail($user));
                $user->forceFill([
                    'welcome_email_sent_at' => now(),
                ])->save();
            } catch (\Exception $e) {
            }
        }
    }
}
