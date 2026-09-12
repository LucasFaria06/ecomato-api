<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Carregar classes
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/JWT.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../middleware/CorsMiddleware.php';

// Carregar .env
if (file_exists(__DIR__ . '/../.env')) {
    $envFile = file_get_contents(__DIR__ . '/../.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

CorsMiddleware::handle();

$action = $_GET['action'] ?? null;
$user = new User();

try {
    if ($action === 'login') {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        if (!$email || !$password) {
            Response::error('Email e senha são obrigatórios', 400);
        }

        $userData = $user->findByEmail($email);
        if (!$userData || !$user->verifyPassword($password, $userData['senha'])) {
            Response::error('Email ou senha incorretos', 401);
        }

        $payload = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'nome' => $userData['nome'],
            'role' => $userData['role']
        ];

        $token = JWT::encode($payload);

        Response::success([
            'token' => $token,
            'user' => [
                'id' => $userData['id'],
                'nome' => $userData['nome'],
                'email' => $userData['email'],
                'role' => $userData['role']
            ]
        ], 'Login realizado com sucesso', 201);
    }

    elseif ($action === 'logout') {
        Response::success(null, 'Logout realizado com sucesso', 200);
    }

    elseif ($action === 'check') {
        $token = null;
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $matches = [];
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            Response::error('Não autenticado', 401);
        }

        JWT::init();
        $decoded = JWT::decode($token);

        if (!$decoded) {
            Response::error('Token inválido', 401);
        }

        Response::success([
            'authenticated' => true,
            'user' => [
                'id' => $decoded['id'],
                'nome' => $decoded['nome'],
                'email' => $decoded['email'],
                'role' => $decoded['role']
            ]
        ], 'Autenticado', 200);
    }

    else {
        Response::error('Ação inválida', 400);
    }

} catch (Exception $e) {
    Response::error('Erro: ' . $e->getMessage(), 500);
}
