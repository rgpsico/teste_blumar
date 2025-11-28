<template>
  <div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
      <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
          <p class="text-sm text-gray-500">Monitoramento de novos usuarios</p>
          <h2 class="text-2xl font-bold text-gray-900">Cadastros por periodo</h2>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
          <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Data inicial</label>
            <input
              v-model="dateFrom"
              type="date"
              class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Data final</label>
            <input
              v-model="dateTo"
              type="date"
              class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          <button
            type="button"
            @click="clearFilters"
            class="px-4 py-2 border text-sm rounded-lg hover:bg-gray-50"
          >
            Limpar
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="stat in userStats"
          :key="stat.key"
          class="p-4 rounded-xl border bg-gradient-to-br from-gray-50 to-white shadow-sm flex flex-col gap-1"
        >
          <p class="text-sm text-gray-500">{{ stat.label }}</p>
          <div class="flex items-end justify-between">
            <span class="text-3xl font-bold text-gray-900">{{ stat.value }}</span>
            <span class="text-xs text-gray-500">{{ stat.hint }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
      <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
          <p class="text-sm text-gray-500">Monitoramento de visitantes</p>
          <h2 class="text-2xl font-bold text-gray-900">Visitas por periodo</h2>
        </div>
        <p class="text-sm text-gray-500">Usa o mesmo filtro de datas acima.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="stat in visitorStats"
          :key="stat.key"
          class="p-4 rounded-xl border bg-gradient-to-br from-gray-50 to-white shadow-sm flex flex-col gap-1"
        >
          <p class="text-sm text-gray-500">{{ stat.label }}</p>
          <div class="flex items-end justify-between">
            <span class="text-3xl font-bold text-gray-900">{{ stat.value }}</span>
            <span class="text-xs text-gray-500">{{ stat.hint }}</span>
          </div>
        </div>
      </div>
    </div>

    <DataTable
      title="Logs de Visitantes"
      subtitle="Acompanhe as visitas ao site"
      :columns="visitorLogsColumns"
      :data="filteredVisitorLogs"
      :loading="loading"
      :show-create-button="false"
    />

    <DataTable
      title="Logs de Registro"
      subtitle="Acompanhe os registros de novos usuarios"
      :columns="registrationLogsColumns"
      :data="filteredRegistrationLogs"
      :loading="loading"
      :show-create-button="false"
    />
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
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
  { key: 'user.name', label: 'Usuario', format: (value, item) => item.user?.name || '-' },
  { key: 'user.email', label: 'Email', format: (value, item) => item.user?.email || '-' },
  { key: 'user.role', label: 'Tipo', format: (value, item) => (item.user?.role ? getRoleLabel(item.user.role) : '-') },
  { key: 'created_at', label: 'Data', format: (value) => (value ? new Date(value).toLocaleString('pt-BR') : '-') }
];

const visitorLogs = ref([]);
const registrationLogs = ref([]);
const loading = ref(false);
const dateFrom = ref('');
const dateTo = ref('');

const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrador',
    owner: 'Proprietario',
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

    const visitorsData = visitorsRes.data?.data || visitorsRes.data.data || [];
    const registrationsData = registrationsRes.data?.data || registrationsRes.data.data || [];

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

const toDate = (value) => {
  const d = value ? new Date(value) : null;
  return d && !Number.isNaN(d.valueOf()) ? d : null;
};

const getLogDate = (log) => log?.created_at || log?.visited_at || log?.user?.created_at || null;

const withinRange = (log) => {
  const date = toDate(getLogDate(log));
  if (!date) return false;

  const start = dateFrom.value ? toDate(dateFrom.value) : null;
  const end = dateTo.value ? toDate(dateTo.value) : null;

  if (start && date < start) return false;
  if (end) {
    const endOfDay = new Date(end);
    endOfDay.setHours(23, 59, 59, 999);
    if (date > endOfDay) return false;
  }
  return true;
};

const filteredVisitorLogs = computed(() =>
  visitorLogs.value.filter((log) => withinRange(log))
);

const filteredRegistrationLogs = computed(() =>
  registrationLogs.value.filter((log) => withinRange(log))
);

const startOfWeek = (date) => {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day + (day === 0 ? -6 : 1);
  d.setDate(diff);
  d.setHours(0, 0, 0, 0);
  return d;
};

const userStats = computed(() => {
  const now = new Date();
  const dayStart = new Date(now);
  dayStart.setHours(0, 0, 0, 0);

  const weekStart = startOfWeek(now);

  const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
  monthStart.setHours(0, 0, 0, 0);

  const yearStart = new Date(now.getFullYear(), 0, 1);
  yearStart.setHours(0, 0, 0, 0);

  const counters = { day: 0, week: 0, month: 0, year: 0 };

  filteredRegistrationLogs.value.forEach((log) => {
    const date = toDate(getLogDate(log));
    if (!date) return;
    if (date >= dayStart) counters.day += 1;
    if (date >= weekStart) counters.week += 1;
    if (date >= monthStart) counters.month += 1;
    if (date >= yearStart) counters.year += 1;
  });

  return [
    { key: 'day', label: 'Hoje', value: counters.day, hint: '24h' },
    { key: 'week', label: 'Semana', value: counters.week, hint: 'Seg a Dom' },
    { key: 'month', label: 'Mes', value: counters.month, hint: 'Mes atual' },
    { key: 'year', label: 'Ano', value: counters.year, hint: 'Ano atual' }
  ];
});

const visitorStats = computed(() => {
  const now = new Date();
  const dayStart = new Date(now);
  dayStart.setHours(0, 0, 0, 0);

  const weekStart = startOfWeek(now);
  const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
  monthStart.setHours(0, 0, 0, 0);
  const yearStart = new Date(now.getFullYear(), 0, 1);
  yearStart.setHours(0, 0, 0, 0);

  const counters = { day: 0, week: 0, month: 0, year: 0 };

  filteredVisitorLogs.value.forEach((log) => {
    const date = toDate(getLogDate(log));
    if (!date) return;
    if (date >= dayStart) counters.day += 1;
    if (date >= weekStart) counters.week += 1;
    if (date >= monthStart) counters.month += 1;
    if (date >= yearStart) counters.year += 1;
  });

  return [
    { key: 'day', label: 'Hoje', value: counters.day, hint: '24h' },
    { key: 'week', label: 'Semana', value: counters.week, hint: 'Seg a Dom' },
    { key: 'month', label: 'Mes', value: counters.month, hint: 'Mes atual' },
    { key: 'year', label: 'Ano', value: counters.year, hint: 'Ano atual' }
  ];
});

const clearFilters = () => {
  dateFrom.value = '';
  dateTo.value = '';
};

onMounted(() => {
  loadLogs();
});
</script>
