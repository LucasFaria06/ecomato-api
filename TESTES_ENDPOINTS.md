# 🧪 Testes dos Endpoints - EcoMato MVP

**Como testar todos os endpoints**

---

## 📌 Setup

1. **Rodar backend:**
```bash
cd /home/usuario/ecomato-api
php -S localhost:8000
```

2. **Abrir Postman ou Insomnia**

3. **Executar testes na ordem abaixo** ⬇️

---

## ✅ Test 1: API Health Check

**Endpoint:** 
```
GET http://localhost:8000/api/test
```

**Headers:** Nenhum

**Expected Response (200):**
```json
{
  "success": true,
  "message": "API funcionando corretamente",
  "data": {
    "message": "API rodando!"
  },
  "timestamp": "2026-09-11 15:00:00"
}
```

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 2: Login com Email/Senha Corretos

**Endpoint:**
```
POST http://localhost:8000/api/auth/login
```

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "email": "raphael@ecomato.com.br",
  "password": "123456"
}
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user": {
      "id": 1,
      "nome": "Raphael Admin",
      "email": "raphael@ecomato.com.br",
      "role": "admin"
    }
  },
  "timestamp": "2026-09-11 15:00:00"
}
```

**⚠️ IMPORTANTE:** Copiar o token para usar nos próximos testes!

**Teste:**
- [ ] Response status é 201
- [ ] `success` é true
- [ ] Token não é vazio
- [ ] User tem id, nome, email, role

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 3: Login com Senha Errada

**Endpoint:**
```
POST http://localhost:8000/api/auth/login
```

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "email": "raphael@ecomato.com.br",
  "password": "SENHA_ERRADA"
}
```

**Expected Response (401):**
```json
{
  "success": false,
  "message": "Email ou senha incorretos",
  "errors": null,
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 401
- [ ] `success` é false
- [ ] Message é "Email ou senha incorretos"

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 4: Login com Email Inválido

**Endpoint:**
```
POST http://localhost:8000/api/auth/login
```

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "email": "email_invalido@teste.com",
  "password": "123456"
}
```

**Expected Response (401):**
```json
{
  "success": false,
  "message": "Email ou senha incorretos",
  "errors": null,
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 401
- [ ] Message é "Email ou senha incorretos"

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 5: Listar Resíduos (com autenticação)

**Endpoint:**
```
GET http://localhost:8000/api/residuos
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Resíduos listados com sucesso",
  "data": [
    {
      "id": 1,
      "usuario_id": 1,
      "tipo_residuo": "Papelão",
      "classe": "II-A",
      "quantidade": "850.00",
      "unidade": "kg",
      "data_geracao": "2026-08-31",
      "tipo_destinacao": "Reciclagem",
      "situacao": "Adequado",
      "created_at": "2026-09-01 10:30:45"
    },
    {
      "id": 2,
      "usuario_id": 1,
      "tipo_residuo": "Óleo usado",
      "classe": "I",
      "quantidade": "120.00",
      "unidade": "L",
      "data_geracao": "2026-08-28",
      "tipo_destinacao": "Tratamento",
      "situacao": "Adequado",
      "created_at": "2026-09-01 10:25:10"
    },
    {
      "id": 3,
      "usuario_id": 1,
      "tipo_residuo": "Plástico",
      "classe": "II-A",
      "quantidade": "430.00",
      "unidade": "kg",
      "data_geracao": "2026-08-25",
      "tipo_destinacao": "Reciclagem",
      "situacao": "Atenção",
      "created_at": "2026-09-01 10:20:30"
    }
  ],
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 200
- [ ] `success` é true
- [ ] `data` é um array com 3 objetos
- [ ] Cada resíduo tem: id, usuario_id, tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao, situacao

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 6: Listar Resíduos SEM Token (erro autenticação)

**Endpoint:**
```
GET http://localhost:8000/api/residuos
```

**Headers:**
```
Content-Type: application/json
```

(SEM Authorization header)

**Expected Response (401):**
```json
{
  "success": false,
  "message": "Token não fornecido",
  "errors": null,
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 401
- [ ] Message é "Token não fornecido"

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 7: Listar Resíduos com Filtro (tipo)

**Endpoint:**
```
GET http://localhost:8000/api/residuos?tipo_residuo=Papelão
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Resíduos listados com sucesso",
  "data": [
    {
      "id": 1,
      "tipo_residuo": "Papelão",
      ...
    }
  ],
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 200
- [ ] `data` array tem apenas 1 resíduo
- [ ] Tipo do resíduo é "Papelão"

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 8: Listar Resíduos com Filtro (classe)

**Endpoint:**
```
GET http://localhost:8000/api/residuos?classe=I
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Resíduos listados com sucesso",
  "data": [
    {
      "id": 2,
      "classe": "I",
      "tipo_residuo": "Óleo usado",
      ...
    }
  ],
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 200
- [ ] `data` array tem apenas 1 resíduo
- [ ] Classe do resíduo é "I"

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 9: Criar Novo Resíduo

**Endpoint:**
```
POST http://localhost:8000/api/residuos
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Body:**
```json
{
  "tipo_residuo": "Metal",
  "classe": "II-A",
  "quantidade": 500,
  "unidade": "kg",
  "data_geracao": "2026-09-11",
  "tipo_destinacao": "Reciclagem"
}
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Recurso criado com sucesso",
  "data": {
    "id": 4,
    "usuario_id": 1,
    "tipo_residuo": "Metal",
    "classe": "II-A",
    "quantidade": "500.00",
    "unidade": "kg",
    "data_geracao": "2026-09-11",
    "tipo_destinacao": "Reciclagem",
    "situacao": "Pendente",
    "created_at": "2026-09-11 15:00:00"
  },
  "timestamp": "2026-09-11 15:00:00"
}
```

