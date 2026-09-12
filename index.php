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
    // Test route
    if ($uri === '/api/test') {
        Response::success(['message' => 'API rodando!'], 'API funcionando corretamente');
    }

    // ========== AUTENTICAÇÃO ==========
    elseif ($uri === '/api/auth/login' && $method === 'POST') {
        $authController = new AuthController();
        $authController->login();
    }
    elseif ($uri === '/api/auth/logout' && $method === 'POST') {
        $authController = new AuthController();
        $authController->logout();
    }

    // ========== RESÍDUOS ==========
    elseif ($uri === '/api/residuos' && $method === 'GET') {
        $residuosController = new ResiduosController();
        $residuosController->listar();
    }
    elseif ($uri === '/api/residuos' && $method === 'POST') {
        $residuosController = new ResiduosController();
        $residuosController->criar();
    }
    elseif (preg_match('/^\/api\/residuos\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
        $id = $matches[1];
        $residuosController = new ResiduosController();
        $residuosController->deletar($id);
    }

    // ========== DASHBOARD ==========
    elseif ($uri === '/api/dashboard' && $method === 'GET') {
        $residuosController = new ResiduosController();
        $residuosController->dashboard();
    }

    else {
        Response::notFound('Rota não encontrada: ' . $uri);
    }
} catch (Exception $e) {
    Response::error('Erro interno: ' . $e->getMessage(), 500);
}
