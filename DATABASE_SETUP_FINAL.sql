-- ========================================
-- EcoMato MVP - SQL DEFINITIVO
-- MySQL / MySQL Workbench
-- Baseado no Frontend do Rafael
-- 2 Tabelas | 5 Registros de Teste
-- ========================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS ecomato_db;
USE ecomato_db;

-- ========================================
-- TABELA 1: usuarios
-- ========================================

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados de teste (2 usuários)
-- Senha: 123456 (hash bcrypt)
INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@industriamodelo.com.br', '$2y$10$tnZOJndEDWQDrRLYeqmb/e6HOIJneg6Dd1fxgUHuCau/4oLAGJNRi', 'admin'),
('Teste User', 'teste@industriamodelo.com.br', '$2y$10$tnZOJndEDWQDrRLYeqmb/e6HOIJneg6Dd1fxgUHuCau/4oLAGJNRi', 'user');

-- ========================================
-- TABELA 2: residuos
-- ========================================

CREATE TABLE IF NOT EXISTS residuos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_residuo VARCHAR(100) NOT NULL,
    classe VARCHAR(10) NOT NULL COMMENT 'I, II-A, II-B',
    data_geracao DATE NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(20) NOT NULL COMMENT 'kg, L, t, m³',
    forma_armazenamento VARCHAR(100),
    tipo_destinacao VARCHAR(100) NOT NULL COMMENT 'Reciclagem, Tratamento, Aterro, Incineração',
    empresa_transportadora VARCHAR(255),
    empresa_destinadora VARCHAR(255),
    comprovante_url VARCHAR(500),
    observacoes TEXT,
    situacao VARCHAR(50) DEFAULT 'Pendente' COMMENT 'Adequado, Atenção, Pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para performance
CREATE INDEX idx_residuos_usuario ON residuos(usuario_id);
CREATE INDEX idx_residuos_data ON residuos(data_geracao);
CREATE INDEX idx_residuos_classe ON residuos(classe);

-- Dados de teste (3 resíduos do usuário ID 1)
INSERT INTO residuos (usuario_id, tipo_residuo, classe, data_geracao, quantidade, unidade, forma_armazenamento, tipo_destinacao, empresa_transportadora, empresa_destinadora, situacao) VALUES
(1, 'Papelão', 'II-A', '2026-08-31', 850, 'kg', 'Big bag', 'Reciclagem', 'Transportes XYZ', 'Recicladora ABC', 'Adequado'),
(1, 'Óleo usado', 'I', '2026-08-28', 120, 'L', 'Tambor', 'Tratamento', 'Transportes XYZ', 'Tratadora DEF', 'Adequado'),
(1, 'Plástico', 'II-A', '2026-08-25', 430, 'kg', 'Caçamba', 'Reciclagem', 'Transportes XYZ', 'Recicladora GHI', 'Atenção');

-- ========================================
-- RESUMO DO QUE FOI CRIADO
-- ========================================
--
-- ✅ Tabela usuarios: 2 registros
--    - raphael@industriamodelo.com.br (admin)
--    - teste@industriamodelo.com.br (user)
--    Senha de teste: 123456
--
-- ✅ Tabela residuos: 3 registros
--    - 850 kg Papelão (Reciclagem - Adequado)
--    - 120 L Óleo usado (Tratamento - Adequado)
--    - 430 kg Plástico (Reciclagem - Atenção)
--
-- ✅ Relacionamento: usuarios (1) ──── (N) residuos
--    - ON DELETE CASCADE (integridade referencial)
--
-- ✅ Índices: usuario_id, data_geracao, classe
--
-- ========================================
-- ENDPOINTS ESPERADOS
-- ========================================
--
-- POST /api/auth.php?action=login
--      Body: { email, senha }
--
-- GET /api/residuos.php
--      Headers: Authorization: Bearer [token]
--
-- POST /api/residuos.php
--      Body: { tipo_residuo, classe, quantidade, unidade, data_geracao,
--              tipo_destinacao, forma_armazenamento, empresa_transportadora,
--              empresa_destinadora, observacoes, comprovante }
--
-- GET /api/dashboard.php
--      Response: { cards: { residuos_gerados_kg, destinacao_adequada_percent, consumo_agua_m3 } }
--
-- ========================================
-- VERIFICAÇÕES (Execute após inserir dados)
-- ========================================

-- Ver estrutura
-- DESCRIBE usuarios;
-- DESCRIBE residuos;

-- Ver dados
-- SELECT * FROM usuarios;
-- SELECT * FROM residuos;

-- Ver com join
-- SELECT r.*, u.nome FROM residuos r JOIN usuarios u ON r.usuario_id = u.id;
