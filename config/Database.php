<?php

class Database {
    private $dataFile;
    private $data = [];

    public function __construct() {
        $this->dataFile = __DIR__ . '/../data.json';
        $this->loadData();
    }

    private function loadData() {
        if (file_exists($this->dataFile)) {
            $this->data = json_decode(file_get_contents($this->dataFile), true);
        } else {
            $this->initializeData();
        }
    }

    private function initializeData() {
        $hashedPassword = password_hash('123456', PASSWORD_BCRYPT);

        $this->data = [
            'usuarios' => [
                ['id' => 1, 'nome' => 'Raphael Admin', 'email' => 'raphael@ecomato.com.br', 'senha' => $hashedPassword, 'role' => 'admin', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 2, 'nome' => 'Teste User', 'email' => 'teste@ecomato.com.br', 'senha' => $hashedPassword, 'role' => 'user', 'created_at' => date('Y-m-d H:i:s')]
            ],
            'residuos' => [
                ['id' => 1, 'usuario_id' => 1, 'tipo_residuo' => 'Papelão', 'classe' => 'II-A', 'quantidade' => 850, 'unidade' => 'kg', 'data_geracao' => '2026-08-31', 'tipo_destinacao' => 'Reciclagem', 'situacao' => 'Adequado', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 2, 'usuario_id' => 1, 'tipo_residuo' => 'Óleo usado', 'classe' => 'I', 'quantidade' => 120, 'unidade' => 'L', 'data_geracao' => '2026-08-28', 'tipo_destinacao' => 'Tratamento', 'situacao' => 'Adequado', 'created_at' => date('Y-m-d H:i:s')],
                ['id' => 3, 'usuario_id' => 1, 'tipo_residuo' => 'Plástico', 'classe' => 'II-A', 'quantidade' => 430, 'unidade' => 'kg', 'data_geracao' => '2026-08-25', 'tipo_destinacao' => 'Reciclagem', 'situacao' => 'Atenção', 'created_at' => date('Y-m-d H:i:s')]
            ],
            'nextIds' => ['usuarios' => 3, 'residuos' => 4]
        ];

        $this->saveData();
    }

