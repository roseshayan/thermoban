<?php
namespace App\Services\Payment;

class ZibalGateway implements PaymentGatewayInterface {
    protected string $merchant;
    protected bool $sandbox;

    public function __construct(string $merchant = 'zibal', bool $sandbox = false) {
        $this->merchant = (!empty($merchant)) ? $merchant : 'zibal';
        $this->sandbox = $sandbox;
    }

    public function request(int $amountToman, string $callbackUrl, string $description, array $metadata = []): array {
        $amountRials = $amountToman * 10;

        $data = [
            'merchant' => $this->merchant,
            'amount' => $amountRials,
            'callbackUrl' => $callbackUrl,
            'description' => $description,
            'orderId' => $metadata['order_id'] ?? (string) time(),
            'mobile' => $metadata['mobile'] ?? ''
        ];

        // If in test sandbox with default merchant or empty
        if ($this->sandbox && ($this->merchant === 'zibal' || empty($this->merchant))) {
            $mockTrackId = (string) rand(10000000, 99999999);
            return [
                'success' => true,
                'redirect_url' => $callbackUrl . '?trackId=' . $mockTrackId . '&success=1&status=2&simulated=1',
                'authority' => $mockTrackId,
                'error' => null
            ];
        }

        $response = $this->sendPostRequest('https://gateway.zibal.ir/v1/request', $data);

        if (!empty($response['result']) && $response['result'] == 100) {
            $trackId = (string) $response['trackId'];
            return [
                'success' => true,
                'redirect_url' => 'https://gateway.zibal.ir/start/' . $trackId,
                'authority' => $trackId,
                'error' => null
            ];
        }

        $errMsg = $this->translateResultCode($response['result'] ?? 0);
        return [
            'success' => false,
            'redirect_url' => null,
            'authority' => null,
            'error' => $errMsg
        ];
    }

    public function verify(int $amountToman, string $authority): array {
        // If mock / simulated
        if (strlen($authority) === 8 && is_numeric($authority)) {
            return [
                'success' => true,
                'ref_id' => 'ZB-' . rand(1000000, 9999999),
                'card_pan' => '۵۸۹۲-۱۰**-****-۴۴۱۹',
                'error' => null
            ];
        }

        $data = [
            'merchant' => $this->merchant,
            'trackId' => $authority
        ];

        $response = $this->sendPostRequest('https://gateway.zibal.ir/v1/verify', $data);

        if (!empty($response['result']) && in_array($response['result'], [100, 201])) {
            return [
                'success' => true,
                'ref_id' => (string) ($response['refNumber'] ?? rand(1000000, 9999999)),
                'card_pan' => $response['cardNumber'] ?? '',
                'error' => null
            ];
        }

        $errMsg = $this->translateResultCode($response['result'] ?? 0);
        return [
            'success' => false,
            'ref_id' => null,
            'card_pan' => null,
            'error' => $errMsg
        ];
    }

    protected function translateResultCode(int $result): string {
        $messages = [
            -1 => 'در انتظار ارسال به درگاه بانکی',
            -2 => 'خطای داخلی درگاه پرداخت زیبال',
            100 => 'عملیات با موفقیت انجام شد',
            102 => 'مرچنت کد زیبال نامعتبر است',
            103 => 'درگاه غیرفعال است',
            104 => 'آدرس بازگشت (CallbackUrl) نامعتبر است',
            105 => 'مبلغ تراکنش کمتر از حداقل مجاز (۱,۰۰۰ تومان) است',
            201 => 'تراکنش قبلا تایید شده است',
            202 => 'سفارش پرداخت نشده یا لغو شده است',
            203 => 'کد پیگیری تراکنش (TrackId) نامعتبر است'
        ];
        return $messages[$result] ?? 'خطای ناشناخته در درگاه پرداخت زیبال (کد: ' . $result . ')';
    }

    protected function sendPostRequest(string $url, array $params): array {
        $jsonData = json_encode($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Zibal Rest Api v1');
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
