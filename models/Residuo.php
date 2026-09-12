<?php

class Residuo {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($usuarioId, $filtros = []) {
        $sql = "SELECT * FROM residuos WHERE usuario_id = ?";
        $params = [$usuarioId];

        if (!empty($filtros['tipo_residuo'])) {
            $sql .= " AND tipo_residuo ILIKE ?";
            $params[] = '%' . $filtros['tipo_residuo'] . '%';
        }

        if (!empty($filtros['classe'])) {
            $sql .= " AND classe = ?";
            $params[] = $filtros['classe'];
        }

        $sql .= " ORDER BY data_geracao DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function create($usuarioId, $data) {
        $sql = "INSERT INTO residuos (
            usuario_id, tipo_residuo, classe, quantidade, unidade,
            data_geracao, tipo_destinacao, situacao
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $usuarioId,
            $data['tipo_residuo'],
            $data['classe'],
            $data['quantidade'],
            $data['unidade'],
            $data['data_geracao'],
            $data['tipo_destinacao'],
            $data['situacao'] ?? 'Pendente'
        ];

        $result = $this->db->execute($sql, $params);

        if ($result > 0) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function delete($id, $usuarioId) {
        $sql = "DELETE FROM residuos WHERE id = ? AND usuario_id = ?";
        $result = $this->db->execute($sql, [$id, $usuarioId]);
        return $result > 0;
    }

    public function getStats($usuarioId) {
        $stats = $this->db->fetch(
            "SELECT
                COUNT(*) as total_registros,
                SUM(CASE WHEN unidade = 'kg' THEN quantidade ELSE 0 END) as total_kg,
                SUM(CASE WHEN situacao = 'Adequado' THEN 1 ELSE 0 END) as adequados,
                COUNT(*) as total
            FROM residuos WHERE usuario_id = ?",
            [$usuarioId]
        );

        $total_adequado = (int)$stats['adequados'];
        $total = (int)$stats['total'];
        $percentual_adequado = $total > 0 ? round(($total_adequado / $total) * 100, 2) : 0;

        return [
            'residuos_gerados_kg' => $stats['total_kg'] ? (float)$stats['total_kg'] : 0,
            'destinacao_adequada_percent' => $percentual_adequado,
            'total_registros' => $stats['total_registros']
        ];
    }

    public function getById($id, $usuarioId) {
        return $this->db->fetch(
            "SELECT * FROM residuos WHERE id = ? AND usuario_id = ?",
            [$id, $usuarioId]
        );
    }
}
