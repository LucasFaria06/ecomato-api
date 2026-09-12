<?php

/**
 * Script de Testes Automatizados - EcoMato MVP
 *
 * Uso: php tests.php
 *
 * Testa todos os endpoints e valida as respostas
 */

class TestRunner {
    private $baseUrl = 'http://localhost:8000';
    private $token = null;
    private $testsPassed = 0;
    private $testsFailed = 0;
    private $createdResiduoId = null;

    public function run() {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         🧪 TESTES DOS ENDPOINTS - EcoMato MVP             ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        // Test 1
        $this->test('API Health Check', 'GET', '/api/test', null, null, 200);

        // Test 2
        $this->testLogin('Login com Credenciais Corretas', 'raphael@ecomato.com.br', '123456', 201);

        // Test 3
        $this->testLogin('Login com Senha Errada', 'raphael@ecomato.com.br', 'ERRADA', 401);

        // Test 4
        $this->testLogin('Login com Email Inválido', 'nao_existe@teste.com', '123456', 401);

        // Test 5
        $this->test('Listar Resíduos', 'GET', '/api/residuos', $this->token, null, 200);

        // Test 6
        $this->test('Listar Resíduos SEM Token', 'GET', '/api/residuos', null, null, 401);

        // Test 7
        $this->test('Listar com Filtro (tipo)', 'GET', '/api/residuos?tipo_residuo=Papelão', $this->token, null, 200);

        // Test 8
        $this->test('Listar com Filtro (classe)', 'GET', '/api/residuos?classe=I', $this->token, null, 200);

        // Test 9
        $this->testCreateResiduuo('Criar Novo Resíduo', $this->token, 201);

        // Test 10
        $this->testCreateResiduuo('Criar com Validação Falhando', $this->token, 400, [
            'tipo_residuo' => 'Metal',
            'classe' => 'II-A',
            'quantidade' => 500,
            'unidade' => 'kg'
        ]);

        // Test 11
        $this->testCreateResiduuo('Criar com Classe Inválida', $this->token, 400, [
            'tipo_residuo' => 'Metal',
            'classe' => 'INVALIDA',
            'quantidade' => 500,
            'unidade' => 'kg',
            'data_geracao' => '2026-09-11',
            'tipo_destinacao' => 'Reciclagem'
        ]);

        // Test 12
        if ($this->createdResiduoId) {
            $this->test("Deletar Resíduo (ID: {$this->createdResiduoId})", 'DELETE', "/api/residuos/{$this->createdResiduoId}", $this->token, null, 200);
        }

        // Test 13
        $this->test('Deletar Resíduo Inexistente', 'DELETE', '/api/residuos/999', $this->token, null, 404);

        // Test 14
        $this->test('Dashboard', 'GET', '/api/dashboard', $this->token, null, 200);

        // Test 15
        $this->test('Dashboard SEM Token', 'GET', '/api/dashboard', null, null, 401);

        // Resumo
        $this->printSummary();
    }

    private function test($name, $method, $endpoint, $token = null, $body = null, $expectedStatus = 200) {
        echo "Test: $name\n";
        echo "  └─ $method $endpoint\n";

        try {
            $response = $this->makeRequest($method, $endpoint, $token, $body);

            if ($response['status'] === $expectedStatus) {
                echo "  ✅ Status: {$response['status']} (esperado: $expectedStatus)\n";
                $this->testsPassed++;
            } else {
                echo "  ❌ Status: {$response['status']} (esperado: $expectedStatus)\n";
                echo "     Response: " . json_encode($response['data']) . "\n";
                $this->testsFailed++;
            }
        } catch (Exception $e) {
            echo "  ❌ Erro: " . $e->getMessage() . "\n";
            $this->testsFailed++;
        }

        echo "\n";
    }

