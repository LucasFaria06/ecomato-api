# 📁 Estrutura do Projeto EcoMato - Frontend + API

## 🎯 Versão Atual

**Data de Organização:** 21 de setembro de 2026  
**Status:** ✅ Pronto para Produção  
**Frontend:** Rafael (Limpeza e Otimização)  
**Backend:** Lucas (PHP + MySQL)

---

## 📂 Estrutura Completa

```
/home/usuario/ecomato-api/
│
├── 📄 index.html                 ← Frontend (do Rafael) - 50KB
│   └── Conecta com /api/
│
├── 🔧 index.php                  ← Router principal (antigo)
│
├── 📁 api/                        ← Endpoints PHP
│   ├── auth.php                 (Login/Logout)
│   ├── dashboard.php            (Dashboard + KPIs)
│   └── residuos.php             (CRUD Resíduos)
│
├── 📁 config/
│   └── Database.php             ← ✨ ATUALIZADO PARA MYSQL
│
├── 📁 controllers/
│   └── ResiduosController.php
│
├── 📁 models/
│   ├── User.php
│   └── Residuo.php
│
├── 📁 middleware/
│   ├── AuthMiddleware.php
│   └── CorsMiddleware.php
│
├── 📁 utils/
│   ├── JWT.php
│   ├── Response.php
│   └── Validator.php
│
├── 📁 public/                    ← Pasta pública (opcional)
│   └── index.html              (Cópia do frontend)
│
├── 📁 docs/                      ← Documentação
│   ├── CONECTAR_MYSQL.md
│   ├── ESTRUTURA_PROJETO.md
│   └── README_SETUP.md
│
├── .env                          ← ✨ Configurado para MySQL
├── .env.example                  ← Template
│
├── DATABASE_SETUP_MYSQL.sql      ← Script SQL
│
└── README.md                     ← Documentação Principal

/home/usuario/projetos/ecomato-api/    ← NOVA API COMPLETA
│
├── index.php                    ← Router único (novo conceito)
├── controllers/                 ← 4 controllers (Auth, Residuos, Documentos, Indicadores)
├── models/                      ← 4 models
├── config/Database.php          ← MySQL com PDO
├── DATABASE_SETUP_MYSQL.sql
└── README_SETUP.md
```

---

## 🔄 Como Funciona a Conexão

### Frontend (index.html)
```
┌─────────────────────┐
│   index.html        │
│  (50KB - Rafael)    │
│                     │
│ Calls: api/*.php    │
└────────┬────────────┘
         │
         ↓
       AJAX
         │
         ↓
┌─────────────────────┐
│  /api/auth.php      │
│  /api/dashboard.php │
│  /api/residuos.php  │
└────────┬────────────┘
         │
         ↓
┌─────────────────────┐
│   Models + Utils    │
│  (User, Residuo)    │
└────────┬────────────┘
         │
         ↓
┌─────────────────────┐
│  Database.php       │
│  (MySQL via PDO)    │
└────────┬────────────┘
         │
         ↓
┌─────────────────────┐
│  MySQL Database     │
│  (ecomato_db)       │
└─────────────────────┘
```

---

## 🚀 Iniciar o Projeto

### Passo 1: Criar Banco de Dados
```bash
mysql -u root -p < /home/usuario/ecomato-api/DATABASE_SETUP_MYSQL.sql
```

### Passo 2: Configurar .env
```bash
cd /home/usuario/ecomato-api
# Editar .env com suas credenciais MySQL
```

### Passo 3: Iniciar Servidor
```bash
php -S localhost:8000
```

### Passo 4: Acessar no Navegador
```
http://localhost:8000
```

---

## 🔐 Credenciais de Teste

```
Email: raphael@industriamodelo.com.br
Senha: 123456
Role: admin

ou

Email: teste@industriamodelo.com.br
Senha: 123456
Role: user
```

---

## 📊 Dados Pré-carregados

**Tabela: usuarios (2 registros)**
- Raphael Admin (raphael@industriamodelo.com.br)
- Teste User (teste@industriamodelo.com.br)

**Tabela: residuos (3 registros)**
- 850 kg Papelão (Reciclagem - Adequado)
- 120 L Óleo usado (Tratamento - Adequado)
- 430 kg Plástico (Reciclagem - Atenção)

---

## 🎯 Funcionalidades Implementadas

### ✅ Autenticação
- Login com JWT
- Logout
- Validação de token

### ✅ Dashboard
- KPIs principais
- Resíduos gerados em kg
- Percentual de destinação adequada
- Total de registros

### ✅ Gestão de Resíduos
- Listar com filtros (tipo, classe)
- Criar novo
- Deletar
- Buscar/Filtrar

### ✅ Interface
- Design responsivo (Mobile + Desktop)
- Tema verde sustentável
- Ícones Lucide
- Gráficos Chart.js
- Tailwind CSS

---

## 📚 Arquivos de Documentação

| Arquivo | Descrição |
|---------|-----------|
| `README.md` | Overview do projeto |
| `README_SETUP.md` | Guia de instalação |
| `CONECTAR_MYSQL.md` | Conectar com MySQL |
| `ESTRUTURA_PROJETO.md` | Este arquivo |
| `CHANGELOG_ATUALIZACOES.md` | O que mudou |

---

## 🔧 Stack Tecnológico

**Frontend:**
- HTML5
- JavaScript (Vanilla)
- Tailwind CSS
- Chart.js (Gráficos)
- Lucide Icons

**Backend:**
- PHP 7.4+
- MySQL 5.7+
- PDO (Database abstraction)
- JWT (Autenticação)
- CORS (Cross-origin)

**Segurança:**
- Prepared statements (contra SQL injection)
- Password hashing (bcrypt)
- JWT tokens
- Validação de entrada

---

## 🐛 Troubleshooting

### Erro ao logar
```
Solução: Verifique se MySQL está rodando e se as tabelas foram criadas
```

### Erro: "Class 'PDO' not found"
```
Solução: Verifique se PHP tem extensão PDO MySQL
php -m | grep PDO
```

### Erro de CORS
```
Solução: Verifique se frontend e backend estão na mesma URL (localhost:8000)
```

---

## 📞 Suporte

Se algo não funcionar:
1. Verifique se MySQL está rodando: `mysql -u root -p`
2. Verifique as credenciais em `.env`
3. Verifique os logs do PHP: `php -S localhost:8000` (veja o output)
4. Verifique o console do navegador: F12 > Console

---

## ✨ Próximas Melhorias Recomendadas

- [ ] Implementar upload de arquivos (comprovantes)
- [ ] Adicionar histórico/auditoria
- [ ] Implementar paginação
- [ ] Adicionar relatórios em PDF
- [ ] Deploy em servidor de produção
- [ ] Implementar 2FA (autenticação dupla)
- [ ] Adicionar logs detalhados
- [ ] Criar testes automatizados

---

**Última Atualização:** 21 de setembro de 2026  
**Status:** ✅ 100% Funcional
