<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Carregar classes
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Residuo.php';
require_once __DIR__ . '/../utils/JWT.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

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

try {
    // Autenticar
    $user = AuthMiddleware::handle();
    $usuarioId = $user['id'];

    $residuo = new Residuo();
    $stats = $residuo->getStats($usuarioId);

    $dados = [
        'residuos_gerados_kg' => $stats['residuos_gerados_kg'],
        'destinacao_adequada_percent' => $stats['destinacao_adequada_percent'],
        'total_registros' => $stats['total_registros'],
        'consumo_agua_m3' => 0
    ];

    Response::success($dados, 'Dashboard carregado', 200);

} catch (Exception $e) {
    Response::error('Erro: ' . $e->getMessage(), 500);
}