    private function saveData() {
        file_put_contents($this->dataFile, json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function fetch($sql, $params = []) {
        return $this->executeSql($sql, $params, 'fetch');
    }

    public function fetchAll($sql, $params = []) {
        return $this->executeSql($sql, $params, 'fetchAll');
    }

    public function execute($sql, $params = []) {
        return $this->executeSql($sql, $params, 'execute');
    }

    public function query($sql, $params = []) {
        return new MockStatement($this->executeSql($sql, $params, 'fetchAll'));
    }

    private function executeSql($sql, $params = [], $type = 'fetch') {
        $sql = strtoupper($sql);

        // SELECT FROM usuarios
        if (strpos($sql, 'SELECT') === 0 && strpos($sql, 'FROM USUARIOS') > 0) {
            if (strpos($sql, 'WHERE EMAIL') > 0) {
                $email = $params[0] ?? null;
                foreach ($this->data['usuarios'] as $user) {
                    if ($user['email'] === $email) {
                        return $type === 'fetch' ? $user : [$user];
                    }
                }
                return $type === 'fetch' ? null : [];
            }
            return $this->data['usuarios'];
        }

        // SELECT FROM residuos
        if (strpos($sql, 'SELECT') === 0 && strpos($sql, 'FROM RESIDUOS') > 0) {
            $residuos = $this->data['residuos'];

            // WHERE usuario_id
            if (strpos($sql, 'WHERE USUARIO_ID') > 0) {
                $usuarioId = $params[0] ?? null;
                $residuos = array_filter($residuos, fn($r) => $r['usuario_id'] == $usuarioId);

                // Com filtro tipo
                if (strpos($sql, 'AND TIPO_RESIDUO ILIKE') > 0) {
                    $tipo = $params[1] ?? null;
                    $residuos = array_filter($residuos, fn($r) => stripos($r['tipo_residuo'], trim($tipo, '%')) !== false);
                }

                // Com filtro classe
                if (strpos($sql, 'AND CLASSE') > 0) {
                    $classe = $params[1] ?? null;
                    $residuos = array_filter($residuos, fn($r) => $r['classe'] === $classe);
                }
            }

            // WHERE id
            if (strpos($sql, 'WHERE ID') > 0 && strpos($sql, 'USUARIO_ID') > 0) {
                $id = $params[0] ?? null;
                $usuarioId = $params[1] ?? null;
                foreach ($residuos as $r) {
                    if ($r['id'] == $id && $r['usuario_id'] == $usuarioId) {
                        return $type === 'fetch' ? $r : [$r];
                    }
                }
                return $type === 'fetch' ? null : [];
            }

            if ($type === 'fetch') {
                return reset($residuos) ?: null;
            }
            return array_values($residuos);
        }

        // INSERT INTO usuarios
        if (strpos($sql, 'INSERT INTO USUARIOS') === 0) {
            $id = $this->data['nextIds']['usuarios']++;
            $user = [
                'id' => $id,
                'nome' => $params[0] ?? null,
                'email' => $params[1] ?? null,
                'senha' => $params[2] ?? null,
                'role' => $params[3] ?? 'user',
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->data['usuarios'][] = $user;
            $this->saveData();
            return 1;
        }

        // INSERT INTO residuos
        if (strpos($sql, 'INSERT INTO RESIDUOS') === 0) {
            $id = $this->data['nextIds']['residuos']++;
            $residuo = [
                'id' => $id,
                'usuario_id' => $params[0] ?? null,
                'tipo_residuo' => $params[1] ?? null,
                'classe' => $params[2] ?? null,
                'quantidade' => $params[3] ?? 0,
                'unidade' => $params[4] ?? null,
                'data_geracao' => $params[5] ?? null,
                'tipo_destinacao' => $params[6] ?? null,
                'situacao' => $params[7] ?? 'Pendente',
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->data['residuos'][] = $residuo;
            $this->saveData();
            return $id;
        }

        // DELETE FROM residuos
        if (strpos($sql, 'DELETE FROM RESIDUOS') === 0) {
            $id = $params[0] ?? null;
            $usuarioId = $params[1] ?? null;
            $initialCount = count($this->data['residuos']);
            $this->data['residuos'] = array_filter($this->data['residuos'], fn($r) => !($r['id'] == $id && $r['usuario_id'] == $usuarioId));
            $deleted = $initialCount - count($this->data['residuos']);
            if ($deleted > 0) {
                $this->saveData();
            }
            return $deleted;
        }

        // COUNT(*)
        if (strpos($sql, 'SELECT COUNT') > 0) {
            if (strpos($sql, 'FROM USUARIOS') > 0) {
                return [0 => count($this->data['usuarios'])];
            }
            if (strpos($sql, 'FROM RESIDUOS') > 0) {
                return [0 => count($this->data['residuos'])];
            }
        }

        // SUM agregações
        if (strpos($sql, 'SELECT') === 0 && strpos($sql, 'SUM') > 0) {
            $usuarioId = $params[0] ?? null;
            $residuos = array_filter($this->data['residuos'], fn($r) => $r['usuario_id'] == $usuarioId);
            $totalKg = array_sum(array_map(fn($r) => $r['unidade'] === 'kg' ? $r['quantidade'] : 0, $residuos));
            $adequados = count(array_filter($residuos, fn($r) => $r['situacao'] === 'Adequado'));

            return [
                'total_registros' => count($residuos),
                'total_kg' => $totalKg,
                'adequados' => $adequados,
                'total' => count($residuos)
            ];
        }

        return null;
    }

    public function lastInsertId() {
        return $this->data['nextIds']['residuos'] - 1;
    }

    public function getConnection() {
        return $this;
    }
}

class MockStatement {
    private $data;

    public function __construct($data) {
        $this->data = $data ?? [];
    }

    public function fetchAll() {
        return is_array($this->data) ? $this->data : [];
    }

    public function fetch() {
        return is_array($this->data) ? (reset($this->data) ?: null) : $this->data;
    }

    public function fetchColumn() {
        if (is_array($this->data) && isset($this->data[0])) {
            return reset($this->data[0]);
        }
        return $this->data[0] ?? null;
    }

    public function rowCount() {
        return is_array($this->data) ? count($this->data) : (int)$this->data;
    }
}
