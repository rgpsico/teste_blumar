<template>
  <div class="space-y-6">
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
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const visitorLogsColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'ip_address', label: 'IP', format: (value) => value || '-' },
  {
    key: 'user_agent',
    label: 'Navegador',
    format: (value) => (value ? value.substring(0, 50) + (value.length > 50 ? '...' : '') : '-')
  },
  { key: 'created_at', label: 'Data', format: (value) => (value ? new Date(value).toLocaleString('pt-BR') : '-') }
];

const registrationLogsColumns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'user.name', label: 'Usuário', format: (value, item) => item.user?.name || '-' },
  { key: 'user.email', label: 'Email', format: (value, item) => item.user?.email || '-' },
  { key: 'user.role', label: 'Tipo', format: (value, item) => (item.user?.role ? getRoleLabel(item.user.role) : '-') },
  { key: 'created_at', label: 'Data', format: (value) => (value ? new Date(value).toLocaleString('pt-BR') : '-') }
];

const visitorLogs = ref([]);
const registrationLogs = ref([]);
const loading = ref(false);

const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrador',
    owner: 'Proprietário',
    tenant: 'Inquilino'
  };
  return labels[role] || role;
};

const loadLogs = async () => {
  loading.value = true;
  try {
    const [visitorsRes, registrationsRes] = await Promise.all([
      axios.get('/api/admin/visitor-logs'),
      axios.get('/api/admin/registration-logs')
    ]);

    const visitorsData = visitorsRes.data?.data || visitorsRes.data || [];
    const registrationsData = registrationsRes.data?.data || registrationsRes.data || [];

    visitorLogs.value = Array.isArray(visitorsData) ? visitorsData : [];
    registrationLogs.value = Array.isArray(registrationsData) ? registrationsData : [];

    visitorLogs.value = visitorLogs.value.filter((log) => log && log.id);
    registrationLogs.value = registrationLogs.value.filter((log) => log && log.id);
  } catch (error) {
    console.error('Erro ao carregar logs:', error);
    visitorLogs.value = [];
    registrationLogs.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadLogs();
});
</script>
