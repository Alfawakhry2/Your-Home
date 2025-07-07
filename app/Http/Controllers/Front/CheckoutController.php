<?php

namespace App\Http\Controllers\front;

use Carbon\Carbon;
use App\Models\Estate;
use App\Models\Reservation;
use App\Repo\Cart\CartModel;
use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

    public function checkout($id)
    {
        $estate = Estate::findOrFail($id);
        if ($estate->status !== 'available') {
            return redirect()->back()->with('error', 'This Estate Not Available');
        }
        return view('front.checkout', compact('estate'));
    }

    public function store(Request $request, $id)
    {

        $estate = Estate::findOrFail($id);
        if ($estate->status !== 'available') {
            return redirect()->route('estates.index')
                ->with('error', 'This Estate Already Reserved');
        }

        $rules = [];
        if ($estate->type == 'rent') {
            $rules = [
                'start_date' => 'required|date|after_or_equal:today',
                'duration'   => 'required|numeric|min:1|max:12',
            ];
        }
        $request->validate($rules);

        $startDate =  $estate->type === 'rent' ? Carbon::parse($request->start_date) : null;
        $endDate = $startDate ? $startDate->copy()->addMonths( (int) $request->duration) : null;
        $reservation = Reservation::create([
            'user_id'    => Auth::id(),
            'estate_id'  => $estate->id,
            'date'       => now(),
            'start_date' => $startDate,
            'end_date'   =>$endDate,
            'price' => $estate->price,
            'status'     => 'pending',
        ]);

        $estate->status = $estate->type === 'rent' ? 'rented' : 'sold';
        $estate->save();


        $cart = new CartModel();
        $cart->delete($reservation->id);

        return redirect()->route('paymob.pay', $reservation->id);
    }
}
