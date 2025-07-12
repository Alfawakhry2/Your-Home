<?php

namespace App\Http\Controllers\Front;

use App\Models\Estate;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EstateNotification;
use Illuminate\Support\Facades\Auth;

class EstateController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }
        $locations = Estate::pluck('location');
        $categories = Category::all();

        //filters
        $estates = Estate::with('category', 'reservations')
            ->location($request->query('location'))
            ->category($request->query('category'))
            ->status($request->query('status'))
            ->type($request->query('type'))
            ->bedrooms($request->query('bedrooms'))
            ->bathrooms($request->query('bathrooms'))
            ->paginate(9);
        return view('front.estates', compact('estates', 'locations', 'categories'));
    }


    public function show($id)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }
        $estate = Estate::findOrFail($id);
        return view('front.estateDetails', compact('estate'));
    }

    public function notifyMe(Estate $estate)
    {
        $user = Auth::user();

        $exist = EstateNotification::where('user_id', $user->id)
            ->where('estate_id', $estate->id)
            ->exists();

        if ($exist) {
            return back()->with('error', 'You already requested a notification for this estate.');
        }

        EstateNotification::create([
            'user_id' => $user->id,
            'estate_id' => $estate->id,
        ]);

        return back()->with('success', 'You will be notified when the estate becomes available.');
    }
}
