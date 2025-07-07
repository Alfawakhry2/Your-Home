<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymobService
{
    protected $apiKey;
    protected $integrationId;
    protected $iframeId;
    protected $authUrl;
    protected $orderUrl;
    protected $paymentKeyUrl;
    protected $iframeUrl;

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

    // 1) جلب توكن التصديق
    public function authToken(): string
    {
        $response = Http::post($this->authUrl, [
            'api_key' => $this->apiKey,
        ]);

        if (! $response->successful()) {
            Log::error('Paymob authToken failed', $response->json());
            throw new Exception('Failed to authenticate with Paymob');
        }

        return $response->json()['token'];
    }

    // 2) إنشاء Order مع merchant_order_id فريد ومعالجة duplicate
    public function createOrder(int $amountCents, int|string $merchantOrderId): int|string
    {
        // نضيف suffix timestamp عشان يكون فريد
        $uniqueMerchantId = $merchantOrderId . '_' . now()->timestamp;

        $token = $this->authToken();
        $response = Http::withToken($token)
            ->post($this->orderUrl, [
                'amount_cents'      => $amountCents,
                'currency'          => 'EGP',
                'merchant_order_id' => $uniqueMerchantId,

            ]);

        $data = $response->json();
        Log::info('Paymob createOrder response', $data);
        // لو فشل الطلب لأي سبب غير duplicate
        if (! $response->successful() && ($data['message'] ?? '') !== 'duplicate') {
            Log::error('Paymob createOrder error', $data);
            throw new Exception('Paymob order creation failed');
        }

        // الحصول على order ID من JSON
        if (isset($data['order']['id'])) {
            return $data['order']['id'];
        }

        if (isset($data['id'])) {
            return $data['id'];
        }

        throw new Exception('Paymob order ID not found in response');
    }

    // 3) توليد payment key
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

    public function verifyHmac($data, $hmac)
    {
        ksort($data);
        $queryString = http_build_query($data);
        $calculatedHmac = hash_hmac('sha512', $queryString, config('paymob.hmac_key'));
        return $hmac === $calculatedHmac;
    }
}
