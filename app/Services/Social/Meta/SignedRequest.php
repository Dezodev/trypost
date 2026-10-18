<?php

declare(strict_types=1);

namespace App\Services\Social\Meta;

class SignedRequest
{
    /**
     * Decode and verify a Meta `signed_request` (deauthorize / data deletion
     * callbacks). It is signed with the secret of the app that sent it, so we
     * accept it when any configured Meta app secret validates the signature.
     *
     * @return array<string, mixed>|null The payload, or null when invalid.
     */
    public static function parse(string $signedRequest): ?array
    {
        $parts = explode('.', $signedRequest, 2);

        if (count($parts) !== 2) {
            return null;
        }

        [$encodedSignature, $encodedPayload] = $parts;

        $signature = self::decode($encodedSignature);
        $payload = json_decode((string) self::decode($encodedPayload), true);

        if ($signature === false || ! is_array($payload) || strtoupper((string) data_get($payload, 'algorithm')) !== 'HMAC-SHA256') {
            return null;
        }

        foreach (self::secrets() as $secret) {
            if (hash_equals(hash_hmac('sha256', $encodedPayload, $secret, true), $signature)) {
                return $payload;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private static function secrets(): array
    {
        return array_values(array_filter(array_map(
            fn (string $app): string => (string) config("services.{$app}.client_secret"),
            ['facebook', 'instagram', 'threads'],
        )));
    }

    private static function decode(string $value): string|false
    {
        return base64_decode(strtr($value, '-_', '+/'), true);
    }
}
