# 📋 EcoMato MVP - Plano Simplificado

**Status**: Em desenvolvimento  
**Prazo**: 14 de setembro de 2026  
**Time**: 5 pessoas (Backend, Frontend, BD)  
**Versão**: MVP Mínimo Viável (foco em essencial)

---

## 📌 Resumo do MVP

**EcoMato** é um SaaS de gestão ambiental para indústrias que permite:
- ♻️ Registrar e acompanhar resíduos gerados
- 📊 Visualizar dashboard com KPIs principais

**Frontend**: React + Vite + Tailwind (já pronto)  
**Backend**: PHP estruturado + REST API  
**Banco de Dados**: PostgreSQL (2 tabelas apenas)

---

## 🗄️ Banco de Dados (SIMPLIFICADO)

**Total: 2 tabelas apenas**

### Tabelas necessárias

#### 1. **usuarios**
Autenticação de usuários

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

**Campos:**
- `id` - ID único (auto-incremento)
- `nome` - Nome do usuário
- `email` - Email único
- `senha` - Senha (será enviada como texto, você faz hash no backend)
- `role` - 'admin' ou 'user'
- `created_at` - Data de criação

**Dados de teste (INSERT):**
```sql
INSERT INTO usuarios (nome, email, senha, role) VALUES
('Raphael Admin', 'raphael@ecomato.com.br', '123456', 'admin'),
('Teste User', 'teste@ecomato.com.br', '123456', 'user');
```

---

#### 2. **residuos**
Registros de resíduos gerados

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

**Campos:**
- `id` - ID único
- `usuario_id` - Referência ao usuário (FK)
- `tipo_residuo` - Papelão, Plástico, Óleo usado, Metal
- `classe` - I, II-A, II-B
- `quantidade` - Número com 2 casas decimais
- `unidade` - kg, L, t, m³
- `data_geracao` - Data em que foi gerado
- `tipo_destinacao` - Reciclagem, Tratamento, Aterro, Incineração
- `situacao` - Adequado, Atenção, Pendente
- `created_at` - Data de criação

**Dados de teste (INSERT):**
```sql
INSERT INTO residuos (usuario_id, tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao, situacao) VALUES
(1, 'Papelão', 'II-A', 850, 'kg', '2026-08-31', 'Reciclagem', 'Adequado'),
(1, 'Óleo usado', 'I', 120, 'L', '2026-08-28', 'Tratamento', 'Adequado'),
(1, 'Plástico', 'II-A', 430, 'kg', '2026-08-25', 'Reciclagem', 'Atenção');
```

---

## 📊 Resumo das Tabelas

| Tabela | Campos | Relacionamento | Índices |
|--------|--------|----------------|---------|
| **usuarios** | 6 | - | email (UNIQUE) |
| **residuos** | 10 | usuario_id (FK) | usuario_id, data_geracao |

---

## 🔌 API REST - Endpoints (5 apenas)

### 🔐 Autenticação

**POST** `/api/auth/login`
```json
Request:
{
  "email": "raphael@ecomato.com.br",
  "password": "123456"
}

Response (201):
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "token": "eyJhbGc...",
    "user": {
      "id": 1,
      "nome": "Raphael",
      "email": "raphael@ecomato.com.br",
      "role": "admin"
    }
  }
}
```

---

### ♻️ Resíduos

