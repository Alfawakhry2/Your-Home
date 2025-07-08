<?php

namespace App\Http\Middleware;

use Closure;
use App\Events\UserVerified;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class SendWelcomeEmailAfterVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->hasVerifiedEmail()) {
            if (is_null($user->welcome_email_sent_at)) {
                try {
                    event(new UserVerified($user));
                    $user->forceFill([
                        'welcome_email_sent_at' => now(),
                    ])->save();
                    
                    Log::info("Welcome email sent to user {$user->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send welcome email to user {$user->id}: " . $e->getMessage());
                }
            }
        }

        return $next($request);
    }
}
