<template>
  <div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 space-y-6">
      <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <p class="text-sm text-gray-500">Painel do Super Admin</p>
          <h1 class="text-2xl font-bold text-gray-900">Inquilinos</h1>
          <p class="text-sm text-gray-500">Listagem completa de inquilinos com acesso rápido para criar ou editar registros.</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <router-link
            to="/admin"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:text-blue-600 hover:border-blue-200 shadow-sm"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar ao Dashboard
          </router-link>
          <button
            @click="goToCreate"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-sm hover:shadow-md"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Inquilino
          </button>
        </div>
      </header>

      <DataTable
        title="Lista de Inquilinos"
        subtitle="Gerencie, edite ou exclua inquilinos do sistema"
        :columns="columns"
        :data="tenants"
        :actions="actions"
        :loading="loading"
        create-button-text="Adicionar Inquilino"
        @create="goToCreate"
        @edit="editTenant"
        @delete="deleteTenant"
      >
        <template #cell-name="{ item }">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center font-semibold">
              {{ item.name?.[0]?.toUpperCase() || '?' }}
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ item.name }}</p>
              <p class="text-xs text-gray-500">{{ item.email }}</p>
            </div>
          </div>
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const router = useRouter();
const tenants = ref([]);
const loading = ref(false);

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome' },
  { key: 'phone', label: 'Telefone' },
  {
    key: 'created_at',
    label: 'Cadastrado em',
    format: (value) => (value ? new Date(value).toLocaleDateString('pt-BR') : '-')
  }
];

const actions = [
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

const loadTenants = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/users');
    const allUsers = Array.isArray(response.data?.data) ? response.data.data : Array.isArray(response.data) ? response.data : [];
    tenants.value = allUsers.filter((user) => user?.role === 'tenant');
  } catch (error) {
    console.error('Erro ao carregar inquilinos:', error);
    tenants.value = [];
  } finally {
    loading.value = false;
  }
};

const goToCreate = () => {
  router.push({ name: 'AdminTenantCreate' });
};

const editTenant = (tenant) => {
  router.push({ name: 'AdminTenantEdit', params: { id: tenant.id } });
};

const deleteTenant = async (tenant) => {
  if (confirm(`Deseja realmente excluir ${tenant.name}?`)) {
    try {
      await axios.delete(`/api/users/${tenant.id}`);
      await loadTenants();
    } catch (error) {
      console.error('Erro ao excluir inquilino:', error);
      alert('Não foi possível excluir o inquilino.');
    }
  }
};

onMounted(loadTenants);
</script>
