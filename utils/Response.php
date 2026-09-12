<?php

class Response {
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($data, $message = 'Sucesso', $statusCode = 200) {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], $statusCode);
    }

    public static function error($message, $statusCode = 400, $errors = null) {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s')
        ], $statusCode);
    }

    public static function unauthorized($message = 'Não autorizado') {
        self::error($message, 401);
    }

    public static function notFound($message = 'Recurso não encontrado') {
        self::error($message, 404);
    }

    public static function validated($data) {
        self::success($data, 'Dados validados com sucesso', 200);
    }

    public static function created($data) {
        self::success($data, 'Recurso criado com sucesso', 201);
    }

    public static function deleted() {
        self::success(null, 'Recurso deletado com sucesso', 200);
    }
}
