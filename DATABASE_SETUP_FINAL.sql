-- ========================================
-- EcoMato MVP - SQL Definitivo
-- MySQL / MySQL Workbench
-- Baseado no Frontend do Rafael
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
-- Campos esperados pelo frontend do Rafael:
-- - tipo_residuo (select: Papelão, Plástico, Óleo usado, Metal, etc)
-- - classe (select: I, II-A, II-B)
-- - data_geracao (date picker)
-- - quantidade (number)
-- - unidade (select: kg, L, t, m³)
-- - forma_armazenamento (select: Tambor, Big bag, Caçamba, Caixa)
-- - tipo_destinacao (select: Reciclagem, Tratamento, Aterro, Incineração)
-- - empresa_transportadora (text input)
-- - empresa_destinadora (text input)
-- - comprovante (file upload - armazenar URL)
-- - observacoes (textarea)

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

-- Dados de teste (3 resíduos)
INSERT INTO residuos (usuario_id, tipo_residuo, classe, data_geracao, quantidade, unidade, forma_armazenamento, tipo_destinacao, empresa_transportadora, empresa_destinadora, situacao) VALUES
(1, 'Papelão', 'II-A', '2026-08-31', 850, 'kg', 'Big bag', 'Reciclagem', 'Transportes XYZ', 'Recicladora ABC', 'Adequado'),
(1, 'Óleo usado', 'I', '2026-08-28', 120, 'L', 'Tambor', 'Tratamento', 'Transportes XYZ', 'Tratadora DEF', 'Adequado'),
(1, 'Plástico', 'II-A', '2026-08-25', 430, 'kg', 'Caçamba', 'Reciclagem', 'Transportes XYZ', 'Recicladora GHI', 'Atenção');

-- ========================================
-- CAMPOS ESPERADOS PELOS ENDPOINTS
-- ========================================

-- POST /api/auth.php?action=login
-- Body: { email, senha }
-- Response: { token, user: { id, nome, email, role } }

-- GET /api/residuos.php
-- Headers: Authorization: Bearer [token]
-- Response: [{ id, usuario_id, tipo_residuo, classe, ... }]

-- POST /api/residuos.php
-- Headers: Authorization: Bearer [token]
-- Body: { tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao,
--         forma_armazenamento, empresa_transportadora, empresa_destinadora, observacoes, comprovante }
-- Response: { id, usuario_id, ... }

-- GET /api/dashboard.php
-- Headers: Authorization: Bearer [token]
-- Response: { residuos_gerados_kg, destinacao_adequada_percent, total_registros, consumo_agua_m3 }

-- ========================================
-- VERIFICAÇÕES (Execute após inserir dados)
-- ========================================

-- Ver estrutura da tabela usuarios
-- DESCRIBE usuarios;

-- Ver estrutura da tabela residuos
-- DESCRIBE residuos;

-- Ver todos os usuários
-- SELECT * FROM usuarios;

-- Ver todos os resíduos
-- SELECT * FROM residuos;

-- Ver resíduos com nome do usuário
-- SELECT r.*, u.nome as usuario_nome
-- FROM residuos r
-- JOIN usuarios u ON r.usuario_id = u.id;

-- Contar resíduos por situação
-- SELECT situacao, COUNT(*) as total FROM residuos GROUP BY situacao;

-- ========================================
-- OPÇÕES DE TIPOS NO FRONTEND
-- ========================================

-- Tipos de Resíduo: Papelão, Plástico, Óleo usado, Metal, Vidro, Madeira, etc.
-- Classe: I (Perigoso), II-A (Não inerte), II-B (Inerte)
-- Unidades: kg, L, t, m³
-- Forma de Armazenamento: Tambor, Big bag, Caçamba, Caixa
-- Tipo de Destinação: Reciclagem, Tratamento, Aterro sanitário, Incineração
-- Situação: Adequado, Atenção, Pendente

-- ========================================
-- CREDENCIAIS DE TESTE
-- ========================================

-- Admin
-- Email: raphael@industriamodelo.com.br
-- Senha: 123456
-- Role: admin

-- Usuário
-- Email: teste@industriamodelo.com.br
-- Senha: 123456
-- Role: user
