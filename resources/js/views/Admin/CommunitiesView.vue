<template>
  <DataTable
    title="Comunidades"
    subtitle="Gerencie todas as comunidades do sistema"
    :columns="columns"
    :data="communities"
    :actions="tableActions"
    :loading="loading"
    @create="openCreateCommunityModal"
    @edit="handleEditCommunity"
    @delete="handleDeleteCommunity"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const router = useRouter();

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome', cellClass: 'font-semibold text-gray-900' },
  { key: 'description', label: 'Descrição' }
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

const communities = ref([]);
const loading = ref(false);

const loadCommunities = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/communities');
    communities.value = Array.isArray(response.data?.data) ? response.data.data : [];
  } finally {
    loading.value = false;
  }
};

const openCreateCommunityModal = () => {
  router.push({ name: 'AdminCommunityCreate' });
};

const handleEditCommunity = (community) => {
  router.push({ name: 'AdminCommunityEdit', params: { id: community.id } });
};

const handleDeleteCommunity = async (community) => {
  if (confirm(`Tem certeza que deseja excluir ${community.name}?`)) {
    try {
      await axios.delete(`/api/communities/${community.id}`);
      loadCommunities();
    } catch (error) {
      console.error('Erro ao excluir comunidade:', error);
      alert('Erro ao excluir comunidade');
    }
  }
};

onMounted(() => {
  loadCommunities();
});
</script>
