<template>
  <DataTable
    title="Inquilinos"
    subtitle="Gerencie todos os inquilinos do sistema"
    :columns="columns"
    :data="tenants"
    :actions="tableActions"
    :loading="loading"
    @create="openCreateUserModal"
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
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const router = useRouter();

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome' },
  { key: 'phone', label: 'Telefone' },
  {
    key: 'created_at',
    label: 'Cadastrado em',
    format: (value) => new Date(value).toLocaleDateString('pt-BR')
  }
];

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

const tenants = ref([]);
const loading = ref(false);

const loadTenants = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/users');
    const allUsers = Array.isArray(response.data?.data) ? response.data.data : Array.isArray(response.data) ? response.data : [];
    tenants.value = allUsers.filter((u) => u && u.role === 'tenant');
  } finally {
    loading.value = false;
  }
};

const openCreateUserModal = () => {
  router.push({ name: 'AdminTenantList' });
  setTimeout(() => {
    router.push({ name: 'AdminTenantCreate' });
  }, 100);
};

const handleEditUser = (user) => {
  router.push({ name: 'AdminTenantEdit', params: { id: user.id } });
};

const handleDeleteUser = async (user) => {
  if (confirm(`Tem certeza que deseja excluir ${user.name}?`)) {
    try {
      await axios.delete(`/api/users/${user.id}`);
      loadTenants();
    } catch (error) {
      console.error('Erro ao excluir usuário:', error);
      alert('Erro ao excluir usuário');
    }
  }
};

onMounted(() => {
  loadTenants();
});
</script>
