# 🗄️ Banco de Dados - EcoMato MVP

**BD Team**: Vocês precisam criar apenas **2 tabelas**

---

## ✅ Passo a Passo

### 1. Criar banco de dados
```sql
CREATE DATABASE ecomato_db;
```

### 2. Conectar ao banco
```sql
\c ecomato_db
```

---

## 📋 Tabela 1: usuarios

```sql
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Inserir dados de teste:**
```sql
INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@ecomato.com.br', '123456', 'admin'),
('Teste User', 'teste@ecomato.com.br', '123456', 'user');
```

---

## 📋 Tabela 2: residuos

```sql
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

CREATE INDEX idx_residuos_usuario ON residuos(usuario_id);
CREATE INDEX idx_residuos_data ON residuos(data_geracao);
```

**Inserir dados de teste:**
```sql
INSERT INTO residuos (usuario_id, tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao, situacao) VALUES
(1, 'Papelão', 'II-A', 850, 'kg', '2026-08-31', 'Reciclagem', 'Adequado'),
(1, 'Óleo usado', 'I', 120, 'L', '2026-08-28', 'Tratamento', 'Adequado'),
(1, 'Plástico', 'II-A', 430, 'kg', '2026-08-25', 'Reciclagem', 'Atenção');
```

---

## ✅ Verificar se tudo tá certo

```sql
-- Ver tabelas
\dt

-- Ver dados de usuarios
SELECT * FROM usuarios;

-- Ver dados de residuos
SELECT * FROM residuos;

-- Ver se a FK funciona
SELECT r.id, r.tipo_residuo, u.nome 
FROM residuos r 
JOIN usuarios u ON r.usuario_id = u.id;
```

---

## 📝 Dados de Conexão

Passar para o Lucas (backend):
- **Host**: localhost (ou IP do servidor)
- **Port**: 5432 (padrão PostgreSQL)
- **Database**: ecomato_db
- **User**: postgres (ou o usuário que vocês criaram)
- **Password**: (a senha que vocês definiram)

Lucas vai colocar essas credenciais no arquivo `.env`

---

## 🎯 Resumo

| Tabela | Linhas | Campos | Status |
|--------|--------|--------|--------|
| usuarios | 2 | 6 | ✅ |
| residuos | 3 | 10 | ✅ |

**Total: 2 tabelas apenas!**

---

**Prazo**: Criar até **9 de setembro (terça)**  
**Dúvidas**: Chamar o Lucas
