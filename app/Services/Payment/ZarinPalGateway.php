<?php
namespace App\Services\Payment;

class ZarinPalGateway implements PaymentGatewayInterface {
    protected string $merchantId;
    protected bool $sandbox;

    public function __construct(string $merchantId, bool $sandbox = false) {
        $this->merchantId = $merchantId;
        $this->sandbox = $sandbox;
    }

    public function request(int $amountToman, string $callbackUrl, string $description, array $metadata = []): array {
        // Convert Toman to Rial for standard ZarinPal v4
        $amountRials = $amountToman * 10;

        $baseUrl = $this->sandbox 
            ? 'https://sandbox.zarinpal.com/pg' 
            : 'https://payment.zarinpal.com/pg';

        $data = [
            'merchant_id' => $this->merchantId,
            'amount' => $amountRials,
            'callback_url' => $callbackUrl,
            'description' => $description,
            'metadata' => [
                'mobile' => $metadata['mobile'] ?? '',
                'email' => $metadata['email'] ?? ''
            ]
        ];

        // If in test/mock environment without real merchant
        if ($this->sandbox && ($this->merchantId === 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX' || empty($this->merchantId))) {
            $mockAuthority = 'A00000000000000000000000000' . rand(10000, 99999);
            return [
                'success' => true,
                'redirect_url' => $callbackUrl . '?Authority=' . $mockAuthority . '&Status=OK&simulated=1',
                'authority' => $mockAuthority,
                'error' => null
            ];
        }

        $response = $this->sendPostRequest($baseUrl . '/v4/payment/request.json', $data);

        if (!empty($response['data']['authority']) && $response['data']['code'] == 100) {
            $authority = $response['data']['authority'];
            return [
                'success' => true,
                'redirect_url' => $baseUrl . '/StartPay/' . $authority,
                'authority' => $authority,
                'error' => null
            ];
        }

        $errMsg = $response['errors']['message'] ?? 'خطا در برقراری ارتباط با درگاه زرین‌پال';
        return [
            'success' => false,
            'redirect_url' => null,
            'authority' => null,
            'error' => $errMsg
        ];
    }

    public function verify(int $amountToman, string $authority): array {
        $amountRials = $amountToman * 10;

        // If mock / simulated
        if (str_starts_with($authority, 'A00000000000000000000000000')) {
            return [
                'success' => true,
                'ref_id' => 'ZP-' . rand(100000, 999999),
                'card_pan' => '۶۰۳۷-۹۹**-****-۲۸۴۱',
                'error' => null
            ];
        }

        $baseUrl = $this->sandbox 
            ? 'https://sandbox.zarinpal.com/pg' 
            : 'https://payment.zarinpal.com/pg';

        $data = [
            'merchant_id' => $this->merchantId,
            'amount' => $amountRials,
            'authority' => $authority
        ];

        $response = $this->sendPostRequest($baseUrl . '/v4/payment/verify.json', $data);

        if (!empty($response['data']) && in_array($response['data']['code'], [100, 101])) {
            return [
                'success' => true,
                'ref_id' => (string) ($response['data']['ref_id'] ?? rand(100000, 999999)),
                'card_pan' => $response['data']['card_pan'] ?? '',
                'error' => null
            ];
        }

        $errMsg = $response['errors']['message'] ?? 'پرداخت توسط درگاه زرین‌پال تایید نشد یا لغو گردید.';
        return [
            'success' => false,
            'ref_id' => null,
            'card_pan' => null,
            'error' => $errMsg
        ];
    }

    protected function sendPostRequest(string $url, array $params): array {
        $jsonData = json_encode($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result, true) ?? [] : [];
    }
}
