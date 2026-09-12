<?php

class ResiduosController {
    private $residuo;

    public function __construct() {
        $this->residuo = new Residuo();
    }

    public function listar() {
        $user = AuthMiddleware::handle();
        $usuarioId = $user['id'];

        $filtros = [];
        if (!empty($_GET['tipo_residuo'])) {
            $filtros['tipo_residuo'] = $_GET['tipo_residuo'];
        }
        if (!empty($_GET['classe'])) {
            $filtros['classe'] = $_GET['classe'];
        }

        $residuos = $this->residuo->getAll($usuarioId, $filtros);

        Response::success($residuos, 'Resíduos listados com sucesso', 200);
    }

    public function criar() {
        $user = AuthMiddleware::handle();
        $usuarioId = $user['id'];

        $input = json_decode(file_get_contents('php://input'), true);

        // Validar dados
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

        // Criar resíduo
        $id = $this->residuo->create($usuarioId, $input);

        if (!$id) {
            Response::error('Erro ao criar resíduo', 500);
        }

        $residuoCriado = $this->residuo->getById($id, $usuarioId);

        Response::created($residuoCriado);
    }

    public function deletar($id) {
        $user = AuthMiddleware::handle();
        $usuarioId = $user['id'];

        $residuo = $this->residuo->getById($id, $usuarioId);

        if (!$residuo) {
            Response::notFound('Resíduo não encontrado');
        }

        $resultado = $this->residuo->delete($id, $usuarioId);

        if (!$resultado) {
            Response::error('Erro ao deletar resíduo', 500);
        }

        Response::deleted();
    }

    public function dashboard() {
        $user = AuthMiddleware::handle();
        $usuarioId = $user['id'];

        $stats = $this->residuo->getStats($usuarioId);

        $dados = [
            'residuos_gerados_kg' => $stats['residuos_gerados_kg'],
            'destinacao_adequada_percent' => $stats['destinacao_adequada_percent'],
            'total_registros' => $stats['total_registros'],
            'consumo_agua_m3' => 0
        ];

        Response::success($dados, 'Dashboard carregado com sucesso', 200);
    }
}
