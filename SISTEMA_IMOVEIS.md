# Sistema de Gerenciamento de Imóveis

Sistema completo desenvolvido com Laravel + Vue.js 3 para gerenciamento de imóveis, proprietários e inquilinos.

## Estrutura do Sistema

### Backend (Laravel)

#### Modelos Criados:
- **User** - Usuários com roles (admin, owner, tenant)
- **Property** - Imóveis
- **Tenant** - Contratos de locação

#### Controllers API:
- **AuthController** - Login, registro, logout, perfil
- **PropertyController** - CRUD de imóveis
- **TenantController** - Gerenciamento de inquilinos
- **UserController** - Gerenciamento de usuários (admin)

#### Rotas da API:
- `POST /api/register` - Registro de usuário
- `POST /api/login` - Login
- `POST /api/logout` - Logout (autenticado)
- `GET /api/me` - Dados do usuário logado
- `PUT /api/profile` - Atualizar perfil
- `GET /api/properties` - Listar imóveis (pública e autenticada)
- `POST /api/properties` - Criar imóvel (owner/admin)
- `PUT /api/properties/{id}` - Atualizar imóvel
- `DELETE /api/properties/{id}` - Excluir imóvel
- `GET /api/users` - Listar usuários (admin)

### Frontend (Vue.js 3)

#### Telas Públicas:
1. **PropertyList** (`/`) - Listagem pública de imóveis disponíveis
2. **PropertyDetail** (`/property/:id`) - Detalhes do imóvel
3. **Login** (`/login`) - Tela de login
4. **Register** (`/register`) - Tela de registro

#### Dashboards:

##### 1. Dashboard Administrador (`/admin`)
**Funcionalidades:**
- Visualizar estatísticas gerais do sistema
- Listar todos os usuários (proprietários e inquilinos)
- Visualizar todos os imóveis cadastrados
- Gerenciar proprietários e inquilinos

##### 2. Dashboard Proprietário (`/owner`)
**Funcionalidades:**
- Visualizar seus imóveis
- Adicionar novos imóveis
- Editar informações dos imóveis
- Excluir imóveis
- Ver estatísticas (total, disponíveis, alugados)
- Gerenciar inquilinos de seus imóveis

##### 3. Dashboard Inquilino (`/tenant`)
**Funcionalidades:**
- Visualizar e editar perfil pessoal
- Adicionar/editar telefone
- Adicionar/editar foto
- Gerenciar cartão de crédito
- Ver informações do imóvel alugado
- Ver dados do proprietário

## Como Usar

### 1. Preparar o Ambiente

O ambiente já está configurado. Certifique-se de que o banco de dados está rodando.

### 2. Iniciar o Servidor Laravel

```bash
php artisan serve
```

O servidor estará disponível em: http://localhost:8000

### 3. Iniciar o Vite (para desenvolvimento)

Em outro terminal:

```bash
npm run dev
```

OU use o build já compilado (assets já foram compilados).

### 4. Criar Usuário Administrador

Execute no terminal:

```bash
php artisan tinker
```

Dentro do Tinker:

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@admin.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
    'phone' => '11999999999'
]);
```

Saia com `exit`.

### 5. Acessar o Sistema

Abra o navegador em: **http://localhost:8000**

#### Fluxo de Teste:

1. **Página Inicial (Pública)**
   - Você verá a listagem de imóveis disponíveis
   - Clique em "Registrar" para criar uma conta

2. **Registrar Usuário Proprietário**
   - Nome: Seu nome
   - Email: proprietario@teste.com
   - Telefone: 11988888888
   - Tipo: Proprietário
   - Senha: senha123
   - Confirmar Senha: senha123

3. **Dashboard do Proprietário**
   - Após o registro, você será direcionado ao dashboard
   - Clique em "Adicionar Imóvel"
   - Preencha os dados do imóvel
   - O imóvel aparecerá na listagem

4. **Criar Usuário Inquilino**
   - Faça logout
   - Registre um novo usuário como "Inquilino"
   - Nome: João Silva
   - Email: inquilino@teste.com
   - Tipo: Inquilino

5. **Dashboard do Inquilino**
   - Edite seu perfil
   - Adicione telefone, foto (URL), cartão de crédito

6. **Login como Admin**
   - Faça logout
   - Login: admin@admin.com
   - Senha: admin123
   - Você verá todos os usuários e imóveis cadastrados

7. **Página Pública**
   - Faça logout
   - Navegue pela página inicial
   - Veja os imóveis listados
   - Clique em um imóvel para ver detalhes

## Estrutura de Permissões

### Admin
- Pode ver todos os usuários
- Pode ver todos os imóveis
- Tem acesso total ao sistema

### Proprietário (Owner)
- Pode criar, editar e excluir seus próprios imóveis
- Pode ver seus inquilinos
- Gerenciar informações dos imóveis

### Inquilino (Tenant)
- Pode editar seu perfil pessoal
- Pode adicionar telefone, foto e cartão de crédito
- Pode ver o imóvel que está alugando
- Pode ver informações do proprietário

## Tecnologias Utilizadas

### Backend:
- Laravel 11
- Laravel Sanctum (autenticação API)
- MySQL

### Frontend:
- Vue.js 3
- Vue Router (navegação)
- Pinia (gerenciamento de estado)
- Axios (requisições HTTP)
- Tailwind CSS (estilização)

### Build:
- Vite (bundler)

## Endpoints da API

### Autenticação:
- `POST /api/register` - Registrar usuário
- `POST /api/login` - Fazer login
- `POST /api/logout` - Fazer logout (requer auth)
- `GET /api/me` - Obter dados do usuário logado (requer auth)
- `PUT /api/profile` - Atualizar perfil (requer auth)

### Imóveis:
- `GET /api/properties` - Listar imóveis
- `GET /api/properties/{id}` - Ver detalhes do imóvel
- `POST /api/properties` - Criar imóvel (requer auth - owner)
- `PUT /api/properties/{id}` - Atualizar imóvel (requer auth - owner/admin)
- `DELETE /api/properties/{id}` - Excluir imóvel (requer auth - owner/admin)

### Usuários (Admin):
- `GET /api/users` - Listar todos os usuários (requer auth - admin)

## Observações

1. O sistema usa autenticação Bearer Token (Sanctum)
2. O token é armazenado no localStorage do navegador
3. As rotas protegidas verificam o token automaticamente
4. O Vue Router protege as rotas baseado no role do usuário
5. Todos os preços são formatados em Real (R$)

## Estrutura de Arquivos Vue

```
resources/js/
├── App.vue                      # Componente principal
├── app.js                       # Entrada principal
├── router/
│   └── index.js                 # Configuração de rotas
├── stores/
│   └── auth.js                  # Store Pinia para autenticação
└── views/
    ├── Auth/
    │   ├── Login.vue           # Tela de login
    │   └── Register.vue        # Tela de registro
    ├── Public/
    │   ├── PropertyList.vue    # Lista pública de imóveis
    │   └── PropertyDetail.vue  # Detalhes do imóvel
    ├── Admin/
    │   └── Dashboard.vue       # Dashboard do admin
    ├── Owner/
    │   └── Dashboard.vue       # Dashboard do proprietário
    └── Tenant/
        └── Dashboard.vue       # Dashboard do inquilino
```

## Próximos Passos (Melhorias Futuras)

1. Upload de imagens para os imóveis
2. Sistema de mensagens entre proprietário e inquilino
3. Gestão de contratos de locação
4. Sistema de pagamentos
5. Notificações por email
6. Calendário de manutenção
7. Relatórios financeiros
8. Sistema de avaliações
