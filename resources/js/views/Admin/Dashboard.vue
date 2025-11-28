<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">
    <!-- Sidebar -->
    <Sidebar
      :isOpen="sidebarOpen"
      :currentView="currentView"
      @toggle="sidebarOpen = false"
      @navigate="handleNavigate"
    />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0" :class="{ 'lg:ml-64': true }">
      <!-- Header -->
      <Header
        :title="pageTitle"
        :subtitle="pageSubtitle"
        :user="authStore.user"
        :notificationCount="unreadNotifications"
        @toggle-sidebar="sidebarOpen = !sidebarOpen"
        @navigate="handleNavigate"
        @logout="handleLogout"
      />

      <!-- Content Area -->
      <main class="flex-1 overflow-auto p-6">
        <!-- Dashboard View -->
        <div v-if="currentView === 'dashboard'" class="space-y-6">
          <!-- Stats Cards - Main -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatsCard
              title="Total de Visitantes"
              :value="stats.totalVisitors"
              icon="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
              color="blue"
              :subtitle="`${stats.uniqueVisitors || 0} únicos`"
            />
            <StatsCard
              title="Visitantes Hoje"
              :value="stats.visitorsToday"
              icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              color="green"
              :subtitle="`${stats.uniqueVisitorsToday || 0} únicos`"
            />
            <StatsCard
              title="Proprietários"
              :value="stats.owners"
              icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
              color="purple"
              :subtitle="`${stats.ownersThisMonth || 0} este mês`"
            />
            <StatsCard
              title="Inquilinos"
              :value="stats.tenants"
              icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
              color="orange"
              :subtitle="`${stats.tenantsThisMonth || 0} este mês`"
            />
          </div>

          <!-- Secondary Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatsCard
              title="Total de Imóveis"
              :value="stats.properties"
              icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
              color="indigo"
            />
            <StatsCard
              title="Imóveis Alugados"
              :value="stats.rented"
              icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              color="teal"
            />
            <StatsCard
              title="Comunidades"
              :value="stats.communities"
              icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
              color="pink"
            />
            <StatsCard
              title="Total de Usuários"
              :value="stats.users"
              icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
              color="gray"
            />
          </div>

          <!-- Recent Activity & Quick Actions -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Users -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Usuários Recentes</h3>
              <div class="space-y-3">
                <div
                  v-for="user in recentUsers"
                  :key="user.id"
                  class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition"
                >
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                      {{ user.name[0].toUpperCase() }}
                    </div>
                    <div>
                      <p class="font-semibold text-gray-900">{{ user.name }}</p>
                      <p class="text-sm text-gray-500">{{ user.email }}</p>
                    </div>
                  </div>
                  <span
                    :class="getRoleBadgeClass(user.role)"
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                  >
                    {{ getRoleLabel(user.role) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Ações Rápidas</h3>
              <div class="space-y-2">
                <button
                  @click="handleNavigate('owners')"
                  class="w-full flex items-center space-x-3 px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-lg transition text-left"
                >
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  <span class="text-sm font-medium text-blue-900">Gerenciar Proprietários</span>
                </button>
                <button
                  @click="handleNavigate('tenants')"
                  class="w-full flex items-center space-x-3 px-4 py-3 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-lg transition text-left"
                >
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  <span class="text-sm font-medium text-green-900">Gerenciar Inquilinos</span>
                </button>
                <button
                  @click="handleNavigate('properties')"
                  class="w-full flex items-center space-x-3 px-4 py-3 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-lg transition text-left"
                >
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  <span class="text-sm font-medium text-purple-900">Gerenciar Imóveis</span>
                </button>
                <button
                  @click="handleNavigate('logs')"
                  class="w-full flex items-center space-x-3 px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 rounded-lg transition text-left"
                >
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span class="text-sm font-medium text-gray-900">Ver Logs</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Owners View -->
        <DataTable
          v-if="currentView === 'owners'"
          title="Proprietários"
          subtitle="Gerencie todos os proprietários do sistema"
          :columns="ownersColumns"
          :data="owners"
          :actions="tableActions"
          :loading="loading"
          @create="openCreateUserModal('owner')"
          @edit="handleEditUser"
          @delete="handleDeleteUser"
        >
          <template #cell-name="{ item }">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                {{ item.name[0].toUpperCase() }}
              </div>
              <div>
                <p class="font-semibold text-gray-900">{{ item.name }}</p>
                <p class="text-xs text-gray-500">{{ item.email }}</p>
              </div>
            </div>
          </template>
        </DataTable>

        <!-- Tenants View -->
        <DataTable
          v-if="currentView === 'tenants'"
          title="Inquilinos"
          subtitle="Gerencie todos os inquilinos do sistema"
          :columns="tenantsColumns"
          :data="tenants"
          :actions="tableActions"
          :loading="loading"
          @create="openCreateUserModal('tenant')"
          @edit="handleEditUser"
          @delete="handleDeleteUser"
        >
          <template #cell-name="{ item }">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                {{ item.name[0].toUpperCase() }}
              </div>
              <div>
                <p class="font-semibold text-gray-900">{{ item.name }}</p>
                <p class="text-xs text-gray-500">{{ item.email }}</p>
              </div>
            </div>
          </template>
        </DataTable>

        <!-- Properties View -->
        <DataTable
          v-if="currentView === 'properties'"
          title="Imóveis"
          subtitle="Gerencie todos os imóveis do sistema"
          :columns="propertiesColumns"
          :data="properties"
          :actions="propertyActions"
          :loading="loading"
          @view="viewProperty"
          @edit="editProperty"
          @delete="deleteProperty"
        >
          <template #cell-title="{ item }">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200">
                <img
                  v-if="item.photos && item.photos[0]"
                  :src="item.photos[0]"
                  :alt="item.title"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>
              <div>
                <p class="font-semibold text-gray-900 truncate max-w-xs">{{ item.title }}</p>
                <p class="text-xs text-gray-500">{{ item.city }}, {{ item.state }}</p>
              </div>
            </div>
          </template>
          <template #cell-status="{ value }">
            <span
              :class="{
                'bg-green-100 text-green-800': value === 'available',
                'bg-purple-100 text-purple-800': value === 'rented',
                'bg-yellow-100 text-yellow-800': value === 'maintenance'
              }"
              class="px-3 py-1 rounded-full text-xs font-semibold"
            >
              {{ getStatusLabel(value) }}
            </span>
          </template>
        </DataTable>

        <!-- Communities View -->
        <DataTable
          v-if="currentView === 'communities'"
          title="Comunidades"
          subtitle="Gerencie todas as comunidades do sistema"
          :columns="communitiesColumns"
          :data="communities"
          :actions="tableActions"
          :loading="loading"
          @create="openCreateCommunityModal"
          @edit="handleEditCommunity"
          @delete="handleDeleteCommunity"
        />

        <!-- Logs View -->
        <div v-if="currentView === 'logs'" class="space-y-6">
          <DataTable
            title="Logs de Visitantes"
            subtitle="Acompanhe as visitas ao site"
            :columns="visitorLogsColumns"
            :data="visitorLogs"
            :loading="loading"
            :show-create-button="false"
          />

          <DataTable
            title="Logs de Registro"
            subtitle="Acompanhe os registros de novos usuários"
            :columns="registrationLogsColumns"
            :data="registrationLogs"
            :loading="loading"
            :show-create-button="false"
          />
        </div>

        <!-- Settings View -->
        <div v-if="currentView === 'settings'" class="max-w-4xl">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Configurações do Sistema</h3>
            <p class="text-gray-500">Funcionalidade em desenvolvimento...</p>
          </div>
        </div>

        <!-- Profile View -->
        <div v-if="currentView === 'profile'" class="max-w-2xl">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Meu Perfil</h3>
            <form @submit.prevent="updateProfile" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nome</label>
                <input
                  v-model="profileForm.name"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input
                  v-model="profileForm.email"
                  type="email"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
                <input
                  v-model="profileForm.phone"
                  type="tel"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div class="pt-4">
                <button
                  type="submit"
                  class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-medium"
                >
                  Salvar Alterações
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import axios from 'axios';
import Sidebar from '../../components/Admin/Sidebar.vue';
import Header from '../../components/Admin/Header.vue';
import DataTable from '../../components/Admin/DataTable.vue';

// Components
const StatsCard = {
  props: ['title', 'value', 'icon', 'color', 'subtitle'],
  template: `
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600 mb-1">{{ title }}</p>
          <p class="text-3xl font-bold text-gray-900">{{ value || 0 }}</p>
          <p v-if="subtitle" class="text-xs text-gray-500 mt-1">{{ subtitle }}</p>
        </div>
        <div :class="'w-12 h-12 bg-gradient-to-br from-' + color + '-500 to-' + color + '-600 rounded-lg flex items-center justify-center'">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icon" />
          </svg>
        </div>
      </div>
    </div>
  `
};

const router = useRouter();
const authStore = useAuthStore();

const sidebarOpen = ref(false);
const currentView = ref('dashboard');
const loading = ref(false);
const unreadNotifications = ref(0);

// Data
const stats = ref({
  users: 0,
  properties: 0,
  rented: 0,
  communities: 0,
  // Estatísticas de visitantes
  totalVisitors: 0,
  uniqueVisitors: 0,
  visitorsToday: 0,
  uniqueVisitorsToday: 0,
  // Estatísticas de usuários por tipo
  owners: 0,
  tenants: 0,
  ownersThisMonth: 0,
  tenantsThisMonth: 0
});

const owners = ref([]);
const tenants = ref([]);
const properties = ref([]);
const communities = ref([]);
const visitorLogs = ref([]);
const registrationLogs = ref([]);
const recentUsers = ref([]);

const profileForm = ref({
  name: '',
  email: '',
  phone: ''
});

// Computed
const pageTitle = computed(() => {
  const titles = {
    dashboard: 'Dashboard',
    owners: 'Proprietários',
    tenants: 'Inquilinos',
    properties: 'Imóveis',
    communities: 'Comunidades',
    settings: 'Configurações',
    profile: 'Meu Perfil',
    logs: 'Logs do Sistema'
  };
  return titles[currentView.value] || 'Dashboard';
});

const pageSubtitle = computed(() => {
  const subtitles = {
    dashboard: 'Visão geral do sistema',
    owners: 'Gerencie todos os proprietários',
    tenants: 'Gerencie todos os inquilinos',
    properties: 'Gerencie todos os imóveis',
    communities: 'Gerencie as comunidades',
    settings: 'Configure o sistema',
    profile: 'Gerencie suas informações pessoais',
    logs: 'Monitore as atividades do sistema'
  };
  return subtitles[currentView.value] || '';
});

// Table Columns
const ownersColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome' },
  { key: 'phone', label: 'Telefone' },
  {
    key: 'created_at',
    label: 'Cadastrado em',
    format: (value) => new Date(value).toLocaleDateString('pt-BR')
  }
];

