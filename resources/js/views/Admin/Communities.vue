<template>
  <div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 space-y-6">
      <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <p class="text-sm text-gray-500">Painel do Super Admin</p>
          <h1 class="text-2xl font-bold text-gray-900">Comunidades</h1>
          <p class="text-sm text-gray-500">Gerencie comunidades e mantenha os dados sempre atualizados.</p>
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
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg shadow-sm hover:shadow-md"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova Comunidade
          </button>
        </div>
      </header>

      <DataTable
        title="Lista de Comunidades"
        subtitle="Cadastre, edite ou remova comunidades"
        :columns="columns"
        :data="communities"
        :actions="actions"
        :loading="loading"
        create-button-text="Adicionar Comunidade"
        @create="goToCreate"
        @edit="editCommunity"
        @delete="deleteCommunity"
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const router = useRouter();
const communities = ref([]);
const loading = ref(false);

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome', cellClass: 'font-semibold text-gray-900' },
  { key: 'description', label: 'Descrição' }
];

const actions = [
  {
    name: 'edit',
    label: 'Editar',
    event: 'edit',
    icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    class: 'text-purple-600 hover:bg-purple-50'
  },
  {
    name: 'delete',
    label: 'Excluir',
    event: 'delete',
    icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
    class: 'text-red-600 hover:bg-red-50'
  }
];

const loadCommunities = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/communities');
    communities.value = Array.isArray(response.data) ? response.data : response.data?.data || [];
  } catch (error) {
    console.error('Erro ao carregar comunidades:', error);
    communities.value = [];
  } finally {
    loading.value = false;
  }
};

const goToCreate = () => {
  router.push({ name: 'AdminCommunityCreate' });
};

const editCommunity = (community) => {
  router.push({ name: 'AdminCommunityEdit', params: { id: community.id } });
};

const deleteCommunity = async (community) => {
  if (confirm(`Deseja realmente excluir ${community.name}?`)) {
    try {
      await axios.delete(`/api/communities/${community.id}`);
      await loadCommunities();
    } catch (error) {
      console.error('Erro ao excluir comunidade:', error);
      alert('Não foi possível excluir a comunidade.');
    }
  }
};

onMounted(loadCommunities);
</script>
