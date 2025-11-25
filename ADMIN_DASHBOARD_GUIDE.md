# 📊 Guia do Dashboard Administrativo

## ✨ Componentes Criados

### 1. **Sidebar** (`components/Admin/Sidebar.vue`)
Sidebar de navegação com menu lateral moderno.

**Uso:**
```vue
<Sidebar
  :isOpen="sidebarOpen"
  :currentView="currentView"
  @toggle="sidebarOpen = !sidebarOpen"
  @navigate="handleNavigate"
/>
```

**Props:**
- `isOpen`: Boolean - Estado da sidebar (para mobile)
- `currentView`: String - View atual selecionada

**Events:**
- `toggle`: Alternar sidebar no mobile
- `navigate`: Navegar para uma view

---

### 2. **Header** (`components/Admin/Header.vue`)
Header superior com perfil, notificações e busca.

**Uso:**
```vue
<Header
  :title="pageTitle"
  :subtitle="pageSubtitle"
  :user="authStore.user"
  :notificationCount="5"
  @toggle-sidebar="sidebarOpen = !sidebarOpen"
  @navigate="handleNavigate"
  @logout="handleLogout"
/>
```

**Props:**
- `title`: String - Título da página
- `subtitle`: String - Subtítulo da página
- `user`: Object - Dados do usuário logado
- `notificationCount`: Number - Número de notificações

**Events:**
- `toggle-sidebar`: Abrir/fechar sidebar no mobile
- `navigate`: Navegar para uma view
- `logout`: Fazer logout

---

### 3. **DataTable** (`components/Admin/DataTable.vue`)
Tabela de dados reutilizável com paginação.

**Uso:**
```vue
<DataTable
  title="Usuários"
  subtitle="Gerencie todos os usuários do sistema"
  :columns="columns"
  :data="users"
  :actions="actions"
  :loading="loading"
  :pagination="pagination"
  @create="openCreateModal"
  @edit="handleEdit"
  @delete="handleDelete"
  @page-change="handlePageChange"
/>
```

**Props:**
- `title`: String - Título da tabela
- `subtitle`: String - Subtítulo opcional
- `columns`: Array - Configuração das colunas
- `data`: Array - Dados da tabela
- `actions`: Array - Ações disponíveis (editar, excluir, etc)
- `loading`: Boolean - Estado de carregamento
- `pagination`: Object - Dados de paginação
- `showCreateButton`: Boolean - Mostrar botão criar
- `createButtonText`: String - Texto do botão criar
- `emptyMessage`: String - Mensagem quando não há dados

**Exemplo de Columns:**
```javascript
const columns = [
  {
    key: 'id',
    label: 'ID',
    cellClass: 'font-mono text-gray-500'
  },
  {
    key: 'name',
    label: 'Nome',
    cellClass: 'font-semibold text-gray-900'
  },
  {
    key: 'email',
    label: 'E-mail'
  },
  {
    key: 'created_at',
    label: 'Cadastrado em',
    format: (value) => new Date(value).toLocaleDateString('pt-BR')
  }
];
```

**Exemplo de Actions:**
```javascript
const actions = [
  {
    name: 'view',
    label: 'Visualizar',
    event: 'view',
    icon: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
    class: 'text-blue-600 hover:bg-blue-50'
  },
  {
    name: 'edit',
    label: 'Editar',
    event: 'edit',
    icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    class: 'text-green-600 hover:bg-green-50'
  },
  {
    name: 'delete',
    label: 'Excluir',
    event: 'delete',
    icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
    class: 'text-red-600 hover:bg-red-50'
  }
];
```

**Slots Personalizados:**
```vue
<DataTable :columns="columns" :data="users">
  <!-- Personalizar célula específica -->
  <template #cell-status="{ value }">
    <span :class="value === 'active' ? 'text-green-600' : 'text-red-600'">
      {{ value }}
    </span>
  </template>

  <!-- Personalizar ações do header -->
  <template #header-actions>
    <button>Exportar CSV</button>
    <button>Criar Novo</button>
  </template>
</DataTable>
```

---

### 4. **Button** (`components/Admin/Button.vue`)
Botão estilizado reutilizável.

**Uso:**
```vue
<Button
  variant="primary"
  size="md"
  icon="M12 4v16m8-8H4"
  @click="handleClick"
>
  Criar Novo
</Button>
```