    private function testLogin($name, $email, $password, $expectedStatus = 201) {
        echo "Test: $name\n";
        echo "  └─ POST /api/auth/login\n";

        try {
            $body = json_encode(['email' => $email, 'password' => $password]);
            $response = $this->makeRequest('POST', '/api/auth/login', null, $body);

            if ($response['status'] === $expectedStatus) {
                echo "  ✅ Status: {$response['status']} (esperado: $expectedStatus)\n";

                // Se login foi bem-sucedido, salvar token
                if ($expectedStatus === 201 && isset($response['data']['data']['token'])) {
                    $this->token = $response['data']['data']['token'];
                    echo "  ✅ Token obtido: " . substr($this->token, 0, 20) . "...\n";
                }

                $this->testsPassed++;
            } else {
                echo "  ❌ Status: {$response['status']} (esperado: $expectedStatus)\n";
                $this->testsFailed++;
            }
        } catch (Exception $e) {
            echo "  ❌ Erro: " . $e->getMessage() . "\n";
            $this->testsFailed++;
        }

        echo "\n";
    }

    private function testCreateResiduuo($name, $token, $expectedStatus = 201, $customData = null) {
        echo "Test: $name\n";
        echo "  └─ POST /api/residuos\n";

        $data = $customData ?? [
            'tipo_residuo' => 'Metal',
            'classe' => 'II-A',
            'quantidade' => 500,
            'unidade' => 'kg',
            'data_geracao' => '2026-09-11',
            'tipo_destinacao' => 'Reciclagem'
        ];

        try {
            $body = json_encode($data);
            $response = $this->makeRequest('POST', '/api/residuos', $token, $body);

            if ($response['status'] === $expectedStatus) {
                echo "  ✅ Status: {$response['status']} (esperado: $expectedStatus)\n";

                // Se criou com sucesso, guardar ID
                if ($expectedStatus === 201 && isset($response['data']['data']['id'])) {
                    $this->createdResiduoId = $response['data']['data']['id'];
                    echo "  ✅ Resíduo criado com ID: {$this->createdResiduoId}\n";
                }

                $this->testsPassed++;
            } else {
                echo "  ❌ Status: {$response['status']} (esperado: $expectedStatus)\n";
                $this->testsFailed++;
            }
        } catch (Exception $e) {
            echo "  ❌ Erro: " . $e->getMessage() . "\n";
            $this->testsFailed++;
        }

        echo "\n";
    }

    private function makeRequest($method, $endpoint, $token = null, $body = null) {
        $url = $this->baseUrl . $endpoint;

        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/json',
                ],
                'timeout' => 10
            ]
        ];

        if ($token) {
            $options['http']['header'][] = "Authorization: Bearer $token";
        }

        if ($body) {
            $options['http']['content'] = $body;
        }

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            throw new Exception("Request failed: " . ($error['message'] ?? 'Unknown error'));
        }

        // Extrair status code
        $status = 200;
        foreach ($http_response_header as $header) {
            if (strpos($header, 'HTTP') === 0) {
                preg_match('/HTTP\/\d+\.\d+ (\d+)/', $header, $matches);
                $status = (int)$matches[1];
                break;
            }
        }

        $data = json_decode($response, true);

        return [
            'status' => $status,
            'data' => $data
        ];
    }

    private function printSummary() {
        $total = $this->testsPassed + $this->testsFailed;
        $percentage = $total > 0 ? ($this->testsPassed / $total) * 100 : 0;

        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                    📊 RESUMO DOS TESTES                    ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "  ✅ Testes Passou: $this->testsPassed\n";
        echo "  ❌ Testes Falhou: $this->testsFailed\n";
        echo "  📊 Total: $total\n";
        echo "  📈 Percentage: " . number_format($percentage, 2) . "%\n";
        echo "\n";

        if ($this->testsFailed === 0) {
            echo "  🎉 TODOS OS TESTES PASSARAM! Backend está pronto!\n";
        } else {
            echo "  ⚠️  Alguns testes falharam. Verificar os erros acima.\n";
        }

        echo "\n";
    }
}

// Executar testes
$runner = new TestRunner();
$runner->run();
