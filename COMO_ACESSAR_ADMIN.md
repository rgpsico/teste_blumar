# 🔐 Como Acessar o Dashboard Administrativo

## 📍 URL de Acesso

```
https://imoveis.comunidadeppg.com.br/admin
```

ou em desenvolvimento:

```
http://localhost/admin
```

## ⚠️ Problema: "Não Consigo Ver a Tela"

### Causa 1: Você Não Está Logado como Admin

A rota `/admin` é protegida e requer:
- ✅ Estar autenticado (logado)
- ✅ Ter role = `admin` no banco de dados

Se você não for admin, será redirecionado para a home automaticamente.

## ✅ Solução: Tornar Seu Usuário Admin

### Opção 1: Via SQL (phpMyAdmin / MySQL)

1. Abra o phpMyAdmin em `http://localhost/phpmyadmin`
2. Selecione o banco de dados do seu projeto
3. Clique na tabela `users`
4. Encontre seu usuário pelo email
5. Edite o campo `role` para `admin`

**Ou execute este SQL:**

```sql
-- Ver todos os usuários e suas roles
SELECT id, name, email, role FROM users;

-- Tornar um usuário específico admin
UPDATE users
SET role = 'admin'
WHERE email = 'seu@email.com';

-- Verificar se funcionou
SELECT id, name, email, role FROM users WHERE email = 'seu@email.com';
```

### Opção 2: Via Laravel Tinker

```bash
php artisan tinker
```

Depois dentro do tinker:

```php
// Ver seu usuário atual
$user = User::where('email', 'seu@email.com')->first();
echo $user->name . ' - Role: ' . $user->role;

// Tornar admin
$user->role = 'admin';
$user->save();

// Confirmar
echo "Agora você é: " . $user->role;
exit
```

### Opção 3: Via Script PHP

Crie um arquivo `make-admin.php` na raiz do projeto:

```php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'seu@email.com'; // MUDE AQUI

$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "❌ Usuário não encontrado: $email\n";
    exit(1);
}

echo "Usuário encontrado: {$user->name}\n";
echo "Role atual: {$user->role}\n";

$user->role = 'admin';
$user->save();

echo "✅ Agora você é ADMIN!\n";
echo "Faça logout e login novamente para aplicar as mudanças.\n";
```

Execute:
```bash
php make-admin.php
```

## 🔄 Passos para Acessar

### 1. Tornar-se Admin (escolha uma opção acima)

### 2. Fazer Logout e Login Novamente

**IMPORTANTE:** Você precisa fazer logout e login novamente para que a nova role seja carregada na sessão.

```
1. Clique no seu perfil (canto superior direito)
2. Clique em "Sair" ou "Logout"
3. Faça login novamente com suas credenciais
```

### 3. Navegar para /admin

Depois de fazer login como admin:

**Opção A - Digite a URL diretamente:**
```
https://imoveis.comunidadeppg.com.br/admin
```

**Opção B - Link direto:**
Se houver um link "Admin" ou "Painel Admin" no menu, clique nele.

## 🎯 O Que Você Deve Ver

Ao acessar `/admin` como admin, você verá:

```
┌────────────────────────────────────────┐
│ [≡] FIMOVEIS        [🔔] [👤 Admin]   │ ← Header
├────────────────────────────────────────┤
│                                        │
│  📊 Dashboard                          │
│                                        │
│  ┌──────────┐ ┌──────────┐           │
│  │ 👁️ 450   │ │ 📅 45    │           │ ← Stats Cards
│  │Visitantes│ │ Hoje     │           │
│  └──────────┘ └──────────┘           │
│                                        │
│  ┌──────────┐ ┌──────────┐           │
│  │ 👤 35    │ │ 👥 58    │           │
│  │Proprietá.│ │Inquilinos│           │
│  └──────────┘ └──────────┘           │
│                                        │
│  [Usuários Recentes]                  │
│  [Ações Rápidas]                      │
│                                        │
└────────────────────────────────────────┘
```

## 🔍 Verificar Se Você É Admin

### No Navegador (Console F12)

Abra o console do navegador e digite:

