-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS ecomato_db;
USE ecomato_db;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user', 'gestor') DEFAULT 'user',
    ativo BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de resíduos
CREATE TABLE IF NOT EXISTS residuos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_residuo VARCHAR(255) NOT NULL,
    classe ENUM('I', 'II-A', 'II-B') NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade ENUM('kg', 'L', 't', 'm³') NOT NULL,
    data_geracao DATE NOT NULL,
    tipo_destinacao VARCHAR(255) NOT NULL,
    situacao ENUM('Adequado', 'Inadequado', 'Pendente', 'Atenção') DEFAULT 'Pendente',
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX (usuario_id),
    INDEX (data_geracao),
    INDEX (classe)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de documentos
CREATE TABLE IF NOT EXISTS documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    tipo ENUM('Licença', 'Certificado', 'Relatório', 'Comprovante', 'Outro') NOT NULL,
    descricao TEXT,
    arquivo_url VARCHAR(512),
    status ENUM('Pendente', 'Aprovado', 'Rejeitado', 'Em Análise') DEFAULT 'Pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX (usuario_id),
    INDEX (tipo),
    INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de indicadores
CREATE TABLE IF NOT EXISTS indicadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_indicador VARCHAR(255) NOT NULL,
    meta DECIMAL(10, 2) NOT NULL,
    valor_atual DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    status ENUM('Atingida', 'Em Progresso', 'Não Atingida') DEFAULT 'Em Progresso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX (usuario_id),
    INDEX (data),
    INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir usuários de teste
INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@industriamodelo.com.br', '$2y$10$E9aufZQ8Q5SaW7KpyJ5XRuY7h7MvVLrKvKAJ9yJBBJBqnqU.8w9vK', 'admin'),
('Teste User', 'teste@industriamodelo.com.br', '$2y$10$E9aufZQ8Q5SaW7KpyJ5XRuY7h7MvVLrKvKAJ9yJBBJBqnqU.8w9vK', 'user');

-- Inserir resíduos de teste
INSERT INTO residuos (usuario_id, tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao, situacao) VALUES
(1, 'Papelão', 'II-A', 850, 'kg', '2026-08-31', 'Reciclagem', 'Adequado'),
(1, 'Óleo usado', 'I', 120, 'L', '2026-08-28', 'Tratamento', 'Adequado'),
(1, 'Plástico', 'II-A', 430, 'kg', '2026-08-25', 'Reciclagem', 'Atenção');

-- Inserir documentos de teste
INSERT INTO documentos (usuario_id, titulo, tipo, descricao, status) VALUES
(1, 'Licença Ambiental', 'Licença', 'Licença de operação vigente', 'Aprovado'),
(1, 'Certificado ISO 14001', 'Certificado', 'Certificação de gestão ambiental', 'Aprovado');

-- Inserir indicadores de teste
INSERT INTO indicadores (usuario_id, nome_indicador, meta, valor_atual, unidade, data, status) VALUES
(1, 'Redução de Resíduos', 100, 85, 'kg/mês', '2026-09-21', 'Em Progresso'),
(1, 'Destinação Adequada', 100, 95, '%', '2026-09-21', 'Atingida');

-- Nota: A senha dos usuários de teste é: 123456
-- Hash gerado com: password_hash('123456', PASSWORD_BCRYPT)
