<template>
  <div class="space-y-6">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
            <span :class="getRoleBadgeClass(user.role)" class="px-3 py-1 rounded-full text-xs font-semibold">
              {{ getRoleLabel(user.role) }}
            </span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Ações Rápidas</h3>
        <div class="space-y-2">
          <QuickAction label="Gerenciar Proprietários" color="blue" @click="() => emitNavigate('owners')" />
          <QuickAction label="Gerenciar Inquilinos" color="green" @click="() => emitNavigate('tenants')" />
          <QuickAction label="Gerenciar Imóveis" color="purple" @click="() => emitNavigate('properties')" />
          <QuickAction label="Ver Logs" color="gray" @click="() => emitNavigate('logs')" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

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

const QuickAction = {
  props: {
    label: String,
    color: {
      type: String,
      default: 'blue'
    }
  },
  emits: ['click'],
  template: `
    <button
      @click="$emit('click')"
      class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition text-left"
      :class="['bg-gradient-to-r', 'from-' + color + '-50', 'to-' + color + '-100', 'hover:from-' + color + '-100', 'hover:to-' + color + '-200']"
    >
      <svg class="w-5 h-5" :class="'text-' + color + '-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      <span class="text-sm font-medium" :class="'text-' + color + '-900'">{{ label }}</span>
    </button>
  `
};

const emit = defineEmits(['navigate']);

const stats = ref({
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
});

const recentUsers = ref([]);

const emitNavigate = (view) => emit('navigate', view);

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

const loadDashboardData = async () => {
  try {
    const [usersRes, propertiesRes, communitiesRes, analyticsRes] = await Promise.all([
      axios.get('/api/users'),
      axios.get('/api/properties'),
      axios.get('/api/communities'),
      axios.get('/api/admin/analytics?period=30')
    ]);

    const allUsers = Array.isArray(usersRes.data?.data) ? usersRes.data.data : Array.isArray(usersRes.data) ? usersRes.data : [];
    const allProperties = Array.isArray(propertiesRes.data?.data)
      ? propertiesRes.data.data
      : Array.isArray(propertiesRes.data)
        ? propertiesRes.data
        : [];
    const allCommunities = Array.isArray(communitiesRes.data?.data) ? communitiesRes.data.data : [];

    stats.value.users = allUsers.length;
    stats.value.properties = allProperties.length;
    stats.value.rented = allProperties.filter((p) => p && p.status === 'rented').length;
    stats.value.communities = allCommunities.length;

    stats.value.owners = allUsers.filter((u) => u && u.role === 'owner').length;
    stats.value.tenants = allUsers.filter((u) => u && u.role === 'tenant').length;

    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    stats.value.ownersThisMonth = allUsers.filter((u) => u && u.role === 'owner' && new Date(u.created_at) >= firstDayOfMonth).length;
    stats.value.tenantsThisMonth = allUsers.filter((u) => u && u.role === 'tenant' && new Date(u.created_at) >= firstDayOfMonth).length;

    if (analyticsRes.data?.summary) {
      stats.value.totalVisitors = analyticsRes.data.summary.total_visitors || 0;
      stats.value.uniqueVisitors = analyticsRes.data.summary.unique_visitors || 0;
    }

    try {
      const todayAnalyticsRes = await axios.get('/api/admin/analytics?period=1');
      if (todayAnalyticsRes.data?.summary) {
        stats.value.visitorsToday = todayAnalyticsRes.data.summary.total_visitors || 0;
        stats.value.uniqueVisitorsToday = todayAnalyticsRes.data.summary.unique_visitors || 0;
      }
    } catch (error) {
      console.warn('Erro ao carregar estatísticas de hoje:', error);
    }

    recentUsers.value = allUsers.slice(0, 5);
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error);
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

onMounted(() => {
  loadDashboardData();
});
</script>
