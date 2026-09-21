# 🔗 Conectar Frontend com MySQL

O frontend foi **atualizado para usar MySQL** ao invés de JSON mock!

## ✅ O que foi feito

1. **Database.php** - Substituído por versão com PDO MySQL
2. **.env** - Configurado para MySQL (porta 3306)
3. **DATABASE_SETUP_MYSQL.sql** - Script SQL fornecido

## 🚀 Como Conectar (3 passos rápidos)

### Passo 1: Criar o Banco de Dados

```bash
mysql -u root -p < DATABASE_SETUP_MYSQL.sql
```

Ou manualmente no MySQL:
```bash
mysql -u root -p
```

```sql
CREATE DATABASE IF NOT EXISTS ecomato_db;
-- Depois execute o conteúdo de DATABASE_SETUP_MYSQL.sql
```

### Passo 2: Editar o `.env` (se necessário)

Abra `/home/usuario/ecomato-api/.env`:

```env
DB_HOST=localhost        # Seu host MySQL
DB_PORT=3306            # Porta padrão MySQL
DB_NAME=ecomato_db      # Nome do banco
DB_USER=root            # Seu usuário MySQL
DB_PASSWORD=            # Sua senha (vazio se sem senha)
```

### Passo 3: Iniciar o Servidor

```bash
cd /home/usuario/ecomato-api
php -S localhost:8000
```

Abra no navegador: **http://localhost:8000**

## 🧪 Testar a Conexão

### 1. Abrir o Frontend
```
http://localhost:8000
```

### 2. Fazer Login
```
Email: raphael@industriamodelo.com.br
Senha: 123456
```

### 3. Se logar com sucesso = ✅ Tudo funcionando!

## 📊 O que funciona agora

| Funcionalidade | Status |
|---|---|
| Login | ✅ Conecta com MySQL |
| Dashboard | ✅ Mostra dados do banco |
| Listar Resíduos | ✅ Lê do MySQL |
| Filtrar Resíduos | ✅ Funciona |
| Criar Resíduo | ✅ Salva no MySQL |
| Deletar Resíduo | ✅ Deleta do MySQL |

## 🔧 Se Receber Erro de Conexão

### Erro: "Erro de conexão com banco"
```
Solução:
1. Verifique se MySQL está rodando
2. Verifique credenciais em .env
3. Verifique se banco foi criado: mysql -u root -p -e "SHOW DATABASES;"
```

### Erro: "Class 'PDO' not found"
```
Solução:
1. Verifique PHP com PDO MySQL: php -m | grep PDO
2. Instale extensão se necessário
```

### Erro ao logar
```
Solução:
1. Banco foi criado com DATABASE_SETUP_MYSQL.sql? 
2. Verificar se tabelas existem: mysql -u root -p -e "USE ecomato_db; SHOW TABLES;"
3. Verificar usuários: mysql -u root -p -e "USE ecomato_db; SELECT * FROM usuarios;"
```

## 📁 Estrutura do Projeto

```
/home/usuario/ecomato-api/
├── index.html              # Frontend (limpado)
├── api/
│   ├── auth.php           # Autenticação (conecta com MySQL)
│   ├── dashboard.php      # Dashboard (conecta com MySQL)
│   └── residuos.php       # Resíduos (conecta com MySQL)
├── config/
│   └── Database.php       # ✅ ATUALIZADO PARA MYSQL
├── models/
│   ├── User.php
│   └── Residuo.php
├── utils/
│   ├── JWT.php
│   ├── Response.php
│   └── Validator.php
├── middleware/
│   ├── AuthMiddleware.php
│   └── CorsMiddleware.php
├── .env                   # ✅ CONFIGURADO PARA MYSQL
└── DATABASE_SETUP_MYSQL.sql  # ✅ NOVO SCRIPT SQL
```

## 💾 Dados de Teste

**Usuários criados automaticamente:**

```
Admin:
  Email: raphael@industriamodelo.com.br
  Senha: 123456
  Role: admin

User:
  Email: teste@industriamodelo.com.br
  Senha: 123456
  Role: user
```

**Dados pré-carregados:**
- 3 resíduos de exemplo
- 2 documentos de exemplo

## 🎯 Próximos Passos

1. ✅ Criar banco de dados
2. ✅ Editar .env (se necessário)
3. ✅ Iniciar servidor PHP
4. ✅ Testar no navegador
5. Explorar funcionalidades (CRUD de resíduos)

## 📝 Notas Importantes

- Senha padrão de teste: `123456`
- Charset: UTF-8 MB4
- Timezone: UTC
- CORS: Habilitado para localhost
- JWT: Token válido por 24 horas

---

**Status: ✅ PRONTO PARA USAR COM MYSQL**

Qualquer dúvida, verifique os logs do PHP ou o console do navegador (F12).