const tenantsColumns = [...ownersColumns];

const propertiesColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'title', label: 'Imóvel' },
  {
    key: 'price',
    label: 'Preço',
    format: (value) => `R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(value)}`
  },
  { key: 'status', label: 'Status' },
  { key: 'owner.name', label: 'Proprietário' }
];

const communitiesColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome', cellClass: 'font-semibold text-gray-900' },
  { key: 'description', label: 'Descrição' }
];

const visitorLogsColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  {
    key: 'ip_address',
    label: 'IP',
    format: (value) => value || '-'
  },
  {
    key: 'user_agent',
    label: 'Navegador',
    format: (value) => value ? value.substring(0, 50) + (value.length > 50 ? '...' : '') : '-'
  },
  {
    key: 'created_at',
    label: 'Data',
    format: (value) => value ? new Date(value).toLocaleString('pt-BR') : '-'
  }
];

const registrationLogsColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  {
    key: 'user.name',
    label: 'Usuário',
    format: (value, item) => item.user?.name || '-'
  },
  {
    key: 'user.email',
    label: 'Email',
    format: (value, item) => item.user?.email || '-'
  },
  {
    key: 'user.role',
    label: 'Tipo',
    format: (value, item) => item.user?.role ? getRoleLabel(item.user.role) : '-'
  },
  {
    key: 'created_at',
    label: 'Data',
    format: (value) => value ? new Date(value).toLocaleString('pt-BR') : '-'
  }
];

