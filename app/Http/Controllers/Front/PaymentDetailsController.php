<?php

namespace App\Http\Controllers\front;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentDetailsController extends Controller
{
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        $details = json_decode($reservation->payment_details, true);

        return view('front.payments.show', compact('reservation', 'details'));
    }
}
