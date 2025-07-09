<?php

namespace App\Http\Controllers\Api\front;

use Carbon\Carbon;
use App\Models\Estate;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repo\Cart\CartModel;

class CheckoutController extends Controller
{
    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

    public function reserve(Request $request, $id)
    {
        $estate = Estate::findOrFail($id);

        if ($estate->status !== 'available') {
            return response()->json(['status' => false, 'message' => 'Estate is not available'], 400);
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
        $endDate = $startDate ? $startDate->copy()->addMonths((int) $request->duration) : null;

        $reservation = Reservation::create([
            'user_id'     => Auth::id(),
            'estate_id'   => $estate->id,
            'date'        => now(),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'price'       => $estate->price,
            'status'      => 'pending',
        ]);

        $estate->status = $estate->type === 'rent' ? 'rented' : 'sold';
        $estate->save();

        (new CartModel())->delete($reservation->id);

        return response()->json([
            'important' => 'To continue Payment , take reservation_id',
            'reservation_id' => $reservation->id,
            'message'       => 'Estate reserved successfully. Proceed to payment.',

        ]);
    }

    public function pay($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $amountCents = $reservation->price * 100;

        $orderId = $this->paymob->createOrder(
            $amountCents,
            $reservation->id . '_' . now()->timestamp
        );

        $user = Auth::user();
        $billingData = [
            'first_name'   => $user->name,
            'last_name'    => $user->last_name ?? 'User',
            'email'        => $user->email,
            'phone_number' => $user->phone ?? '',
            'street'       => $reservation->estate->street ?? 'Test St.',
            'building'     => $reservation->estate->building_number ?? '1',
            'floor'        => $reservation->estate->floor ?? '1',
            'apartment'    => $reservation->estate->apartment_number ?? '101',
            'city'         => $reservation->estate->city ?? 'Cairo',
            'country'      => $reservation->estate->country ?? 'EG',
            'postal_code'  => $reservation->estate->postal_code ?? '12345',
        ];

        $paymentKey = $this->paymob->paymentKey($amountCents, $orderId, $billingData);
        $iframeUrl = $this->paymob->getPaymentIframeUrl($paymentKey);

        return response()->json([
            'status' => true,
            'iframe_url' => $iframeUrl,
        ]);
    }

    public function paymentResponse(Request $request)
    {
        $merchantOrderId = $request->query('merchant_order_id');
        [$reservationId] = explode('_', $merchantOrderId);

        $reservation = Reservation::find($reservationId);
        if (! $reservation) {
            return response()->json(['status' => false, 'message' => 'Reservation not found'], 404);
        }

        if ($request->query('success') == 'true') {
            $reservation->status = 'confirmed';
            $reservation->payment_status = 'paid';
        } else {
            $reservation->status = 'pending';
            $reservation->payment_status = 'pending';
        }

        $reservation->payment_details = json_encode($request->all());
        $reservation->save();

        return response()->json(['status' => true, 'message' => 'Payment status updated']);
    }

    public function callback(Request $request)
    {
        $merchantOrderId = $request->input('merchant_order_id');
        [$reservationId] = explode('_', $merchantOrderId);

        $reservation = Reservation::find($reservationId);
        if (! $reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        $reservation->status = 'confirmed';
        $reservation->payment_status = 'paid';
        $reservation->payment_details = json_encode($request->all());
        $reservation->save();

        return response()->json(['message' => 'Callback processed']);
    }
}