// Table Actions
const tableActions = [
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

const propertyActions = [
  {
    name: 'view',
    label: 'Visualizar',
    event: 'view',
    icon: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
    class: 'text-blue-600 hover:bg-blue-50'
  },
  ...tableActions
];

// Methods
const handleNavigate = (view) => {
  currentView.value = view;
  sidebarOpen.value = false;
  loadViewData();
};

const loadViewData = async () => {
  loading.value = true;
  try {
    switch (currentView.value) {
      case 'dashboard':
        await loadDashboardData();
        break;
      case 'owners':
        await loadOwners();
        break;
      case 'tenants':
        await loadTenants();
        break;
      case 'properties':
        await loadProperties();
        break;
      case 'communities':
        await loadCommunities();
        break;
      case 'logs':
        await loadLogs();
        break;
    }
  } catch (error) {
    console.error('Erro ao carregar dados:', error);
  } finally {
    loading.value = false;
  }
};

const loadDashboardData = async () => {
  try {
    const [usersRes, propertiesRes, communitiesRes, analyticsRes] = await Promise.all([
      axios.get('/api/users'),
      axios.get('/api/properties'),
      axios.get('/api/communities'),
      axios.get('/api/admin/analytics?period=30') // Últimos 30 dias
    ]);

    const allUsers = Array.isArray(usersRes.data.data) ? usersRes.data.data : (Array.isArray(usersRes.data) ? usersRes.data : []);
    const allProperties = Array.isArray(propertiesRes.data.data) ? propertiesRes.data.data : (Array.isArray(propertiesRes.data) ? propertiesRes.data : []);
    const allCommunities = Array.isArray(communitiesRes.data) ? communitiesRes.data : [];

    // Estatísticas gerais
    stats.value.users = allUsers.length;
    stats.value.properties = allProperties.length;
    stats.value.rented = allProperties.filter(p => p && p.status === 'rented').length;
    stats.value.communities = allCommunities.length;

    // Estatísticas de usuários por tipo
    stats.value.owners = allUsers.filter(u => u && u.role === 'owner').length;
    stats.value.tenants = allUsers.filter(u => u && u.role === 'tenant').length;

    // Usuários registrados este mês
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    stats.value.ownersThisMonth = allUsers.filter(u =>
      u && u.role === 'owner' && new Date(u.created_at) >= firstDayOfMonth
    ).length;
    stats.value.tenantsThisMonth = allUsers.filter(u =>
      u && u.role === 'tenant' && new Date(u.created_at) >= firstDayOfMonth
    ).length;

    // Estatísticas de visitantes do analytics
    if (analyticsRes.data && analyticsRes.data.summary) {
      stats.value.totalVisitors = analyticsRes.data.summary.total_visitors || 0;
      stats.value.uniqueVisitors = analyticsRes.data.summary.unique_visitors || 0;
    }

    // Calcular visitantes de hoje
    const todayStart = new Date();
    todayStart.setHours(0, 0, 0, 0);

    try {
      const todayAnalyticsRes = await axios.get('/api/admin/analytics?period=1');
      if (todayAnalyticsRes.data && todayAnalyticsRes.data.summary) {
        stats.value.visitorsToday = todayAnalyticsRes.data.summary.total_visitors || 0;
        stats.value.uniqueVisitorsToday = todayAnalyticsRes.data.summary.unique_visitors || 0;
      }
    } catch (error) {
      console.warn('Erro ao carregar estatísticas de hoje:', error);
    }

    recentUsers.value = allUsers.slice(0, 5);
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error);
    // Garantir valores padrão em caso de erro
    stats.value = {
      users: 0,
      properties: 0,
      rented: 0,
      communities: 0,
      totalVisitors: 0,
      uniqueVisitors: 0,
      visitorsToday: 0,
      uniqueVisitorsToday: 0,
      owners: 0,
      tenants: 0,
      ownersThisMonth: 0,
      tenantsThisMonth: 0
    };
  }
};

