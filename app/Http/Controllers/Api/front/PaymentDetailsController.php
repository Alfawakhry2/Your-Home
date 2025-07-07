<?php

namespace App\Http\Controllers\Api\front;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentDetailsController extends Controller
{
    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (empty($reservation)) {
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }
        // Optional: تأكد إن المستخدم هو صاحب الحجز
        if (Auth::id() !== $reservation->user_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'reservation_id' => $reservation->id,
            'payment_status' => $reservation->payment_status,
            'payment_details' => json_decode($reservation->payment_details, true),
        ]);
    }
}
