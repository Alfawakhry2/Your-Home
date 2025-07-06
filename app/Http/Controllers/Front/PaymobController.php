<?php

namespace App\Http\Controllers\front;

use App\Models\Estate;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\PaymobService;
use App\Http\Controllers\Controller;

class PaymobController extends Controller
{
    public function payEstate(Estate $estate)
    {
        if ($estate->status !== 'available') {
            return back()->with('error', 'Estate Not Available');
        }

        $paymob = new PaymobService();
        $token = $paymob->authenticate();
        $orderId = $paymob->createOrder($token, $estate->price, $estate->id);
        $paymentToken = $paymob->getPaymentKey($token, $estate->price, $orderId, auth()->user());
        $iframeUrl = $paymob->getIframeUrl($paymentToken);

        return redirect($iframeUrl);
    }

    public function callback(Request $request)
    {
        $data = $request->all();
        $hmac = config('services.paymob.hmac_secret');

        $calculatedHmac = hash_hmac('sha512', $this->concatenateData($data), $hmac);

        if ($data['hmac'] !== $calculatedHmac) {
            return response('Unauthorized', 401);
        }

        if ($data['obj']['success'] && $data['obj']['txn_response_code'] === 'APPROVED') {
            $estateId = $data['obj']['order']['merchant_order_id'];
            $userEmail = $data['obj']['billing_data']['email'];

            $estate = Estate::find($estateId);
            $user = \App\Models\User::where('email', $userEmail)->first();

            if ($estate && $user && $estate->status === 'available') {
                Reservation::create([
                    'user_id' => $user->id,
                    'estate_id' => $estate->id,
                    'date' => now(),
                    'status' => 'confirmed',
                    'payment_status' => 'confirmed',
                ]);

                $estate->status = $estate->type === 'buy' ? 'sold' : 'rented';
                $estate->save();
            }
        }

        return response('ok');
    }

    private function concatenateData($data)
    {
        $fields = [
            "amount_cents",
            "created_at",
            "currency",
            "error_occured",
            "has_parent_transaction",
            "id",
            "integration_id",
            "is_3d_secure",
            "is_auth",
            "is_capture",
            "is_refunded",
            "is_standalone_payment",
            "is_voided",
            "order",
            "owner",
            "pending",
            "source_data_pan",
            "source_data_sub_type",
            "source_data_type",
            "success"
        ];

        $string = '';
        foreach ($fields as $field) {
            $string .= $data['obj'][$field] ?? '';
        }

        return $string;
    }
}
