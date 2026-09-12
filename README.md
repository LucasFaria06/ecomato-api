# EcoMato - Backend API

Sistema de gestão ambiental para indústrias em PHP.

## Estrutura do Projeto

```
/ecomato-api/
├── config/          # Configurações (banco de dados, etc)
├── controllers/     # Lógica dos endpoints
├── models/          # Modelos de dados
├── middleware/      # Middlewares (autenticação, CORS, etc)
├── utils/           # Utilidades (JWT, Response, Validator)
├── index.php        # Entry point / Router principal
└── .env             # Variáveis de ambiente
```

## Como rodar

### 1. Configurar variáveis de ambiente
```bash
cp .env .env.local
# Editar .env.local com seus valores
```

### 2. Criar banco de dados PostgreSQL
```sql
CREATE DATABASE ecomato_db;
```

### 3. Rodar PHP server
```bash
php -S localhost:8000
```

### 4. Testar API
```bash
curl http://localhost:8000/api/test
```

## Endpoints

### Autenticação
- `POST /api/auth/login` - Login (email, password)
- `POST /api/auth/logout` - Logout

### Resíduos
- `GET /api/residuos` - Listar resíduos
- `POST /api/residuos` - Criar novo resíduo
- `GET /api/residuos/{id}` - Detalhe do resíduo
- `DELETE /api/residuos/{id}` - Deletar resíduo

### Documentos
- `GET /api/documentos` - Listar documentos
- `POST /api/documentos` - Criar novo documento
- `GET /api/documentos/{id}` - Detalhe do documento
- `DELETE /api/documentos/{id}` - Deletar documento

### Indicadores e Dashboard
- `GET /api/dashboard` - KPIs principais
- `GET /api/indicadores` - Metas e histórico

## Stack Tecnológico

- PHP 7.4+
- PostgreSQL
- JWT para autenticação
- PDO para queries
