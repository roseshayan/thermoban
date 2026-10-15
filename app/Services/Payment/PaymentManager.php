<?php
namespace App\Services\Payment;

use App\Models\Setting;

class PaymentManager {
    /**
     * Resolve and return active payment gateway instance
     */
    public static function resolve(?string $gatewayName = null): PaymentGatewayInterface {
        $driver = $gatewayName ?: Setting::get('gateway_default', 'zarinpal');

        if ($driver === 'zibal') {
            $merchant = Setting::get('gateway_zibal_merchant', 'zibal') ?: 'zibal';
            $sandbox = Setting::get('gateway_zibal_sandbox', '1') === '1';
            return new ZibalGateway($merchant, $sandbox);
        }

        // Default ZarinPal
        $merchant = Setting::get('gateway_zarinpal_merchant', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
        $sandbox = Setting::get('gateway_zarinpal_sandbox', '1') === '1';
        return new ZarinPalGateway($merchant, $sandbox);
    }

    /**
     * Get list of supported gateways and their current status
     */
    public static function getGatewaysList(): array {
        return [
            'zarinpal' => [
                'name' => 'درگاه پرداخت اینترنتی زرین‌پال (ZarinPal PGv4)',
                'merchant' => Setting::get('gateway_zarinpal_merchant', ''),
                'sandbox' => Setting::get('gateway_zarinpal_sandbox', '1') === '1',
                'is_default' => Setting::get('gateway_default', 'zarinpal') === 'zarinpal'
            ],
            'zibal' => [
                'name' => 'درگاه پرداخت زیبال (Zibal v1)',
                'merchant' => Setting::get('gateway_zibal_merchant', 'zibal'),
                'sandbox' => Setting::get('gateway_zibal_sandbox', '1') === '1',
                'is_default' => Setting::get('gateway_default', 'zarinpal') === 'zibal'
            ]
        ];
    }
}