**Props:**
- `variant`: String - 'primary', 'secondary', 'success', 'danger', 'warning', 'ghost'
- `size`: String - 'sm', 'md', 'lg'
- `type`: String - Tipo do botão HTML
- `disabled`: Boolean - Desabilitar botão
- `loading`: Boolean - Estado de carregamento
- `icon`: String - SVG path do ícone
- `fullWidth`: Boolean - Largura total

**Variantes:**
```vue
<Button variant="primary">Primário</Button>
<Button variant="secondary">Secundário</Button>
<Button variant="success">Sucesso</Button>
<Button variant="danger">Perigo</Button>
<Button variant="warning">Aviso</Button>
<Button variant="ghost">Fantasma</Button>
```

---

### 5. **Modal** (`components/Admin/Modal.vue`)
Modal reutilizável para formulários e confirmações.

**Uso:**
```vue
<Modal
  v-model="showModal"
  title="Criar Usuário"
  subtitle="Preencha os dados abaixo"
  size="md"
  @confirm="handleConfirm"
  @close="handleClose"
>
  <form>
    <!-- Conteúdo do formulário -->
  </form>

  <template #footer>
    <button @click="showModal = false">Cancelar</button>
    <button @click="handleSave">Salvar</button>
  </template>
</Modal>
```

**Props:**
- `modelValue`: Boolean - Estado do modal (v-model)
- `title`: String - Título do modal
- `subtitle`: String - Subtítulo opcional
- `size`: String - 'sm', 'md', 'lg', 'xl', 'full'
- `closeable`: Boolean - Permitir fechar
- `showDefaultFooter`: Boolean - Mostrar footer padrão
- `confirmText`: String - Texto do botão confirmar
- `confirmLoading`: Boolean - Estado de carregamento do confirm

---

## 🎨 Guia de Estilo

### Cores

```css
/* Primárias */
blue-500/600 - Azul principal
purple-600 - Roxo de destaque
slate-900/800 - Sidebar

/* Secundárias */
gray-50/100/200 - Backgrounds
green-500/600 - Sucesso
red-500/600 - Perigo/Erro
yellow-500/600 - Aviso
```

### Espaçamento
- Padding interno: `px-6 py-4`
- Espaçamento entre elementos: `space-x-3`, `space-y-4`
- Margem de seções: `mb-6`, `mt-8`

### Tipografia
- Títulos principais: `text-2xl font-bold`
- Subtítulos: `text-lg font-semibold`
- Texto corpo: `text-sm` ou `text-base`
- Labels: `text-xs font-semibold uppercase`

### Bordas e Sombras
- Bordas arredondadas: `rounded-lg`, `rounded-xl`
- Sombras leves: `shadow-sm`
- Sombras médias: `shadow-md`
- Sombras fortes: `shadow-2xl`

---

## 📦 Estrutura de Arquivos

```
resources/js/
├── components/
│   └── Admin/
│       ├── Sidebar.vue       ✅ Navegação lateral
│       ├── Header.vue         ✅ Cabeçalho superior
│       ├── DataTable.vue      ✅ Tabela de dados
│       ├── Button.vue         ✅ Botão reutilizável
│       └── Modal.vue          ✅ Modal genérico
│
└── views/
    └── Admin/
        ├── Dashboard.vue      📝 Dashboard principal
        ├── Users.vue          📝 Gestão de usuários
        ├── Owners.vue         📝 Gestão de proprietários
        ├── Tenants.vue        📝 Gestão de inquilinos
        ├── Properties.vue     📝 Gestão de imóveis
        ├── Communities.vue    📝 Gestão de comunidades
        ├── Settings.vue       📝 Configurações
        ├── Profile.vue        📝 Perfil do admin
        └── Logs.vue           📝 Logs do sistema
```

---

## 🚀 Próximos Passos

1. Implementar as views de gestão (Users, Owners, Tenants, etc)
2. Integrar com as APIs existentes
3. Adicionar validação de formulários
4. Implementar filtros e busca nas tabelas
5. Adicionar gráficos no dashboard principal
6. Implementar sistema de notificações em tempo real

---

## 💡 Dicas de Uso

### Responsividade
Todos os componentes são responsivos. No mobile:
- Sidebar vira drawer lateral
- Tabelas têm scroll horizontal
- Header adapta o layout

### Performance
- Use `v-if` para modais (monta/desmonta)
- Use `v-show` para tabs (mantém na DOM)
- Implemente paginação server-side para grandes datasets

### Acessibilidade
- Todos os botões têm títulos descritivos
- Modais prendem o foco quando abertos
- Navegação por teclado está implementada