**⚠️ IMPORTANTE:** Anotar o ID do resíduo criado (ex: 4) para usar no teste de DELETE!

**Teste:**
- [ ] Response status é 201
- [ ] `success` é true
- [ ] `data` tem um novo ID (não é 1, 2 ou 3)
- [ ] Tipo, classe, quantidade, unidade, data, destinacao estão corretos
- [ ] Situação é "Pendente"

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 10: Criar Resíduo com Validação Falhando

**Endpoint:**
```
POST http://localhost:8000/api/residuos
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Body (falta campo obrigatório):**
```json
{
  "tipo_residuo": "Metal",
  "classe": "II-A",
  "quantidade": 500,
  "unidade": "kg"
}
```

(Faltam: data_geracao, tipo_destinacao)

**Expected Response (400):**
```json
{
  "success": false,
  "message": "Validação falhou",
  "errors": {
    "data_geracao": ["data_geracao é obrigatório"],
    "tipo_destinacao": ["tipo_destinacao é obrigatório"]
  },
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 400
- [ ] `success` é false
- [ ] `errors` contém data_geracao e tipo_destinacao

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 11: Criar Resíduo com Classe Inválida

**Endpoint:**
```
POST http://localhost:8000/api/residuos
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Body:**
```json
{
  "tipo_residuo": "Metal",
  "classe": "CLASSE_INVALIDA",
  "quantidade": 500,
  "unidade": "kg",
  "data_geracao": "2026-09-11",
  "tipo_destinacao": "Reciclagem"
}
```

**Expected Response (400):**
```json
{
  "success": false,
  "message": "Validação falhou",
  "errors": {
    "classe": ["classe deve ser um dos seguintes: I, II-A, II-B"]
  },
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 400
- [ ] Message menciona as classes válidas

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 12: Deletar Resíduo

**Endpoint:**
```
DELETE http://localhost:8000/api/residuos/4
```

(Usar o ID criado no Test 9)

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Recurso deletado com sucesso",
  "data": null,
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 200
- [ ] `success` é true
- [ ] `data` é null

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 13: Deletar Resíduo Inexistente

**Endpoint:**
```
DELETE http://localhost:8000/api/residuos/999
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Expected Response (404):**
```json
{
  "success": false,
  "message": "Recurso não encontrado",
  "errors": null,
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 404
- [ ] Message é "Recurso não encontrado"

**✅ Status:** PASS / ❌ FAIL

---

## ✅ Test 14: Dashboard (KPIs)

**Endpoint:**
```
GET http://localhost:8000/api/dashboard
```

**Headers:**
```
Authorization: Bearer [TOKEN_DO_LOGIN]
Content-Type: application/json
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Dashboard carregado com sucesso",
  "data": {
    "residuos_gerados_kg": 1400,
    "destinacao_adequada_percent": 67.50,
    "total_registros": 3,
    "consumo_agua_m3": 0
  },
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 200
- [ ] `success` é true
- [ ] Campos retornados: residuos_gerados_kg, destinacao_adequada_percent, total_registros, consumo_agua_m3
- [ ] Valores são números
- [ ] Total de registros é 3 (ou mais se criou mais)

**✅ Status:** PASS / ❌ FAIL

---

## ❌ Test 15: Dashboard SEM Token

**Endpoint:**
```
GET http://localhost:8000/api/dashboard
```

**Headers:**
```
Content-Type: application/json
```

(SEM Authorization header)

**Expected Response (401):**
```json
{
  "success": false,
  "message": "Token não fornecido",
  "errors": null,
  "timestamp": "2026-09-11 15:00:00"
}
```

**Teste:**
- [ ] Response status é 401
- [ ] Message é "Token não fornecido"

**✅ Status:** PASS / ❌ FAIL

---

## 📊 Resumo de Testes

| # | Endpoint | Método | Esperado | Status |
|---|----------|--------|----------|--------|
| 1 | `/api/test` | GET | 200 ✅ | - |
| 2 | `/api/auth/login` | POST | 201 ✅ | - |
| 3 | `/api/auth/login` | POST | 401 ❌ | - |
| 4 | `/api/auth/login` | POST | 401 ❌ | - |
| 5 | `/api/residuos` | GET | 200 ✅ | - |
| 6 | `/api/residuos` | GET | 401 ❌ | - |
| 7 | `/api/residuos?tipo=` | GET | 200 ✅ | - |
| 8 | `/api/residuos?classe=` | GET | 200 ✅ | - |
| 9 | `/api/residuos` | POST | 201 ✅ | - |
| 10 | `/api/residuos` | POST | 400 ❌ | - |
| 11 | `/api/residuos` | POST | 400 ❌ | - |
| 12 | `/api/residuos/{id}` | DELETE | 200 ✅ | - |
| 13 | `/api/residuos/{id}` | DELETE | 404 ❌ | - |
| 14 | `/api/dashboard` | GET | 200 ✅ | - |
| 15 | `/api/dashboard` | GET | 401 ❌ | - |

**Total:** 15 testes

---

## ✅ Checklist Final

- [ ] Test 1 passou
- [ ] Test 2 passou (copiar token)
- [ ] Test 3 passou
- [ ] Test 4 passou
- [ ] Test 5 passou
- [ ] Test 6 passou
- [ ] Test 7 passou
- [ ] Test 8 passou
- [ ] Test 9 passou (anotar ID)
- [ ] Test 10 passou
- [ ] Test 11 passou
- [ ] Test 12 passou (usar ID do Test 9)
- [ ] Test 13 passou
- [ ] Test 14 passou
- [ ] Test 15 passou

**Se todos passarem: ✅ BACKEND PRONTO PARA PRODUÇÃO!**

---

**Última atualização:** 11 de setembro de 2026
