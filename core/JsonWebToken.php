<?php

class JsonWebToken
{
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode(string $data): bool|string
    {
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        return base64_decode($data);
    }

    public function createToken(array $payload): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));

        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->secret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public function verifyToken(string $token): bool|array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->secret, true);
        $expectedSignature = $this->base64UrlEncode($signature);

        if ($expectedSignature !== $base64UrlSignature) {
            return false;
        }

        $payload = json_decode($this->base64UrlDecode($base64UrlPayload), true);

        if (isset($payload['exp']) && time() >= $payload['exp']) {
            return false;
        }

        return $payload;
    }

    public function decodeToken(string $token): array|null
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        $base64UrlPayload = $parts[1];
        return json_decode($this->base64UrlDecode($base64UrlPayload), true);
    }
}