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

    // intialize the PaymobService when contoller is used
    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

    // we will use the reservation id from our db , to pay
    public function pay($reservationId)
    {
        //check if reservation already created
        $reservation = Reservation::findOrFail($reservationId);
        //we say that deal with cents and need to convert from pound to cents
        $amountCents = $reservation->price * 100;


        $orderId = $this->paymob->createOrder(
            $amountCents,
            $reservation->id . '_' . now()->timestamp
        );
        $billingData = [
            ## the first name and last name are mandatory ,
            'first_name'   => auth()->user()->name,
            'last_name'    => auth()->user()->last_name ?? ' User', // i do that , cause i not have the last name
            'email'        => auth()->user()->email,
            'phone_number' => auth()->user()->phone ?? '',
            //this billing data i don't have , cause it is reservation , not delivery , but this data mandatory
            'street'       => $reservation->estate->street ?? 'Unkown Street ',
            'building'     => $reservation->estate->building_number ?? 'unknown',
            'floor'        => $reservation->estate->floor ?? 'unknown',
            'apartment'    => $reservation->estate->apartment_number ?? 'unknown',
            'city'         => $reservation->estate->city ?? 'unknown',
            'country'      => $reservation->estate->country ?? 'EG',
            'postal_code'  => $reservation->estate->postal_code ?? 'unknown',
        ];
        // generate payment key
        $paymentKey = $this->paymob->paymentKey($amountCents, $orderId, $billingData);

        // get iframe with payment_token
        $iframeUrl = $this->paymob->getPaymentIframeUrl($paymentKey);

        //the front page that i created and put the iframe there
        return view('front.payments.pay', compact('iframeUrl'));
    }


    //  Callback (Server to Server ) => tell your server the status of payment
    public function callback(Request $request)
    {

        //handlePaymentStatus is static function that defined in reservation model
        $reservation = Reservation::handlePaymentStatus(
            $request->input('merchant_order_id'),
            $request->all(),
            true
        );

        if (! $reservation) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Processed'], 200);
    }

    // Redirect (as call back , but to client )
    ## redirect to the reservation page with message
    public function response(Request $request)
    {
        $isSuccess = $request->query('success') == 'true' || $request->query('success') === true;

        //handlePaymentStatus is static function that defined in reservation model
        $reservation = Reservation::handlePaymentStatus(
            $request->query('merchant_order_id'),
            $request->all(),
            $isSuccess
        );

        if (! $reservation) {
            return redirect()->route('estates.index')
                ->with('error', 'Reservation Not Exist !');
        }

        if (! $isSuccess) {
            return redirect()->route('reservation.index')
                ->with('error', 'Paied Failed , try in another Time ');
        }

        return redirect()->route('reservation.index')
            ->with('success', 'Paied Successfully ');
    }
}
