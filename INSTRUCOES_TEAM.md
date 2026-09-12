# 📋 Instruções para o Time - EcoMato MVP

**Prazo: 14 de setembro de 2026**

---

## 👥 Distribuição de Tarefas

### **1️⃣ BD Team (2 pessoas)**

**O que fazer:**
- [ ] Criar banco de dados PostgreSQL
- [ ] Executar arquivo `DATABASE_SETUP.sql`
- [ ] Verificar se as 2 tabelas foram criadas
- [ ] Inserir dados de teste
- [ ] Passar credenciais para Lucas (Host, Port, Database, User, Password)

**Arquivos:**
- `DATABASE_SETUP.sql` - SQL pronto para executar
- `BANCO_DADOS.md` - Instruções detalhadas

**Checklist BD:**
```sql
-- Verificar se tá tudo certo
SELECT * FROM usuarios;
-- Deve retornar 2 usuários

SELECT COUNT(*) FROM residuos;
-- Deve retornar 3

SELECT r.*, u.nome FROM residuos r 
JOIN usuarios u ON r.usuario_id = u.id;
-- Deve retornar 3 resíduos com nome do usuário
```

**Prazo:** Até **9 de setembro (terça)**

---

### **2️⃣ Lucas (Backend PHP)**

**O que fazer:**
- [ ] Editar `.env` com credenciais do BD (quando BD team terminar)
- [ ] Testar `/api/test` (se conecta no PHP server)
- [ ] Testar `/api/auth/login` (se conecta no BD)
- [ ] Testar `/api/residuos` (autenticação + BD)
- [ ] Testar `/api/dashboard` (agregações)
- [ ] Fazer push final no GitHub

**Teste no Postman:**
```
1. POST /api/auth/login
   Body: { "email": "raphael@ecomato.com.br", "password": "123456" }
   
2. Copiar token
   
3. GET /api/residuos
   Header: Authorization: Bearer [TOKEN]
   
4. POST /api/residuos
   Header: Authorization: Bearer [TOKEN]
   Body: { tipo_residuo, classe, quantidade, unidade, data_geracao, tipo_destinacao }
   
5. DELETE /api/residuos/{id}
   Header: Authorization: Bearer [TOKEN]
   
6. GET /api/dashboard
   Header: Authorization: Bearer [TOKEN]
```

**Arquivos:**
- `PROJETO_MVP.md` - Plano completo
- `BANCO_DADOS.md` - Credenciais do BD

**Rodar backend:**
```bash
cd /home/usuario/ecomato-api
php -S localhost:8000
```

**Prazo:** Até **13 de setembro (sexta)**

---

### **3️⃣ Frontend Team (2 pessoas)**

**O que fazer:**
- [ ] Integrar login (pegar token e guardar no localStorage)
- [ ] Integrar listar resíduos (usar token no header)
- [ ] Integrar criar resíduo (POST com validação)
- [ ] Integrar deletar resíduo (DELETE)
- [ ] Integrar dashboard (carregar KPIs)
- [ ] Testar fluxo completo (login → dashboard → criar → listar → deletar)

**Base URL:** `http://localhost:8000`

**Exemplo React:**
```javascript
const handleLogin = async (email, password) => {
  const response = await fetch('http://localhost:8000/api/auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  if (data.success) {
    localStorage.setItem('token', data.data.token);
    // Redirecionar para dashboard
  }
};
```

**Arquivos:**
- `FRONTEND_INTEGRATION.md` - Guia completo com exemplos

**Prazo:** Até **13 de setembro (sexta)**

---

## 📊 Checklist de Dependências

```
BD Team começa:
├── Cria tabelas (até 9/09)
│
├─→ Lucas recebe credenciais
│   ├── Edita .env
│   ├── Testa endpoints (até 13/09)
│   └── Push no GitHub
│
├─→ Frontend team recebe URL do backend
    ├── Integra requisições (até 13/09)
    └── Testa fluxo completo
```

