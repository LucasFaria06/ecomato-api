# 🔗 Integração Frontend com Backend

**Para o time do Frontend**

---

## 📌 Resumo

O backend está em **PHP** rodando em `http://localhost:8000`

Todos os endpoints retornam **JSON** e requerem **autenticação JWT**

---

## 🔐 Passo 1: Login

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

**Response (201):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwiZW1haWwiOiJyYXBoYWVsQGVjb21hdG8uY29tLmJyIiwibm9tZSI6IlJhcGhhZWwgQWRtaW4iLCJyb2xlIjoiYWRtaW4iLCJpYXQiOjE2OTk0NTAwMDAsImV4cCI6MTY5OTUzNjQwMH0.xxx",
    "user": {
      "id": 1,
      "nome": "Raphael Admin",
      "email": "raphael@ecomato.com.br",
      "role": "admin"
    }
  }
}
```

**React Code:**
```javascript
const handleLogin = async (email, password) => {
  try {
    const response = await fetch('http://localhost:8000/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email,
        password
      })
    });

    const data = await response.json();

    if (data.success) {
      // ✅ Guardar token no localStorage
      localStorage.setItem('token', data.data.token);
      
      // ✅ Guardar dados do usuário
      localStorage.setItem('user', JSON.stringify(data.data.user));
      
      // ✅ Redirecionar para dashboard
      navigate('/dashboard');
    } else {
      // ❌ Mostrar erro
      alert(data.message);
    }
  } catch (error) {
    console.error('Erro ao fazer login:', error);
  }
};
```

---

## ♻️ Passo 2: Listar Resíduos

**⚠️ IMPORTANTE: Sempre incluir o token no header!**

**Endpoint:**
```
GET http://localhost:8000/api/residuos
```

**Headers:**
```
Authorization: Bearer [TOKEN_AQUI]
Content-Type: application/json
```

**Query params (opcionais):**
```
?tipo_residuo=Papelão
?classe=II-A
```

**Response (200):**
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
    }
  ],
  "timestamp": "2026-09-11 14:30:00"
}
```

**React Code:**
```javascript
const listarResiduos = async (filtros = {}) => {
  try {
    const token = localStorage.getItem('token');
    
    let url = 'http://localhost:8000/api/residuos';
    
    // Adicionar filtros opcionais
    if (filtros.tipo_residuo) {
      url += `?tipo_residuo=${filtros.tipo_residuo}`;
    }
    if (filtros.classe) {
      url += `&classe=${filtros.classe}`;
    }

    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();

    if (data.success) {
      // ✅ Retornar array de resíduos
      return data.data;
    } else {
      // ❌ Erro
      alert(data.message);
      return [];
    }
  } catch (error) {
    console.error('Erro ao listar resíduos:', error);
    return [];
  }
};
```

---

## ➕ Passo 3: Criar Novo Resíduo

**Endpoint:**
```
POST http://localhost:8000/api/residuos
```

**Headers:**
```
Authorization: Bearer [TOKEN_AQUI]
Content-Type: application/json
```

**Body:**
```json
{
  "tipo_residuo": "Papelão",
  "classe": "II-A",
  "quantidade": 500,
  "unidade": "kg",
  "data_geracao": "2026-09-11",
  "tipo_destinacao": "Reciclagem"
}
```

**Campos obrigatórios:**
- `tipo_residuo` (string)
- `classe` (string: I, II-A, II-B)
- `quantidade` (número)
- `unidade` (string: kg, L, t, m³)
- `data_geracao` (date: YYYY-MM-DD)
- `tipo_destinacao` (string: Reciclagem, Tratamento, Aterro, Incineração)

**Response (201):**
```json
{
  "success": true,
  "message": "Recurso criado com sucesso",
  "data": {
    "id": 4,
    "usuario_id": 1,
    "tipo_residuo": "Papelão",
    "classe": "II-A",
    "quantidade": "500.00",
    "unidade": "kg",
    "data_geracao": "2026-09-11",
    "tipo_destinacao": "Reciclagem",
    "situacao": "Pendente",
    "created_at": "2026-09-11 14:35:20"
  },
  "timestamp": "2026-09-11 14:35:20"
}
```

**React Code:**
```javascript
const criarResiduuo = async (formData) => {
  try {
    const token = localStorage.getItem('token');

    const response = await fetch('http://localhost:8000/api/residuos', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        tipo_residuo: formData.tipo,
        classe: formData.classe,
        quantidade: Number(formData.quantidade),
        unidade: formData.unidade,
        data_geracao: formData.data,
        tipo_destinacao: formData.destinacao
      })
    });

    const data = await response.json();

    if (data.success) {
      // ✅ Resíduo criado com sucesso
      alert('Resíduo criado com sucesso!');
      return data.data; // Retorna o resíduo criado
    } else {
      // ❌ Erro de validação
      console.error('Erros:', data.errors);
      alert(data.message);
    }
  } catch (error) {
    console.error('Erro ao criar resíduo:', error);
  }
};
```

---