const loadOwners = async () => {
  const response = await axios.get('/api/users');
  const allUsers = Array.isArray(response.data.data) ? response.data.data : (Array.isArray(response.data) ? response.data : []);
  owners.value = allUsers.filter(u => u && u.role === 'owner');
};

const loadTenants = async () => {
  const response = await axios.get('/api/users');
  const allUsers = Array.isArray(response.data.data) ? response.data.data : (Array.isArray(response.data) ? response.data : []);
  tenants.value = allUsers.filter(u => u && u.role === 'tenant');
};

const loadProperties = async () => {
  const response = await axios.get('/api/properties');
  const allProperties = Array.isArray(response.data.data) ? response.data.data : (Array.isArray(response.data) ? response.data : []);
  properties.value = allProperties;
};

const loadCommunities = async () => {
  const response = await axios.get('/api/communities');
  communities.value = Array.isArray(response.data) ? response.data : [];
};

const loadLogs = async () => {
  try {
    const [visitorsRes, registrationsRes] = await Promise.all([
      axios.get('/api/admin/visitor-logs'),
      axios.get('/api/admin/registration-logs')
    ]);

    // A API retorna dados paginados: { data: [...], current_page, per_page, total }
    // Extrair o array de dados de dentro da resposta paginada
    const visitorsData = visitorsRes.data?.data || visitorsRes.data || [];
    const registrationsData = registrationsRes.data?.data || registrationsRes.data || [];

    // Garantir que sempre sejam arrays
    visitorLogs.value = Array.isArray(visitorsData) ? visitorsData : [];
    registrationLogs.value = Array.isArray(registrationsData) ? registrationsData : [];

    // Filtrar itens válidos
    visitorLogs.value = visitorLogs.value.filter(log => log && log.id);
    registrationLogs.value = registrationLogs.value.filter(log => log && log.id);

    console.log('Visitor logs carregados:', visitorLogs.value.length);
    console.log('Registration logs carregados:', registrationLogs.value.length);
  } catch (error) {
    console.error('Erro ao carregar logs:', error);
    // Em caso de erro, garantir arrays vazios
    visitorLogs.value = [];
    registrationLogs.value = [];
  }
};

