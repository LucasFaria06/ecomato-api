CREATE DATABASE IF NOT EXISTS ecomato_db;
USE ecomato_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@industriamodelo.com.br', '$2y$10$tnZOJndEDWQDrRLYeqmb/e6HOIJneg6Dd1fxgUHuCau/4oLAGJNRi', 'admin'),
('Teste User', 'teste@industriamodelo.com.br', '$2y$10$tnZOJndEDWQDrRLYeqmb/e6HOIJneg6Dd1fxgUHuCau/4oLAGJNRi', 'user');

CREATE TABLE IF NOT EXISTS residuos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_residuo VARCHAR(100) NOT NULL,
    classe VARCHAR(10) NOT NULL,
    data_geracao DATE NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(20) NOT NULL,
    forma_armazenamento VARCHAR(100),
    tipo_destinacao VARCHAR(100) NOT NULL,
    empresa_transportadora VARCHAR(255),
    empresa_destinadora VARCHAR(255),
    comprovante_url VARCHAR(500),
    observacoes TEXT,
    situacao VARCHAR(50) DEFAULT 'Pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_residuos_usuario ON residuos(usuario_id);
CREATE INDEX idx_residuos_data ON residuos(data_geracao);
CREATE INDEX idx_residuos_classe ON residuos(classe);

INSERT INTO residuos (usuario_id, tipo_residuo, classe, data_geracao, quantidade, unidade, forma_armazenamento, tipo_destinacao, empresa_transportadora, empresa_destinadora, situacao) VALUES
(1, 'Papelão', 'II-A', '2026-08-31', 850, 'kg', 'Big bag', 'Reciclagem', 'Transportes XYZ', 'Recicladora ABC', 'Adequado'),
(1, 'Óleo usado', 'I', '2026-08-28', 120, 'L', 'Tambor', 'Tratamento', 'Transportes XYZ', 'Tratadora DEF', 'Adequado'),
(1, 'Plástico', 'II-A', '2026-08-25', 430, 'kg', 'Caçamba', 'Reciclagem', 'Transportes XYZ', 'Recicladora GHI', 'Atenção');