---

## 🎯 Dados de Teste

**Usuários:**
- Email: `raphael@ecomato.com.br` | Senha: `123456` | Role: admin
- Email: `teste@ecomato.com.br` | Senha: `123456` | Role: user

**Resíduos (já inseridos):**
1. Papelão 850kg - Reciclagem - Adequado
2. Óleo usado 120L - Tratamento - Adequado
3. Plástico 430kg - Reciclagem - Atenção

---

## 📁 Arquivos Importantes

| Arquivo | Para quem | O que é |
|---------|-----------|--------|
| `DATABASE_SETUP.sql` | BD Team | SQL pronto para executar |
| `BANCO_DADOS.md` | BD Team | Instruções de como rodar |
| `PROJETO_MVP.md` | Todos | Visão geral do projeto |
| `FRONTEND_INTEGRATION.md` | Frontend | Como fazer requisições |
| `.env` | Lucas | Credenciais (editar quando BD tiver pronto) |
| `index.php` | Lucas | Router dos endpoints |
| `controllers/` | Lucas | Lógica dos endpoints |
| `models/` | Lucas | Acesso ao BD |

---

## 🔗 Repositório GitHub

```
https://github.com/LucasFaria06/ecomato-api
```

**Clonar (cada pessoa do time):**
```bash
git clone https://github.com/LucasFaria06/ecomato-api.git
cd ecomato-api
```

---

## ✅ Resumo do Fluxo

```
1. BD Team setup (até 9/09)
   └─ Cria: usuarios, residuos
   └─ Insere: 2 usuários, 3 resíduos
   └─ Passa credenciais para Lucas

2. Lucas setup (até 13/09)
   └─ Edita .env
   └─ Testa endpoints no Postman
   └─ Push no GitHub

3. Frontend integração (até 13/09)
   └─ Login (pega token)
   └─ Listar resíduos (com token)
   └─ Criar resíduo (POST com token)
   └─ Deletar resíduo (DELETE com token)
   └─ Dashboard (GET com token)

4. Teste final (13-14/09)
   └─ Login → Dashboard → Criar → Listar → Deletar
   └─ Verificar todos os erros
   └─ Apresentação (14/09)
```

---

## 🚨 Problemas e Soluções

### BD Team

**"Erro: database does not exist"**
→ Executar: `CREATE DATABASE ecomato_db;`

**"Erro: relation "usuarios" does not exist"**
→ Executar o arquivo `DATABASE_SETUP.sql` completo

**"Erro: duplicate key value violates unique constraint"**
→ Email já existe. Mudar para outro email nos INSERTs

### Lucas

**"Erro: Connect to localhost refused"**
→ BD não está rodando. Pedir para BD team ligar PostgreSQL.

**"Erro: SQLSTATE[08006]"**
→ Credenciais do `.env` estão erradas. Verificar com BD team.

### Frontend

**"CORS error: No 'Access-Control-Allow-Origin' header"**
→ Backend está configurado. Se der, reiniciar: `php -S localhost:8000`

**"401 Token inválido"**
→ Token expirou (24h) ou não foi passado no header.

---

## 📞 Comunicação

- **BD Team** ↔️ **Lucas**: Credenciais do BD
- **Lucas** ↔️ **Frontend**: URL base do backend
- **Todos** ↔️ **GitHub**: Push de código

---

## 🎯 Meta

**Até 14 de setembro:**
- ✅ Login funcionando
- ✅ Listar resíduos funcionando
- ✅ Criar resíduo funcionando
- ✅ Deletar resíduo funcionando
- ✅ Dashboard com KPIs
- ✅ Banco de dados integrado
- ✅ Frontend conectado ao backend
- ✅ Código explicável e comentado

---

**Versão:** MVP Simplificado (2 tabelas, 5 endpoints)  
**Última atualização:** 11 de setembro de 2026  
**Responsável:** Lucas Augusto (Backend)
