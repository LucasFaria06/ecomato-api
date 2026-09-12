<?php

class JWT {
    private static $secret;
    private static $algorithm = 'HS256';

    public static function init() {
        self::$secret = $_ENV['JWT_SECRET'] ?? 'sua_chave_secreta';
    }

    public static function encode($payload) {
        self::init();

        $issuedAt = time();
        $expire = $issuedAt + ($_ENV['JWT_EXPIRATION'] ?? 86400);

        $payload['iat'] = $issuedAt;
        $payload['exp'] = $expire;

        $header = self::base64UrlEncode(json_encode(['alg' => self::$algorithm, 'typ' => 'JWT']));
        $payload = self::base64UrlEncode(json_encode($payload));
        $signature = self::base64UrlEncode(self::signMessage("$header.$payload"));

        return "$header.$payload.$signature";
    }

    public static function decode($token) {
        self::init();

        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        list($header, $payload, $signature) = $parts;

        $valid = hash_equals(
            self::base64UrlEncode(self::signMessage("$header.$payload")),
            $signature
        );

        if (!$valid) {
            return false;
        }

        $decoded = json_decode(self::base64UrlDecode($payload), true);

        if (isset($decoded['exp']) && time() > $decoded['exp']) {
            return false;
        }

        return $decoded;
    }

    private static function signMessage($message) {
        return hash_hmac('sha256', $message, self::$secret, true);
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 4 - (strlen($data) % 4)));
    }
}