const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrador',
    owner: 'Proprietário',
    tenant: 'Inquilino'
  };
  return labels[role] || role;
};

const getRoleBadgeClass = (role) => {
  const classes = {
    admin: 'bg-red-100 text-red-800',
    owner: 'bg-blue-100 text-blue-800',
    tenant: 'bg-green-100 text-green-800'
  };
  return classes[role] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
  const labels = {
    available: 'Disponível',
    rented: 'Alugado',
    maintenance: 'Manutenção'
  };
  return labels[status] || status;
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

const openCreateUserModal = (role) => {
  // Navegar para a página específica de owners ou tenants onde o modal será aberto
  if (role === 'owner') {
    router.push({ name: 'AdminOwnerList' });
    // O modal será aberto pela rota AdminOwnerCreate se necessário
    setTimeout(() => {
      router.push({ name: 'AdminOwnerCreate' });
    }, 100);
  } else {
    router.push({ name: 'AdminTenantList' });
    setTimeout(() => {
      router.push({ name: 'AdminTenantCreate' });
    }, 100);
  }
};

const openCreateCommunityModal = () => {
  router.push({ name: 'AdminCommunityCreate' });
};

const handleEditUser = (user) => {
  if (user.role === 'owner') {
    router.push({ name: 'AdminOwnerEdit', params: { id: user.id } });
  } else if (user.role === 'tenant') {
    router.push({ name: 'AdminTenantEdit', params: { id: user.id } });
  }
};

const handleDeleteUser = async (user) => {
  if (confirm(`Tem certeza que deseja excluir ${user.name}?`)) {
    try {
      await axios.delete(`/api/users/${user.id}`);
      loadViewData();
    } catch (error) {
      console.error('Erro ao excluir usuário:', error);
      alert('Erro ao excluir usuário');
    }
  }
};

const handleEditCommunity = (community) => {
  router.push({ name: 'AdminCommunityEdit', params: { id: community.id } });
};

const handleDeleteCommunity = async (community) => {
  if (confirm(`Tem certeza que deseja excluir ${community.name}?`)) {
    try {
      await axios.delete(`/api/communities/${community.id}`);
      loadViewData();
    } catch (error) {
      console.error('Erro ao excluir comunidade:', error);
      alert('Erro ao excluir comunidade');
    }
  }
};

const viewProperty = (property) => {
  router.push({ name: 'PropertyDetail', params: { id: property.id } });
};

const editProperty = (property) => {
  alert(`Editar imóvel ${property.title} - Em desenvolvimento`);
};

const deleteProperty = async (property) => {
  if (confirm(`Tem certeza que deseja excluir ${property.title}?`)) {
    try {
      await axios.delete(`/api/properties/${property.id}`);
      loadViewData();
    } catch (error) {
      console.error('Erro ao excluir imóvel:', error);
      alert('Erro ao excluir imóvel');
    }
  }
};

const updateProfile = async () => {
  try {
    await axios.put('/api/profile', profileForm.value);
    const response = await axios.get('/api/me');
    authStore.user = response.data;
    alert('Perfil atualizado com sucesso!');
  } catch (error) {
    console.error('Erro ao atualizar perfil:', error);
    alert('Erro ao atualizar perfil');
  }
};

onMounted(async () => {
  await authStore.checkAuth();
  profileForm.value = {
    name: authStore.user?.name || '',
    email: authStore.user?.email || '',
    phone: authStore.user?.phone || ''
  };
  loadViewData();
});
</script>
