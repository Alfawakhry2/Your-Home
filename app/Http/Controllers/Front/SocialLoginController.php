<?php

namespace App\Http\Controllers\front;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Get user data from Google (after click on google icon)
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Try to find user by google_id (if exist , this is already registered => direct login)
            $user = User::where('google_id', $googleUser->getId())->first();

            // If not found by google_id (may register without social login ) then => check by email
            if (! $user) {
                $user = User::where('email', $googleUser->getEmail())->first();
                //if user return , update google_id with GoogleId to make it login direct when press on social icon
                if ($user) {
                    // update google_id to existing account
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name'              => $googleUser->getName(),
                        'email'             => $googleUser->getEmail(),
                        'google_id'         => $googleUser->getId(),
                        'email_verified_at' => now(),
                        'password'          => bcrypt(uniqid()), // random password (if log with social , the password comes hashed and should change password)
                    ]);

                    $user->assignRole('buyer');
                }
            }

            // Login the user
            Auth::login($user);

            // Redirect to home or any page
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to login with Google.');
        }
    }



    //facebook

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {

        // لو رجع فيه خطأ من Facebook (زي المستخدم رفض)
        if ($request->has('error') || $request->has('error_code')) {
            return redirect('/login')->with('error', 'Error while login with Facebook.');
        }

        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Error while login with Facebook.');
        }


        $user = User::where('email', $facebookUser->getEmail())->first();

        if ($user) {
            if (!$user->facebook_id) {
                $user->update([
                    'facebook_id' => $facebookUser->getId(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'facebook_id' => $facebookUser->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt(uniqid()),
                'type' => 'buyer'
            ]);

            $user->assignRole('buyer');
        }


        Auth::login($user);

        return redirect()->route('home');
    }
}
