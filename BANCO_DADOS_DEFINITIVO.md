# 🗄️ Banco de Dados - SQL Definitivo para BD Team

**MVP SEM DOCUMENTOS**

---

## 📋 Como Usar (3 passos)

1. Abra **MySQL Workbench**
2. **File → Open SQL Script** → Selecione `DATABASE_SETUP_FINAL.sql`
3. **Query → Execute All** (Ctrl+Shift+Enter)

✅ **Pronto!** Banco criado com tabelas e dados de teste.

---

## 📊 O que será criado

### **Tabela `usuarios` — 2 registros**

| id | nome | email | senha | role |
|----|------|-------|-------|------|
| 1 | Raphael Admin | raphael@industriamodelo.com.br | hash(123456) | admin |
| 2 | Teste User | teste@industriamodelo.com.br | hash(123456) | user |

### **Tabela `residuos` — 3 registros**

| id | usuario_id | tipo_residuo | classe | quantidade | unidade | data_geracao | tipo_destinacao | situacao |
|----|----|----|----|----|----|----|----|----|
| 1 | 1 | Papelão | II-A | 850 | kg | 2026-08-31 | Reciclagem | Adequado |
| 2 | 1 | Óleo usado | I | 120 | L | 2026-08-28 | Tratamento | Adequado |
| 3 | 1 | Plástico | II-A | 430 | kg | 2026-08-25 | Reciclagem | Atenção |

---

## 🔗 Relacionamento

```
usuarios (1) ──── (N) residuos
   └─ ON DELETE CASCADE
```

---

## 📋 Campos da tabela `residuos`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | PK, auto-increment |
| usuario_id | INT | FK → usuarios(id) |
| tipo_residuo | VARCHAR(100) | Ex: Papelão, Plástico, Óleo, etc |
| classe | VARCHAR(10) | I, II-A, II-B |
| data_geracao | DATE | YYYY-MM-DD |
| quantidade | DECIMAL(10,2) | Ex: 850.50 |
| unidade | VARCHAR(20) | kg, L, t, m³ |
| forma_armazenamento | VARCHAR(100) | Tambor, Big bag, Caçamba, Caixa |
| tipo_destinacao | VARCHAR(100) | Reciclagem, Tratamento, Aterro, Incineração |
| empresa_transportadora | VARCHAR(255) | Nome da empresa |
| empresa_destinadora | VARCHAR(255) | Nome da empresa |
| comprovante_url | VARCHAR(500) | URL do arquivo |
| observacoes | TEXT | Notas livres |
| situacao | VARCHAR(50) | Adequado, Atenção, Pendente |
| created_at | TIMESTAMP | Auto |
| updated_at | TIMESTAMP | Auto |

---

## 🔐 Credenciais de Teste

```
Email: raphael@industriamodelo.com.br
Senha: 123456
Role: admin
```

---

## ✨ Características

- ✅ UTF-8 encoding (suporta português)
- ✅ Senhas já hashadas em bcrypt
- ✅ Timestamps automáticos
- ✅ Integridade referencial (FK + CASCADE)
- ✅ Índices para performance
- ✅ Dados de teste para testar imediatamente

---

## 🚀 Próximas Etapas

1. **BD Team**: Execute o SQL
2. **Lucas**: Edite `.env` com credenciais do MySQL
3. **Frontend**: Será conectado automaticamente

---

**TUDO PRONTO PARA SEGUNDA! ✅**