## 🗑️ Passo 4: Deletar Resíduo

**Endpoint:**
```
DELETE http://localhost:8000/api/residuos/[ID]
```

**Exemplo:**
```
DELETE http://localhost:8000/api/residuos/4
```

**Headers:**
```
Authorization: Bearer [TOKEN_AQUI]
Content-Type: application/json
```

**Response (200):**
```json
{
  "success": true,
  "message": "Recurso deletado com sucesso",
  "data": null,
  "timestamp": "2026-09-11 14:40:00"
}
```

**React Code:**
```javascript
const deletarResiduuo = async (id) => {
  try {
    const token = localStorage.getItem('token');

    const response = await fetch(`http://localhost:8000/api/residuos/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();

    if (data.success) {
      // ✅ Resíduo deletado
      alert('Resíduo deletado com sucesso!');
      return true;
    } else {
      // ❌ Erro
      alert(data.message);
      return false;
    }
  } catch (error) {
    console.error('Erro ao deletar resíduo:', error);
  }
};
```

---

## 📊 Passo 5: Dashboard (KPIs)

**Endpoint:**
```
GET http://localhost:8000/api/dashboard
```

**Headers:**
```
Authorization: Bearer [TOKEN_AQUI]
Content-Type: application/json
```

**Response (200):**
```json
{
  "success": true,
  "message": "Dashboard carregado com sucesso",
  "data": {
    "residuos_gerados_kg": 1400.00,
    "destinacao_adequada_percent": 67.50,
    "total_registros": 3,
    "consumo_agua_m3": 0
  },
  "timestamp": "2026-09-11 14:45:00"
}
```

**React Code:**
```javascript
const carregarDashboard = async () => {
  try {
    const token = localStorage.getItem('token');

    const response = await fetch('http://localhost:8000/api/dashboard', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();

    if (data.success) {
      // ✅ Usar os dados do dashboard
      setDashboardData(data.data);
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error('Erro ao carregar dashboard:', error);
  }
};
```

---

## ⚠️ Tratamento de Erros

**Erros possíveis:**

```javascript
// 401 - Token inválido ou expirado
if (response.status === 401) {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  navigate('/login'); // Redirecionar para login
}

// 400 - Validação falhou
if (response.status === 400) {
  console.error('Erros de validação:', data.errors);
}

// 404 - Recurso não encontrado
if (response.status === 404) {
  alert('Recurso não encontrado');
}

// 500 - Erro do servidor
if (response.status === 500) {
  alert('Erro no servidor. Tente novamente mais tarde.');
}
```

---

## 🧪 Testar no Postman/Insomnia

### **1. Login**
```
POST http://localhost:8000/api/auth/login
Body: { "email": "raphael@ecomato.com.br", "password": "123456" }
```

### **2. Copiar o token da resposta**
```
Exemplo: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### **3. Listar Resíduos**
```
GET http://localhost:8000/api/residuos
Header: Authorization: Bearer [TOKEN]
```

### **4. Criar Resíduo**
```
POST http://localhost:8000/api/residuos
Header: Authorization: Bearer [TOKEN]
Body: { "tipo_residuo": "Metal", "classe": "II-A", "quantidade": 200, "unidade": "kg", "data_geracao": "2026-09-11", "tipo_destinacao": "Reciclagem" }
```

### **5. Dashboard**
```
GET http://localhost:8000/api/dashboard
Header: Authorization: Bearer [TOKEN]
```

---

## 📝 Resumo de URLs

| Método | URL | Autenticação | Descrição |
|--------|-----|--------------|-----------|
| POST | `/api/auth/login` | ❌ | Login |
| GET | `/api/residuos` | ✅ | Listar |
| POST | `/api/residuos` | ✅ | Criar |
| DELETE | `/api/residuos/{id}` | ✅ | Deletar |
| GET | `/api/dashboard` | ✅ | KPIs |

---

## 🚨 Erros Comuns

**"Erro: Cannot POST /api/residuos"**
→ Backend não está rodando. Executar: `php -S localhost:8000`

**"Token não fornecido"**
→ Faltou o header `Authorization: Bearer [TOKEN]`

**"Token inválido ou expirado"**
→ Token expirou (24h). Fazer login novamente.

**"Validação falhou"**
→ Algum campo obrigatório está faltando ou com formato errado.

**"CORS error"**
→ Já está resolvido no backend. Se der, verificar console do navegador.

---

## ✅ Checklist Frontend

- [ ] Login funciona e retorna token
- [ ] Token é guardado em localStorage
- [ ] Listar resíduos funciona (com token)
- [ ] Criar resíduo funciona
- [ ] Deletar resíduo funciona
- [ ] Dashboard carrega KPIs
- [ ] Erro 401 redireciona para login
- [ ] Filtros funcionam (tipo_residuo, classe)

---

**Base URL:** `http://localhost:8000`  
**Documentação completa:** `PROJETO_MVP.md`  
**Dúvidas?** Chamar o Lucas

---

**Última atualização:** 11 de setembro de 2026
