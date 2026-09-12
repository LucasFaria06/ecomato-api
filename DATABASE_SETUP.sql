-- ========================================
-- EcoMato MVP - Setup do Banco de Dados
-- PostgreSQL
-- ========================================

-- Criar banco de dados
CREATE DATABASE ecomato_db;

-- Conectar ao banco (no terminal: \c ecomato_db)

-- ========================================
-- TABELA 1: usuarios
-- ========================================

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir dados de teste
INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@ecomato.com.br', '123456', 'admin'),
('Teste User', 'teste@ecomato.com.br', '123456', 'user');

-- ========================================
-- TABELA 2: residuos
-- ========================================

CREATE TABLE residuos (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    tipo_residuo VARCHAR(100) NOT NULL,
    classe VARCHAR(10) NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(20) NOT NULL,
    data_geracao DATE NOT NULL,
    tipo_destinacao VARCHAR(100) NOT NULL,
    situacao VARCHAR(50) DEFAULT 'Pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar índices
CREATE INDEX idx_residuos_usuario ON residuos(usuario_id);
CREATE INDEX idx_residuos_data ON residuos(data_geracao);

-- Inserir dados de teste
INSERT INTO residuos (usuario_id, tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao, situacao) VALUES
(1, 'Papelão', 'II-A', 850, 'kg', '2026-08-31', 'Reciclagem', 'Adequado'),
(1, 'Óleo usado', 'I', 120, 'L', '2026-08-28', 'Tratamento', 'Adequado'),
(1, 'Plástico', 'II-A', 430, 'kg', '2026-08-25', 'Reciclagem', 'Atenção');

-- ========================================
-- VERIFICAÇÕES (execute para confirmar)
-- ========================================

-- Ver todas as tabelas
-- \dt

-- Ver dados de usuarios
-- SELECT * FROM usuarios;

-- Ver dados de residuos
-- SELECT * FROM residuos;

-- Ver join entre tabelas
-- SELECT r.id, r.tipo_residuo, u.nome
-- FROM residuos r
-- JOIN usuarios u ON r.usuario_id = u.id;
