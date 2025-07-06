<?php

namespace App\Http\Controllers\front;

use App\Models\Estate;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repo\Cart\CartModel;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout($id)
    {
        $estate = Estate::findOrFail($id);
        if ($estate->status !== 'available') {
            return redirect()->back()->with('error', 'This Estate Not Available');
        }
        return view('front.checkout', compact('estate'));
    }


    public function store(Request $request, Estate $estate)
    {
        $estate = Estate::findOrFail($request->post('estate_id'));
        if ($estate->status !== 'available') {
            return redirect()->route('estates.index')->with('error', 'This Estate Already Reserved');
        }

        if ($estate->type == 'rent') {
            $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
            ]);

            $reservation = Reservation::create([
                'user_id' =>Auth::id(),
                'estate_id' => $estate->id,
                'date' => now(),
                'start_date' => $estate->type === 'rent' ? $request->start_date : null,
                'end_date' => $estate->type === 'rent' ? $request->end_date : null,
            ]);

            if($reservation){
                $estate->status = $estate->type === 'rent' ? 'rented' : 'sold';
                $estate->save();

                $cart = new CartModel();
                $cart->delete($reservation->id);
            }

            return redirect()->route('estates.index')->with('success' , 'Estate Is Reserved Successsfully');
        }
    }
}
