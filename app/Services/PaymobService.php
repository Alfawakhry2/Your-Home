<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
    }

    // 1. Get auth token
    public function authenticate()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);

        return $response['token'];
    }

    // 2. Create order
    public function createOrder($token, $amount, $user)
    {
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $amount * 100,
            'currency' => 'EGP',
            'items' => [],
        ]);

        return $response['id'];
    }

    // 3. Get payment key
    public function getPaymentKey($token, $amount, $orderId, $user)
    {
        $billingData = [
            "first_name" => $user->name,
            "last_name" => "user",
            "email" => $user->email,
            "phone_number" => "01000000000",
            "apartment" => "NA",
            "floor" => "NA",
            "street" => "NA",
            "building" => "NA",
            "city" => "Cairo",
            "country" => "EG",
            "state" => "Cairo",
        ];

        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => $amount * 100,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->integrationId,
        ]);

        return $response['token'];
    }

    // 4. Get iframe URL
    public function getIframeUrl($paymentToken)
    {
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";
    }
}
