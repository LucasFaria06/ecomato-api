-- ========================================
-- EcoMato MVP - Setup do Banco de Dados
-- MySQL (para MySQL Workbench)
-- ========================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS ecomato_db;
USE ecomato_db;

-- ========================================
-- TABELA 1: usuarios
-- ========================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir dados de teste
INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@ecomato.com.br', '$2y$10$YourHashedPasswordHere', 'admin'),
('Teste User', 'teste@ecomato.com.br', '$2y$10$YourHashedPasswordHere', 'user');

-- ========================================
-- TABELA 2: residuos
-- ========================================

CREATE TABLE residuos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_residuo VARCHAR(100) NOT NULL,
    classe VARCHAR(10) NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(20) NOT NULL,
    data_geracao DATE NOT NULL,
    tipo_destinacao VARCHAR(100) NOT NULL,
    situacao VARCHAR(50) DEFAULT 'Pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar índices
CREATE INDEX idx_residuos_usuario ON residuos(usuario_id);
CREATE INDEX idx_residuos_data ON residuos(data_geracao);

-- Inserir dados de teste
INSERT INTO residuos (usuario_id, tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao, situacao) VALUES
(1, 'Papelão', 'II-A', 850, 'kg', '2026-08-31', 'Reciclagem', 'Adequado'),
(1, 'Óleo usado', 'I', 120, 'L', '2026-08-28', 'Tratamento', 'Adequado'),
(1, 'Plástico', 'II-A', 430, 'kg', '2026-08-25', 'Reciclagem', 'Atenção');

-- ========================================
-- VERIFICAÇÕES (execute no MySQL Workbench)
-- ========================================

-- Ver todas as tabelas
-- SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ecomato_db';

-- Ver dados de usuarios
-- SELECT * FROM usuarios;

-- Ver dados de residuos
-- SELECT * FROM residuos;

-- Ver join entre tabelas
-- SELECT r.id, r.tipo_residuo, u.nome
-- FROM residuos r
-- JOIN usuarios u ON r.usuario_id = u.id;
