<?php

namespace App\Http\Controllers\Front;

use Log;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

    public function pay($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $amountCents = $reservation->price * 100;

        $orderId = $this->paymob->createOrder(
            $amountCents,
            $reservation->id . '_' . now()->timestamp
        );
        $billingData = [
            'first_name'   => auth()->user()->name,
            'last_name'    => auth()->user()->last_name ?? ' User',
            'email'        => auth()->user()->email,
            'phone_number' => auth()->user()->phone ?? '',
            'street'       => $reservation->estate->street ?? 'Test St.',
            'building'     => $reservation->estate->building_number ?? '1',
            'floor'        => $reservation->estate->floor ?? '1',
            'apartment'    => $reservation->estate->apartment_number ?? '101',
            'city'         => $reservation->estate->city ?? 'Cairo',
            'country'      => $reservation->estate->country ?? 'EG',
            'postal_code'  => $reservation->estate->postal_code ?? '12345',
        ];
        // payment key
        $paymentKey = $this->paymob->paymentKey($amountCents, $orderId, $billingData);

        // iframe
        $iframeUrl = $this->paymob->getPaymentIframeUrl($paymentKey);

        return view('front.payments.pay', compact('iframeUrl'));
    }

    public function response(Request $request)
    {
        // البيانات بتيجي كـ query params: success, merchant_order_id, transaction_id, etc.
        $merchantOrderId = $request->query('merchant_order_id');
        [$reservationId] = explode('_', $merchantOrderId);

        $reservation = Reservation::find($reservationId);
        if (! $reservation) {
            return redirect()->route('estates.index')
                ->with('error', 'الحجز غير موجود');
        }

        // حدّد الحالة
        if ($request->query('success') == 'true' || $request->query('success') === true) {
            $reservation->status         = 'confirmed';
            $reservation->payment_status = 'paid';
        } else {
            $reservation->status         = 'pending';
            $reservation->payment_status = 'pending';
        }

        // خزن بقية التفاصيل
        $reservation->payment_details = json_encode($request->all());
        $reservation->save();

        // رجّع المستخدم لصفحة الحجز أو أي صفحة شكر
        return redirect()->route('reservation.index')
            ->with('success', 'تم الدفع بنجاح!');
    }

    // 4) Callback
    public function callback(Request $request)
    {
        // فكّر في التحقق الأمني (HMAC أو IP whitelist)
        $merchantOrderId = $request->input('merchant_order_id');
        [$reservationId]  = explode('_', $merchantOrderId);

        $reservation = Reservation::find($reservationId);
        if (! $reservation) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $reservation->status         = 'confirmed';
        $reservation->payment_status = 'paid';
        $reservation->payment_details = json_encode($request->all());
        $reservation->save();

        return response()->json(['message' => 'Processed'], 200);
    }
}
