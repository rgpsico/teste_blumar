<template>
  <div class="space-y-6">
    <DataTable
      title="Comunidades"
      subtitle="Gerencie todas as comunidades do sistema"
      :columns="columns"
      :data="communities"
      :actions="tableActions"
      :loading="loading"
      :pagination="pagination || undefined"
      @create="openCreateCommunityModal"
      @edit="handleEditCommunity"
      @delete="handleDeleteCommunity"
      @page-change="handlePageChange"
    />

    <Transition name="modal">
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        @click.self="closeModal"
      >
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6">
          <div class="flex items-start justify-between mb-6">
            <div>
              <p class="text-sm text-gray-500">{{ isEditing ? 'Edite os dados' : 'Cadastre uma nova comunidade' }}</p>
              <h3 class="text-2xl font-bold text-gray-900">{{ isEditing ? 'Editar comunidade' : 'Nova comunidade' }}</h3>
            </div>
            <button @click="closeModal" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <form @submit.prevent="saveCommunity" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nome *</label>
                <input
                  v-model="form.name"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Cidade</label>
                <input
                  v-model="form.city"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                <input
                  v-model="form.state"
                  maxlength="2"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent uppercase"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descricao</label>
                <input
                  v-model="form.description"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">CEP</label>
                <input
                  v-model="form.zip_code"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ativo</label>
                <div class="flex items-center gap-2 mt-2">
                  <input v-model="form.active" type="checkbox" class="w-4 h-4 text-purple-600 rounded" />
                  <span class="text-sm text-gray-700">Comunidade ativa</span>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Imagem (upload)</label>
              <div class="flex items-center gap-4">
                <input
                  type="file"
                  accept="image/*"
                  @change="handleImageUpload"
                  class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                />
                <div
                  v-if="imagePreview"
                  class="w-16 h-16 rounded-xl overflow-hidden border border-purple-100 shadow-sm"
                >
                  <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="closeModal" class="px-5 py-2 border rounded-lg hover:bg-gray-50">
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg disabled:opacity-60"
              >
                {{ saving ? 'Salvando...' : 'Salvar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome', cellClass: 'font-semibold text-gray-900' },
  { key: 'description', label: 'Descricao' }
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
const saving = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const pagination = ref(null);
const imagePreview = ref('');

const form = ref({
  id: null,
  name: '',
  description: '',
  city: '',
  state: '',
  zip_code: '',
  image: '',
  active: true
});

const buildPagination = (payload, items) => {
  const meta = payload?.meta || payload;
  if (!meta || meta.current_page === undefined) return null;

  return {
    current_page: meta.current_page,
    last_page: meta.last_page || meta.lastPage || 1,
    from: meta.from ?? 1,
    to: meta.to ?? items.length,
    total: meta.total ?? items.length
  };
};

const loadCommunities = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/communities', { params: { page } });
    const payload = response.data ?? {};
    const items = Array.isArray(payload.data)
      ? payload.data
      : Array.isArray(payload)
        ? payload
        : Array.isArray(payload.data?.data)
          ? payload.data.data
          : [];

    communities.value = items;
    pagination.value = buildPagination(payload, items);
  } catch (error) {
    console.error('Erro ao carregar comunidades:', error);
    alert('Nao foi possivel carregar as comunidades');
  } finally {
    loading.value = false;
  }
};

const openCreateCommunityModal = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    name: '',
    description: '',
    city: '',
    state: '',
    zip_code: '',
    image: '',
    active: true
  };
  imagePreview.value = '';
  showModal.value = true;
};

const handleEditCommunity = (community) => {
  isEditing.value = true;
  form.value = { ...community };
  imagePreview.value = community.image ? `/storage/${community.image}` : '';
  showModal.value = true;
};

const handleDeleteCommunity = async (community) => {
  if (confirm(`Tem certeza que deseja excluir ${community.name}?`)) {
    try {
      await axios.delete(`/api/communities/${community.id}`);
      loadCommunities(pagination.value?.current_page || 1);
    } catch (error) {
      console.error('Erro ao excluir comunidade:', error);
      alert('Erro ao excluir comunidade');
    }
  }
};

const handlePageChange = (page) => {
  if (page < 1) return;
  loadCommunities(page);
};

const closeModal = () => {
  showModal.value = false;
};

const handleImageUpload = (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    form.value.image = e.target?.result;
    imagePreview.value = e.target?.result || '';
  };
  reader.readAsDataURL(file);
};

const saveCommunity = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/communities/${form.value.id}`, form.value);
    } else {
      await axios.post('/api/communities', form.value);
    }
    closeModal();
    loadCommunities(pagination.value?.current_page || 1);
  } catch (error) {
    console.error('Erro ao salvar comunidade:', error);
    alert('Erro ao salvar comunidade');
  } finally {
    saving.value = false;
  }
};

onMounted(() => {
  loadCommunities();
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
