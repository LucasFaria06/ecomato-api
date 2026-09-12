<?php

// Carrega variáveis de ambiente
if (file_exists(__DIR__ . '/.env')) {
    $envFile = file_get_contents(__DIR__ . '/.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Autoload de classes
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/config/',
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/middleware/',
        __DIR__ . '/utils/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// CORS
CorsMiddleware::handle();

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/ecomato-api', '', $uri);

// Rotas
try {
    if ($uri === '/api/test') {
        Response::success(['message' => 'API rodando!'], 'API funcionando corretamente');
    }
    // Auth routes
    elseif ($uri === '/api/auth/login' && $method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        Response::error('AuthController não implementado ainda', 501);
    }
    // Residuos routes
    elseif ($uri === '/api/residuos' && $method === 'GET') {
        Response::error('ResiduosController não implementado ainda', 501);
    }
    elseif ($uri === '/api/residuos' && $method === 'POST') {
        Response::error('ResiduosController não implementado ainda', 501);
    }
    // Documentos routes
    elseif ($uri === '/api/documentos' && $method === 'GET') {
        Response::error('DocumentosController não implementado ainda', 501);
    }
    elseif ($uri === '/api/documentos' && $method === 'POST') {
        Response::error('DocumentosController não implementado ainda', 501);
    }
    // Dashboard/Indicadores
    elseif ($uri === '/api/dashboard' && $method === 'GET') {
        Response::error('IndicadoresController não implementado ainda', 501);
    }
    else {
        Response::notFound('Rota não encontrada: ' . $uri);
    }
} catch (Exception $e) {
    Response::error('Erro interno: ' . $e->getMessage(), 500);
}
