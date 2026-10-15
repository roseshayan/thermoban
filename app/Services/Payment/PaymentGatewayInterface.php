<?php
namespace App\Services\Payment;

interface PaymentGatewayInterface {
    /**
     * Request payment token/authority from gateway
     * Returns ['success' => bool, 'redirect_url' => string, 'authority' => string, 'error' => ?string]
     */
    public function request(int $amountToman, string $callbackUrl, string $description, array $metadata = []): array;

    /**
     * Verify payment transaction with gateway
     * Returns ['success' => bool, 'ref_id' => ?string, 'card_pan' => ?string, 'error' => ?string]
     */
    public function verify(int $amountToman, string $authority): array;
}