```javascript
// Ver dados do usuário atual
console.log(JSON.parse(localStorage.getItem('user')));

// Ou via API
fetch('/api/auth/user', {
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('token'),
    'Accept': 'application/json'
  }
})
.then(r => r.json())
.then(user => {
  console.log('Nome:', user.name);
  console.log('Email:', user.email);
  console.log('Role:', user.role);

  if (user.role === 'admin') {
    console.log('✅ Você é ADMIN! Pode acessar /admin');
  } else {
    console.log('❌ Você não é admin. Role atual:', user.role);
  }
});
```

### Via API REST

**Request:**
```bash
GET https://imoveis.comunidadeppg.com.br/api/auth/user
Authorization: Bearer SEU_TOKEN
```

**Response esperada:**
```json
{
  "id": 1,
  "name": "Seu Nome",
  "email": "seu@email.com",
  "role": "admin",  ← Deve ser "admin"
  "created_at": "2025-01-20T10:00:00.000000Z"
}
```

## 🚫 Erros Comuns

### Erro 1: Redirecionado para Home

**Causa:** Você não tem role `admin`.

**Solução:**
1. Verifique sua role no banco
2. Mude para `admin` usando um dos métodos acima
3. Faça logout e login novamente

### Erro 2: Redirecionado para Login

**Causa:** Você não está autenticado.

**Solução:**
1. Faça login primeiro
2. Depois acesse `/admin`

### Erro 3: Página em Branco

**Causa:** Assets não foram compilados.

**Solução:**
```bash
npm run build
# ou
build-fix-logs.bat
```

### Erro 4: Vejo Dashboard de Owner/Tenant

**Causa:** Você está logado com outro tipo de usuário.

**Solução:**
1. Saia da conta atual
2. Mude a role do usuário para `admin` no banco
3. Faça login novamente

## 🔐 Roles Disponíveis

| Role | URL de Acesso | Descrição |
|------|---------------|-----------|
| `admin` | `/admin` | Dashboard administrativo completo |
| `owner` | `/owner` | Dashboard do proprietário |
| `tenant` | `/tenant` | Dashboard do inquilino |

## 📋 Checklist de Acesso

- [ ] 1. Verificar role no banco (`role = 'admin'`)
- [ ] 2. Fazer logout da conta atual
- [ ] 3. Fazer login novamente
- [ ] 4. Acessar `https://imoveis.comunidadeppg.com.br/admin`
- [ ] 5. Ver dashboard com estatísticas

## 🆘 Ainda Não Funciona?

### Debug Passo a Passo

1. **Abra o console do navegador (F12)**

2. **Execute este código:**
```javascript
// Verificar token
console.log('Token:', localStorage.getItem('token'));

// Verificar usuário
console.log('User:', JSON.parse(localStorage.getItem('user')));

// Tentar acessar API admin
fetch('/api/admin/analytics', {
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('token'),
    'Accept': 'application/json'
  }
})
.then(r => {
  console.log('Status:', r.status);
  if (r.status === 403) {
    console.log('❌ ERRO: Você não tem permissão (não é admin)');
  } else if (r.status === 401) {
    console.log('❌ ERRO: Token inválido ou expirado');
  } else if (r.status === 200) {
    console.log('✅ SUCESSO: Você tem acesso admin!');
  }
  return r.json();
})
.then(data => console.log('Dados:', data))
.catch(err => console.error('Erro:', err));
```

3. **Se retornar 403:**
   - Seu usuário não é admin
   - Vá ao banco e mude a role
   - Faça logout e login

4. **Se retornar 401:**
   - Seu token expirou
   - Faça logout e login novamente

5. **Se retornar 200:**
   - Você é admin!
   - O problema pode ser no frontend
   - Execute `npm run build`

## 📞 Contato

Se ainda tiver problemas, verifique:
- Logs do Laravel: `storage/logs/laravel.log`
- Console do navegador: F12 → Console
- Network tab: F12 → Network

---

**Resumo Rápido:**
1. SQL: `UPDATE users SET role = 'admin' WHERE email = 'seu@email.com';`
2. Logout e Login novamente
3. Acesse: `https://imoveis.comunidadeppg.com.br/admin`
4. Veja as estatísticas! 📊
