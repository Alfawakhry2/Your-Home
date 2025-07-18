<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymobService
{
    protected $apiKey; // related to authentication
    protected $integrationId; // related to payment way (paypal , online card , mobile wallet)
    protected $iframeId; // the iframe you want to apppear while client add payment details
    protected $authUrl; // will contain the url to send to paymob , to get the authtoken
    protected $orderUrl; // send to paymob to request to create new order and take the id , to can create paymentkey
    protected $paymentKeyUrl; // after got the order_id , will send url to create payment_key
    protected $iframeUrl; // to used to redirect to iframe that i choosed

    public function __construct()
    {
        $this->apiKey        = config('paymob.api_key');
        $this->integrationId = config('paymob.integration_id');
        $this->iframeId      = config('paymob.iframe_id');
        $this->authUrl       = config('paymob.auth_url');
        $this->orderUrl      = config('paymob.order_url');
        $this->paymentKeyUrl = config('paymob.payment_key_url');
        $this->iframeUrl     = config('paymob.iframe_url');
    }

    public function authToken(): string
    {
        //http::post => will send auth url with api key to get the auth_token
        $response = Http::post($this->authUrl, [
            'api_key' => $this->apiKey,
        ]);

        if (! $response->successful()) {
            Log::error('Paymob authToken failed', $response->json());
            throw new Exception('Failed to authenticate with Paymob');
        }

        //if response success , return the auth_token
        return $response->json()['token'];
    }

    ##after have the auth_token , send to request new order with Reservation details
    ##here paymob used the cent (القرش بتاعنا)
    ## its take amount at first to show if the order can create or no , if less 10 not created
    public function createOrder(int $amountCents, int|string $merchantOrderId): int|string
    {

        // we make unique to ensure not duplicated
        $uniqueMerchantId = $merchantOrderId . '_' . now()->timestamp;
        $token = $this->authToken();
        // send token with data to paymob , so can create the order with these data
        $response = Http::withToken($token)
            ->post($this->orderUrl, [
                'amount_cents'      => $amountCents,
                'currency'          => 'EGP',
                'merchant_order_id' => $uniqueMerchantId,

            ]);

        // receive the date comes
        $data = $response->json();
        //we use log , to show if any error occure while create order
        Log::info('Paymob createOrder response', $data);

        if (! $response->successful() && ($data['message'] ?? '') !== 'duplicate') {
            Log::error('Paymob createOrder error', $data);
            throw new Exception('Paymob order creation failed');
        }

        // mean if response contain the oreder and its id
        ## when test , an error appear , cause return order.id , and once return id
        if (isset($data['order']['id'])) {
            return $data['order']['id'];
        }

        if (isset($data['id'])) {
            return $data['id'];
        }

        throw new Exception('Paymob order ID not found in response');
    }

    //payment key
    public function paymentKey(int $amountCents, int|string $orderId, array $billingData = []): string
    {
        $token = $this->authToken();
        $response = Http::withToken($token)
            ->post($this->paymentKeyUrl, [
                'amount_cents'   => $amountCents,
                'expiration'     => 3600,
                'order_id'       => $orderId,
                'integration_id' => $this->integrationId,
                'currency' => 'EGP',
                'billing_data'   => $billingData,
            ]);

        // dd([
        //     'status_code' => $response->status(),
        //     'body'        => $response->json(),
        // ]);
        if (! $response->successful()) {
            Log::error('Paymob paymentKey failed', $response->json());
            throw new Exception('Failed to generate payment key');
        }

        return $response->json()['token'];
    }


    public function getPaymentIframeUrl(string $paymentKey): string
    {
        return "{$this->iframeUrl}?payment_token={$paymentKey}";
    }

    //this is optional , and will use when we use webhook
    public function verifyHmac($data, $hmac)
    {
        //sort the data , as payment sort
        ksort($data);
        //build the query with data
        $queryString = http_build_query($data);
        $calculatedHmac = hash_hmac('sha512', $queryString, config('paymob.hmac_key'));
        return $hmac === $calculatedHmac;
    }
}
