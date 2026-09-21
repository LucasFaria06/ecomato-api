# ⚡ Guia Rápido - EcoMato

**De 0 até rodando em 5 minutos**

---

## 🚀 5 Passos Rápidos

### 1️⃣ Criar Banco de Dados (1 min)
```bash
cd /home/usuario/ecomato-api
mysql -u root -p < DATABASE_SETUP_MYSQL.sql
```

**O que acontece:**
- ✅ Database `ecomato_db` criada
- ✅ Tabelas `usuarios` e `residuos` criadas
- ✅ 2 usuários de teste inseridos
- ✅ 3 resíduos de exemplo inseridos

---

### 2️⃣ Configurar .env (1 min)
```bash
cp .env.example .env
```

**Se houver senha no MySQL, edite:**
```env
DB_PASSWORD=sua_senha_aqui
```

**Credenciais padrão (sem senha):**
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=ecomato_db
DB_USER=root
DB_PASSWORD=
```

---

### 3️⃣ Iniciar Servidor (30 seg)
```bash
php -S localhost:8000
```

**Você verá:**
```
Development Server is running...
Listening on http://127.0.0.1:8000
```

---

### 4️⃣ Acessar no Navegador (30 seg)
```
http://localhost:8000
```

---

### 5️⃣ Fazer Login (30 seg)
```
Email:  raphael@industriamodelo.com.br
Senha:  123456
```

**✅ Pronto! Você está dentro!**

---

## 🎯 O que Você Pode Fazer Agora

- ✅ Ver o Dashboard com KPIs
- ✅ Listar resíduos existentes
- ✅ Filtrar por tipo e classe
- ✅ Criar novo resíduo
- ✅ Deletar resíduo
- ✅ Ver gráficos interativos

---

## ⚠️ Se Der Erro

### Erro: "Can't connect to MySQL"
```bash
# Verifique se MySQL está rodando
mysql -u root -p -e "SELECT 1"
```

### Erro: "Class PDO not found"
```bash
# Verifique extensão PDO
php -m | grep PDO
```

### Erro ao logar
```bash
# Verifique credenciais em .env
cat /home/usuario/ecomato-api/.env
```

### Port 8000 já está em uso
```bash
# Use outra porta
php -S localhost:8001
```

---

## 📚 Próximas Leituras

Depois que tudo estiver funcionando:

1. **[README.md](./README.md)** - Overview do projeto
2. **[CONECTAR_MYSQL.md](./CONECTAR_MYSQL.md)** - Detalhes de conexão
3. **[ESTRUTURA_PROJETO.md](./ESTRUTURA_PROJETO.md)** - Arquitetura
4. **[STATUS_PROJETO.md](./STATUS_PROJETO.md)** - Status completo

---

## 🧪 Testar a API (Avançado)

### Login via curl
```bash
curl -X POST http://localhost:8000/api/auth.php?action=login \
  -H "Content-Type: application/json" \
  -d '{"email":"raphael@industriamodelo.com.br","password":"123456"}'
```

### Resposta (copie o token)
```json
{
  "success": true,
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {"id": 1, "nome": "Raphael Admin", ...}
  }
}
```

### Usar o token
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  http://localhost:8000/api/residuos.php
```

---

## 💾 Dados de Teste

**Usuário Admin:**
- Email: raphael@industriamodelo.com.br
- Senha: 123456

**Usuário Comum:**
- Email: teste@industriamodelo.com.br
- Senha: 123456

**Resíduos pré-carregados:**
- 850 kg Papelão
- 120 L Óleo usado
- 430 kg Plástico

---

## 🔒 Segurança (Importante!)

**NÃO ESQUEÇA EM PRODUÇÃO:**
- [ ] Alterar senha do MySQL
- [ ] Alterar JWT_SECRET em .env
- [ ] Usar HTTPS
- [ ] Adicionar rate limiting
- [ ] Configurar logging
- [ ] Fazer backups automáticos

---

## ❓ Dúvidas Rápidas

**P: O frontend é HTML mesmo?**  
R: Sim! HTML puro + JavaScript vanilla + Tailwind CSS. Sem frameworks.

**P: Preciso instalar algo?**  
R: Não! Só PHP, MySQL e navegador.

**P: Posso rodar em outro computador?**  
R: Sim, se tiver PHP e MySQL instalados.

**P: Como fazer backup do banco?**  
R: `mysqldump -u root -p ecomato_db > backup.sql`

**P: Como restaurar backup?**  
R: `mysql -u root -p ecomato_db < backup.sql`

---

## 🎉 Sucesso!

Se você conseguiu fazer login = **Tudo está funcionando!** 🎊

Agora explore o sistema e leia a documentação completa.

---

**Precisa de ajuda?** Leia os outros arquivos de documentação!
