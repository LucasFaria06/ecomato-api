# 🌱 EcoMato - Sistema de Gestão Ambiental

**Sistema completo de gestão ambiental para indústrias mato-grossenses**

![Status](https://img.shields.io/badge/status-production%20ready-brightgreen)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)

---

## 🚀 Quick Start

### 1️⃣ Criar Banco de Dados
```bash
mysql -u root -p < DATABASE_SETUP_MYSQL.sql
```

### 2️⃣ Configurar Variáveis
```bash
cp .env.example .env
# Editar .env com suas credenciais MySQL
```

### 3️⃣ Iniciar Servidor
```bash
php -S localhost:8000
```

### 4️⃣ Acessar
```
http://localhost:8000
```

**Credenciais de teste:**
- Email: `raphael@industriamodelo.com.br`
- Senha: `123456`

---

## 📦 Estrutura

```
ecomato-api/
├── 📄 index.html              # Frontend (Rafael)
├── 📁 api/                    # Endpoints PHP
│   ├── auth.php
│   ├── dashboard.php
│   └── residuos.php
├── 📁 config/                 # Configuração
│   └── Database.php           # MySQL + PDO
├── 📁 models/                 # Lógica de dados
├── 📁 controllers/
├── 📁 middleware/
├── 📁 utils/
└── DATABASE_SETUP_MYSQL.sql
```

---

## ✨ Funcionalidades

✅ Autenticação JWT  
✅ Dashboard com KPIs  
✅ Gestão de Resíduos (CRUD)  
✅ Filtros e Buscas  
✅ Gráficos Interativos  
✅ Interface Responsiva  

---

## 🛠️ Stack

| Componente | Tecnologia |
|-----------|-----------|
| Frontend | HTML5 + JavaScript + Tailwind |
| Backend | PHP 7.4+ |
| Banco | MySQL 5.7+ |
| Auth | JWT |
| Segurança | Bcrypt, Prepared Statements |

---

## 📚 Documentação

- [CONECTAR_MYSQL.md](./CONECTAR_MYSQL.md) - Setup MySQL
- [README_SETUP.md](./README_SETUP.md) - Instalação Detalhada
- [ESTRUTURA_PROJETO.md](./ESTRUTURA_PROJETO.md) - Arquitetura
- [CHANGELOG_ATUALIZACOES.md](./CHANGELOG_ATUALIZACOES.md) - Histórico

---

## 📊 Endpoints

```
POST   /api/auth.php?action=login       Login
POST   /api/auth.php?action=logout      Logout
GET    /api/residuos.php                Listar
POST   /api/residuos.php                Criar
DELETE /api/residuos.php                Deletar
GET    /api/dashboard.php               Dashboard
```

---

**Versão 2.0.0 | MySQL Edition | 2026**
