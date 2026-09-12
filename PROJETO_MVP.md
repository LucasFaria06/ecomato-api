# 📋 EcoMato MVP - Plano Completo

**Status**: Em desenvolvimento  
**Prazo**: 14 de setembro de 2026  
**Time**: 5 pessoas (Backend, Frontend, BD)

---

## 📌 Resumo do MVP

**EcoMato** é um SaaS de gestão ambiental para indústrias que permite:
- ♻️ Registrar e acompanhar resíduos gerados
- 📄 Gerenciar documentos ambientais (licenças, autorizações)
- 📊 Acompanhar indicadores de sustentabilidade (água, energia, reciclagem)
- 📈 Visualizar dashboards com KPIs principais

**Frontend**: React + Vite + Tailwind (já pronto)  
**Backend**: PHP estruturado + REST API  
**Banco de Dados**: PostgreSQL

---

## 🗄️ Banco de Dados

### Tabelas necessárias

#### 1. **usuarios**
Autenticação e controle de acesso

```
id (PK)
nome (VARCHAR)
email (VARCHAR, UNIQUE)
senha (VARCHAR, hashed)
role (VARCHAR) - valores: 'admin', 'user'
ativo (BOOLEAN)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

**Dados de teste:**
- Email: `raphael@ecomato.com.br` / Senha: `123456` (role: admin)
- Email: `teste@ecomato.com.br` / Senha: `123456` (role: user)

---

#### 2. **residuos**
Registros de resíduos gerados pela indústria

```
id (PK)
usuario_id (FK → usuarios.id)
tipo_residuo (VARCHAR) - ex: Papelão, Plástico, Óleo usado, Metal
classe (VARCHAR) - I (Perigoso), II-A (Não inerte), II-B (Inerte)
quantidade (DECIMAL)
unidade (VARCHAR) - kg, L, t, m³
data_geracao (DATE)
forma_armazenamento (VARCHAR) - Tambor, Big bag, Caçamba, Caixa
tipo_destinacao (VARCHAR) - Reciclagem, Tratamento, Aterro, Incineração
empresa_transportadora (VARCHAR)
empresa_destinadora (VARCHAR)
comprovante_url (VARCHAR) - URL do arquivo
observacoes (TEXT)
situacao (VARCHAR) - Adequado, Atenção, Pendente
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

**Índices necessários:**
- usuario_id
- data_geracao
- classe

**Dados de teste:** 3 resíduos com datas diferentes

---

#### 3. **documentos**
Licenças e autorizações ambientais

