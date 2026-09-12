# 🗄️ Banco de Dados - Especificação Final para BD Team

**Baseado no Frontend do Rafael**

---

## 📋 Como Usar

1. **Abra MySQL Workbench**
2. **File** → **Open SQL Script**
3. **Selecione**: `/home/usuario/ecomato-api/DATABASE_SETUP_FINAL.sql`
4. **Execute**: Query → Execute All (Ctrl+Shift+Enter)
5. **Pronto!** ✅

---

## 📊 Estrutura do Banco

### Tabela 1: `usuarios`

```
┌─────────────┬───────────────────────────────────┐
│ id (PK)     │ 1, 2                              │
│ nome        │ Raphael Admin, Teste User         │
│ email       │ raphael@industriamodelo.com.br   │
│             │ teste@industriamodelo.com.br      │
│ senha       │ Hash bcrypt de "123456"           │
│ role        │ admin, user                       │
│ created_at  │ TIMESTAMP                         │
└─────────────┴───────────────────────────────────┘
```

**2 registros de teste já inclusos**

---

### Tabela 2: `residuos`

```
┌─────────────────────┬──────────────────────────────────┐
│ id (PK)             │ Auto increment                    │
│ usuario_id (FK)     │ Referência para usuarios(id)     │
│ tipo_residuo        │ Papelão, Plástico, Óleo, etc     │
│ classe              │ I, II-A, II-B                    │
│ data_geracao        │ DATE (YYYY-MM-DD)                │
│ quantidade          │ DECIMAL(10,2)                    │
│ unidade             │ kg, L, t, m³                     │
│ forma_armazenamento │ Tambor, Big bag, Caçamba, Caixa  │
│ tipo_destinacao     │ Reciclagem, Tratamento, etc      │
│ empresa_transportadora │ VARCHAR(255)                   │
│ empresa_destinadora │ VARCHAR(255)                      │
│ comprovante_url     │ VARCHAR(500) - URL do arquivo    │
│ observacoes         │ TEXT                             │
│ situacao            │ Adequado, Atenção, Pendente      │
│ created_at          │ TIMESTAMP                        │
│ updated_at          │ TIMESTAMP (auto-update)          │
└─────────────────────┴──────────────────────────────────┘
```

**3 registros de teste já inclusos**

---

## ✅ Dados de Teste Inclusos

### Usuários (2)

| Email | Senha | Role |
|-------|-------|------|
| raphael@industriamodelo.com.br | 123456 | admin |
| teste@industriamodelo.com.br | 123456 | user |

### Resíduos (3)

| tipo_residuo | classe | quantidade | unidade | data_geracao | tipo_destinacao | situacao |
|---|---|---|---|---|---|---|
| Papelão | II-A | 850 | kg | 2026-08-31 | Reciclagem | Adequado |
| Óleo usado | I | 120 | L | 2026-08-28 | Tratamento | Adequado |
| Plástico | II-A | 430 | kg | 2026-08-25 | Reciclagem | Atenção |

---

## 🔗 Relacionamentos

```
usuarios (1) ──── (N) residuos
    │id                 usuario_id
    └─ ON DELETE CASCADE
```

Quando um usuário é deletado, todos seus resíduos são deletados também.

---

## 📑 Campos Esperados pelo Frontend

O frontend do Rafael espera exatamente esses campos ao enviar dados:

**Ao criar um resíduo:**
```javascript
{
    tipo_residuo: "string",
    classe: "I|II-A|II-B",
    data_geracao: "YYYY-MM-DD",
    quantidade: number,
    unidade: "kg|L|t|m³",
    forma_armazenamento: "Tambor|Big bag|Caçamba|Caixa",
    tipo_destinacao: "Reciclagem|Tratamento|Aterro|Incineração",
    empresa_transportadora: "string",
    empresa_destinadora: "string",
    comprovante: file (opcional),
    observacoes: "string"
}
```

---

## 🔐 Índices Criados

Para performance:
- `idx_residuos_usuario` - para filtrar por usuário
- `idx_residuos_data` - para ordenar por data
- `idx_residuos_classe` - para filtrar por classe

---

## ✨ Features Inclusos

- ✅ Constraints de chave estrangeira
- ✅ ON DELETE CASCADE (integridade referencial)
- ✅ UTF-8 encoding (suporta português)
- ✅ Timestamps automáticos (created_at, updated_at)
- ✅ Senhas já hashadas em bcrypt
- ✅ Dados de teste para testar imediatamente

---

## 🚀 Próximas Etapas

1. **BD Team**: Executar `DATABASE_SETUP_FINAL.sql`
2. **Lucas**: Editar `.env` com credenciais do MySQL
3. **Frontend**: Conectar ao backend
4. **Testes**: Fazer login com raphael@industriamodelo.com.br / 123456

---

## 📞 Dúvidas?

Se algo não estiver claro, consulte o arquivo `DATABASE_SETUP_FINAL.sql` que tem comentários detalhados em cada seção.
