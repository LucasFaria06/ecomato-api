<?php

class AuthMiddleware {
    public static function handle($request = null) {
        $token = self::getBearerToken();

        if (!$token) {
            Response::unauthorized('Token não fornecido');
        }

        JWT::init();
        $decoded = JWT::decode($token);

        if (!$decoded) {
            Response::unauthorized('Token inválido ou expirado');
        }

        return $decoded;
    }

    private static function getBearerToken() {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $matches = [];
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