```
id (PK)
usuario_id (FK → usuarios.id)
tipo_documento (VARCHAR) - ex: Licença de Operação, Autorização ambiental
numero_documento (VARCHAR, UNIQUE)
data_emissao (DATE)
data_validade (DATE)
arquivo_url (VARCHAR)
situacao (VARCHAR) - Válido, Vence em breve, Vencido
observacoes (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

**Índices necessários:**
- usuario_id
- data_validade
- situacao

**Dados de teste:** 3 documentos (1 válido, 2 vencendo)

---

#### 4. **indicadores**
Histórico mensal de indicadores ambientais

```
id (PK)
usuario_id (FK → usuarios.id)
ano (INT)
mes (INT) - 1-12
residuos_reciclados (DECIMAL) - percentual 0-100
residuos_gerados_kg (DECIMAL)
consumo_agua_m3 (DECIMAL)
consumo_energia_kwh (DECIMAL)
dias_operacao (INT)
unidades_produzidas (DECIMAL)
observacoes (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

**Constraint:**
- UNIQUE (usuario_id, ano, mes)

**Índices necessários:**
- usuario_id
- ano, mes

**Dados de teste:** 6 meses de histórico (março a agosto 2026)

---

## 🔌 API REST - Endpoints

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

**POST** `/api/auth/logout`
- Requer: Authorization header com Bearer token
- Response: 200 com mensagem de sucesso

---

### ♻️ Resíduos

**GET** `/api/residuos`
- Requer: Autenticação
- Query params: `?periodo=&tipo=&classe=&destinacao=`
- Response: Array de resíduos do usuário

**POST** `/api/residuos`
- Requer: Autenticação
- Body: tipo_residuo, classe, quantidade, unidade, data_geracao, forma_armazenamento, tipo_destinacao, empresa_transportadora, empresa_destinadora, observacoes
- Response (201): Resíduo criado com ID

**GET** `/api/residuos/{id}`
- Requer: Autenticação
- Response: Detalhes do resíduo

**DELETE** `/api/residuos/{id}`
- Requer: Autenticação
- Response (200): Mensagem de sucesso

---

### 📄 Documentos

**GET** `/api/documentos`
- Requer: Autenticação
- Query params: `?situacao=`
- Response: Array de documentos do usuário

**POST** `/api/documentos`
- Requer: Autenticação
- Body: tipo_documento, numero_documento, data_emissao, data_validade, observacoes
- Response (201): Documento criado com ID

**GET** `/api/documentos/{id}`
- Requer: Autenticação
- Response: Detalhes do documento

**DELETE** `/api/documentos/{id}`
- Requer: Autenticação
- Response (200): Mensagem de sucesso

---

### 📊 Dashboard e Indicadores

**GET** `/api/dashboard`
- Requer: Autenticação
- Response: KPIs principais
```json
{
  "success": true,
  "data": {
    "residuos_gerados_kg": 12450,
    "destinacao_adequada_percent": 68,
    "consumo_agua_m3": 840,
    "documentos_vencendo": 3
  }
}
```

**GET** `/api/indicadores`
- Requer: Autenticação
- Query params: `?ano=2026&mes=`
- Response: Indicadores do período + metas

---

## 🏗️ Arquitetura do Backend

### Controllers a implementar

1. **AuthController.php**
   - `login($email, $password)` → gera JWT
   - `logout()` → valida logout

2. **ResiduosController.php**
   - `listar()` → GET /api/residuos
   - `criar()` → POST /api/residuos
   - `detalhes($id)` → GET /api/residuos/{id}
   - `deletar($id)` → DELETE /api/residuos/{id}

3. **DocumentosController.php**
   - `listar()` → GET /api/documentos
   - `criar()` → POST /api/documentos
   - `detalhes($id)` → GET /api/documentos/{id}
   - `deletar($id)` → DELETE /api/documentos/{id}

4. **IndicadoresController.php**
   - `dashboard()` → GET /api/dashboard
   - `indicadores()` → GET /api/indicadores

### Models a implementar

1. **User.php** - Usuário (autenticação)
2. **Residuo.php** - Resíduo
3. **Documento.php** - Documento
4. **Indicador.php** - Indicador

### Utilitários já criados

- ✅ `Database.php` - Conexão PDO
- ✅ `JWT.php` - Geração e validação de tokens
- ✅ `Response.php` - Respostas JSON padronizadas
- ✅ `Validator.php` - Validações
- ✅ `CorsMiddleware.php` - CORS
- ✅ `AuthMiddleware.php` - Autenticação

---

## 📅 Timeline de Desenvolvimento

| Dia | Task | Responsável | Status |
|-----|------|-------------|--------|
| 9 (terça) | Estrutura + BD pronto | Lucas + BD team | ✅ Estrutura criada |
| 9-10 | Autenticação JWT | Lucas | ⏳ Pendente |
| 10-11 | CRUD Resíduos | Lucas | ⏳ Pendente |
| 11-12 | CRUD Documentos | Lucas | ⏳ Pendente |
| 12-13 | Dashboard + Indicadores | Lucas | ⏳ Pendente |
| 13-14 | Testes + Deploy | Lucas | ⏳ Pendente |

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

### Usuários padrão

| Email | Senha | Role |
|-------|-------|------|
| raphael@ecomato.com.br | 123456 | admin |
| teste@ecomato.com.br | 123456 | user |

### Resíduos (3 exemplos)
1. Papelão 850kg - Reciclagem - Adequado
2. Óleo usado 120L - Tratamento - Adequado
3. Plástico 430kg - Pendente - Atenção

### Documentos (3 exemplos)
1. Licença de Operação - Vence em 12 dias
2. Autorização ambiental - Válido
3. Comprovante de destinação - Vence em breve

### Indicadores (últimos 6 meses)
- Dados mensais de março a agosto 2026
- Reciclagem: 58% → 68%
- Consumo de água: 2.8 → 2.4 m³/t

---

## ✅ Checklist para Apresentação (14/09)

- [ ] Login funcional com JWT
- [ ] Listar resíduos
- [ ] Criar novo resíduo
- [ ] Listar documentos
- [ ] Dashboard com KPIs
- [ ] Banco de dados integrado
- [ ] Frontend conectado ao backend
- [ ] CORS funcionando
- [ ] Postman/Insomnia com exemplos de requests
- [ ] Código explicável e comentado

---

## 📚 Referências

- **Frontend**: /home/usuario/Downloads (App.tsx)
- **Backend**: /home/usuario/ecomato-api
- **Documentação**: Este arquivo (PROJETO_MVP.md)

---

## 👥 Distribuição de Tarefas

| Pessoa | Tarefa |
|--------|--------|
| Lucas (você) | Backend PHP (controllers, models, endpoints) |
| Parceiro Lucas | Frontend React (já pronto, ajustes se necessário) |
| 2 pessoas | Banco de Dados (criar tabelas, dados de teste) |

---

**Última atualização**: 9 de setembro de 2026