**GET** `/api/residuos`
- Requer: Autenticação (Bearer token)
- Query params: `?tipo=&classe=` (opcional, para filtro)
- Response: 
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "tipo_residuo": "Papelão",
      "classe": "II-A",
      "quantidade": 850,
      "unidade": "kg",
      "data_geracao": "2026-08-31",
      "tipo_destinacao": "Reciclagem",
      "situacao": "Adequado",
      "created_at": "2026-09-01"
    }
  ]
}
```

**POST** `/api/residuos`
- Requer: Autenticação
- Body:
```json
{
  "tipo_residuo": "Papelão",
  "classe": "II-A",
  "quantidade": 850,
  "unidade": "kg",
  "data_geracao": "2026-08-31",
  "tipo_destinacao": "Reciclagem"
}
```
- Response (201): Resíduo criado com ID

**DELETE** `/api/residuos/{id}`
- Requer: Autenticação
- Response (200): `{ "success": true, "message": "Resíduo deletado com sucesso" }`

---

### 📊 Dashboard

**GET** `/api/dashboard`
- Requer: Autenticação
- Response: KPIs principais (4 cards)
```json
{
  "success": true,
  "data": {
    "residuos_gerados_kg": 1400,
    "destinacao_adequada_percent": 67,
    "consumo_agua_m3": 0,
    "total_residuos_registrados": 3
  }
}
```

---

## 🏗️ Arquitetura do Backend (SIMPLIFICADA)

### Controllers a implementar (2 apenas)

1. **AuthController.php**
   - `login($email, $password)` → gera JWT e retorna token

2. **ResiduosController.php**
   - `listar()` → GET /api/residuos (com filtros opcionais)
   - `criar()` → POST /api/residuos
   - `deletar($id)` → DELETE /api/residuos/{id}
   - `dashboard()` → GET /api/dashboard (KPIs)

### Models a implementar (2 apenas)

1. **User.php** - Usuário (autenticação)
2. **Residuo.php** - Resíduo

### Utilitários já criados

- ✅ `Database.php` - Conexão PDO
- ✅ `JWT.php` - Geração e validação de tokens
- ✅ `Response.php` - Respostas JSON padronizadas
- ✅ `Validator.php` - Validações
- ✅ `CorsMiddleware.php` - CORS
- ✅ `AuthMiddleware.php` - Autenticação

---

## 📅 Timeline de Desenvolvimento (6 dias)

| Dia | Task | Horas | Status |
|-----|------|-------|--------|
| **9-10** | Autenticação JWT (login) | 2h | ⏳ Pendente |
| **10-11** | CRUD Resíduos (listar, criar, deletar) | 2h | ⏳ Pendente |
| **11-12** | Dashboard (KPIs) | 1.5h | ⏳ Pendente |
| **12-14** | Testes + Deploy + Ajustes | 2h | ⏳ Pendente |
| **Paralelo** | BD criando 2 tabelas | - | ⏳ Pendente |

**Total Backend: ~7.5 horas de trabalho**

---

## 🚀 Como Rodar o Projeto

### Setup Inicial

```bash
# 1. Entrar na pasta
cd /home/usuario/ecomato-api

# 2. Criar/configurar .env
# DB_HOST, DB_NAME, DB_USER, DB_PASSWORD (coordenar com BD team)

# 3. Rodar PHP server
php -S localhost:8000

# 4. Testar
curl http://localhost:8000/api/test
```

### Testar endpoints

Use **Insomnia** ou **Postman**:

```
1. POST /api/auth/login
   Body: { "email": "raphael@ecomato.com.br", "password": "123456" }
   Copiar token do response

2. GET /api/residuos
   Header: Authorization: Bearer [token]

3. POST /api/residuos
   Header: Authorization: Bearer [token]
   Body: { ... dados ... }
```

---

## 📊 Dados de Teste

### Usuários (2 contas)
```
Email: raphael@ecomato.com.br | Senha: 123456 | Role: admin
Email: teste@ecomato.com.br | Senha: 123456 | Role: user
```

### Resíduos (3 registros de exemplo)
```
1. Papelão | 850 kg | Reciclagem | Adequado
2. Óleo usado | 120 L | Tratamento | Adequado
3. Plástico | 430 kg | Reciclagem | Atenção
```

---

## ✅ Checklist para Apresentação (14/09)

- [ ] Login funcional com JWT
- [ ] Listar resíduos (com filtros básicos)
- [ ] Criar novo resíduo
- [ ] Deletar resíduo
- [ ] Dashboard com 4 KPIs
- [ ] Banco de dados integrado (2 tabelas)
- [ ] Frontend conectado ao backend
- [ ] CORS funcionando
- [ ] Postman/Insomnia com exemplos
- [ ] Código estruturado e fácil de explicar

---

## 📚 Referências

- **Frontend**: /home/usuario/Downloads (App.tsx)
- **Backend**: /home/usuario/ecomato-api
- **Documentação**: Este arquivo (PROJETO_MVP.md)

---

## 👥 Distribuição de Tarefas

| Pessoa | Tarefa | Deadline |
|--------|--------|----------|
| **Lucas (você)** | Backend PHP - 2 Controllers + 2 Models + 5 Endpoints | 13/09 |
| **Parceiro Lucas** | Frontend React - Ajustes finais (já pronto) | 13/09 |
| **2 pessoas BD** | Criar 2 tabelas (usuarios, residuos) + dados teste | 9/09 |

---

## 🎯 O que NÃO faz parte do MVP

❌ Documentos (remover do plano original)  
❌ Indicadores complexos (remover do plano original)  
❌ Gráficos (simplificar para cards simples)  
❌ Configurações (deixar para Fase 2)  
❌ Relatórios (deixar para Fase 2)  

---

**Última atualização**: 11 de setembro de 2026  
**Versão**: MVP Simplificado (Essencial apenas)
