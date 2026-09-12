<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Carregar classes
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Residuo.php';
require_once __DIR__ . '/../utils/JWT.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
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

$method = $_SERVER['REQUEST_METHOD'];
$residuo = new Residuo();

try {
    // Autenticar
    $user = AuthMiddleware::handle();
    $usuarioId = $user['id'];

    if ($method === 'GET') {
        $filtros = [];
        if (!empty($_GET['tipo_residuo'])) {
            $filtros['tipo_residuo'] = $_GET['tipo_residuo'];
        }
        if (!empty($_GET['classe'])) {
            $filtros['classe'] = $_GET['classe'];
        }

        $residuos = $residuo->getAll($usuarioId, $filtros);
        Response::success($residuos, 'Resíduos listados', 200);
    }

    elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validar
        if (!Validator::validate($input, [
            'tipo_residuo' => 'required',
            'classe' => 'required|in:I,II-A,II-B',
            'quantidade' => 'required|numeric',
            'unidade' => 'required|in:kg,L,t,m³',
            'data_geracao' => 'required|date',
            'tipo_destinacao' => 'required'
        ])) {
            Response::error('Validação falhou', 400, Validator::getErrors());
        }

        $id = $residuo->create($usuarioId, $input);
        if (!$id) {
            Response::error('Erro ao criar resíduo', 500);
        }

        $residuoCriado = $residuo->getById($id, $usuarioId);
        Response::created($residuoCriado);
    }

    elseif ($method === 'DELETE') {
        // Extrair ID da URL
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        preg_match('/\/(\d+)($|\?)/', $path, $matches);
        $id = $matches[1] ?? null;

        if (!$id) {
            Response::error('ID não fornecido', 400);
        }

        $res = $residuo->delete($id, $usuarioId);
        if (!$res) {
            Response::notFound('Resíduo não encontrado');
        }

        Response::deleted();
    }

    else {
        Response::error('Método não permitido', 405);
    }

} catch (Exception $e) {
    Response::error('Erro: ' . $e->getMessage(), 500);
}
