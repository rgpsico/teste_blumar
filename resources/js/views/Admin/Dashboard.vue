<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">
    <Sidebar :isOpen="sidebarOpen" :currentView="currentView" @toggle="sidebarOpen = false" @navigate="handleNavigate" />

    <div class="flex-1 flex flex-col min-w-0" :class="{ 'lg:ml-64': true }">
      <Header
        :title="pageTitle"
        :subtitle="pageSubtitle"
        :user="authStore.user"
        :notificationCount="unreadNotifications"
        @toggle-sidebar="sidebarOpen = !sidebarOpen"
        @navigate="handleNavigate"
        @logout="handleLogout"
      />

      <main class="flex-1 overflow-auto p-6">
        <DashboardOverview v-if="currentView === 'dashboard'" @navigate="handleNavigate" />
        <OwnersView v-else-if="currentView === 'owners'" />
        <TenantsView v-else-if="currentView === 'tenants'" />
        <PropertiesView v-else-if="currentView === 'properties'" />
        <CommunitiesView v-else-if="currentView === 'communities'" />
        <LogsView v-else-if="currentView === 'logs'" />
        <SettingsView v-else-if="currentView === 'settings'" />
        <ProfileView v-else-if="currentView === 'profile'" />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import Sidebar from '../../components/Admin/Sidebar.vue';
import Header from '../../components/Admin/Header.vue';
import DashboardOverview from './DashboardOverview.vue';
import OwnersView from './OwnersView.vue';
import TenantsView from './TenantsView.vue';
import PropertiesView from './PropertiesView.vue';
import CommunitiesView from './CommunitiesView.vue';
import LogsView from './LogsView.vue';
import SettingsView from './SettingsView.vue';
import ProfileView from './ProfileView.vue';

const router = useRouter();
const authStore = useAuthStore();

const sidebarOpen = ref(false);
const currentView = ref('dashboard');
const unreadNotifications = ref(0);

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

const handleNavigate = (view) => {
  currentView.value = view;
  sidebarOpen.value = false;
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

onMounted(async () => {
  await authStore.checkAuth();
});
</script>
