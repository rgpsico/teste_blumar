<template>
  <div class="space-y-6">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full">
        <div>
          <label class="text-sm text-gray-600 mb-1 block">Status</label>
          <select v-model="filters.status" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">Todos</option>
            <option value="available">Disponivel</option>
            <option value="rented">Alugado</option>
            <option value="maintenance">Manutencao</option>
            <option value="inactive">Inativo</option>
          </select>
        </div>
        <div>
          <label class="text-sm text-gray-600 mb-1 block">Comunidade</label>
          <select v-model="filters.community_id" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">Todas</option>
            <option v-for="community in communities" :key="community.id" :value="community.id">
              {{ community.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="text-sm text-gray-600 mb-1 block">Proprietario</label>
          <select v-model="filters.owner_id" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">Todos</option>
            <option v-for="owner in owners" :key="owner.id" :value="owner.id">
              {{ owner.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button @click="clearFilters" class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-50">
          Limpar filtros
        </button>
        <button
          type="button"
          @click="openCreateModal"
          class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Novo imóvel
        </button>
      </div>
    </div>

    <DataTable
      title="Imóveis"
      subtitle="Gerencie todos os imóveis do sistema"
      :columns="columns"
      :data="filteredProperties"
      :actions="actions"
      :loading="loading"
      @view="viewProperty"
      @edit="handleEditProperty"
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
            'bg-yellow-100 text-yellow-800': value === 'maintenance',
            'bg-gray-200 text-gray-800': value === 'inactive'
          }"
          class="px-3 py-1 rounded-full text-xs font-semibold"
        >
          {{ getStatusLabel(value) }}
        </span>
      </template>
    </DataTable>

    <Transition name="modal">
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        @click.self="closeModal"
      >
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-3xl w-full p-6 max-h-[90vh] overflow-y-auto">
          <div class="flex items-start justify-between mb-6">
            <div>
              <p class="text-sm text-gray-500">{{ isEditing ? 'Edite os dados do imóvel' : 'Cadastre um novo imóvel' }}</p>
              <h3 class="text-2xl font-bold text-gray-900">{{ isEditing ? 'Editar imóvel' : 'Novo imóvel' }}</h3>
            </div>
            <button @click="closeModal" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <form @submit.prevent="saveProperty" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Título *</label>
                <input
                  v-model="form.title"
                  required
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Preço (R$) *</label>
                <input
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">CEP</label>
                <input
                  v-model="form.zip_code"
                  inputmode="numeric"
                  maxlength="8"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="zipLoading" class="text-xs text-blue-600 mt-1">Buscando endereco pelo CEP...</p>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Endereco *</label>
                <input
                  v-model="form.address"
                  required
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Cidade *</label>
                <input
                  v-model="form.city"
                  required
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Estado *</label>
                <input
                  v-model="form.state"
                  maxlength="2"
                  required
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Quartos</label>
                <input
                  v-model.number="form.bedrooms"
                  type="number"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Banheiros</label>
                <input
                  v-model.number="form.bathrooms"
                  type="number"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Área (m²)</label>
                <input
                  v-model.number="form.area"
                  type="number"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Proprietário *</label>
                <select
                  v-model="form.owner_id"
                  required
                  class="w-full border rounded-lg px-3 py-2"
                >
                  <option :value="null">Selecionar</option>
                  <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                    {{ owner.name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Comunidade</label>
                <select
                  v-model="form.community_id"
                  class="w-full border rounded-lg px-3 py-2"
                >
                  <option :value="null">Selecionar</option>
                  <option v-for="community in communities" :key="community.id" :value="community.id">
                    {{ community.name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <select v-model="form.status" class="w-full border rounded-lg px-3 py-2">
                  <option value="available">Disponivel</option>
                  <option value="rented">Alugado</option>
                  <option value="maintenance">Manutencao</option>
                  <option value="inactive">Inativo</option>
                </select>
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="closeModal" class="px-5 py-2 border rounded-lg hover:bg-gray-50">
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-lg disabled:opacity-60"
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
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const router = useRouter();

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'title', label: 'Imóvel' },
  {
    key: 'price',
    label: 'Preço',
    format: (value) => `R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(value || 0)}`
  },
  { key: 'status', label: 'Status' },
  { key: 'owner.name', label: 'Proprietário' }
];

const actions = [
  {
    name: 'view',
    label: 'Visualizar',
    event: 'view',
    icon: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
    class: 'text-blue-600 hover:bg-blue-50'
  },
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

const properties = ref([]);
const communities = ref([]);
const tenants = ref([]);
const owners = ref([]);
const currentUser = ref(null);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const filters = ref({
  status: '',
  community_id: '',
  tenant_id: '',
  owner_id: ''
});

const form = ref({
  title: '',
  description: '',
  address: '',
  city: '',
  state: '',
  zip_code: '',
  price: '',
  bedrooms: '',
  bathrooms: '',
  area: '',
  community_id: null,
  tenant_id: null,
  owner_id: null,
  status: 'available',
  photos: []
});
const zipLoading = ref(false);
const lastZipFetched = ref('');

const clearFilters = () => {
  filters.value = {
    status: '',
    community_id: '',
    tenant_id: '',
    owner_id: ''
  };
};

const getStatusLabel = (status) => {
  const labels = {
    available: 'Dispon?vel',
    rented: 'Alugado',
    maintenance: 'Manuten??o',
    inactive: 'Inativo'
  };
  return labels[status] || status;

};

const loadProperties = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/properties');
    const payload = response.data ?? {};
    const list = Array.isArray(payload.data)
      ? payload.data
      : Array.isArray(payload)
        ? payload
        : Array.isArray(payload.data?.data)
          ? payload.data.data
          : [];
    properties.value = list.filter(Boolean);
  } finally {
    loading.value = false;
  }
};

const loadCurrentUser = async () => {
  try {
    const response = await axios.get('/api/user');
    currentUser.value = response.data || null;
  } catch (error) {
    console.error('Erro ao carregar usuário autenticado:', error);
    currentUser.value = null;
  }
};

const ensureDefaultOwner = () => {
  if (!form.value.owner_id && owners.value[0]) {
    form.value.owner_id = owners.value[0].id;
  }
};

const loadOwners = async () => {
  try {
    const response = await axios.get('/api/users');
    const allUsers = Array.isArray(response.data?.data)
      ? response.data.data
      : Array.isArray(response.data)
        ? response.data
        : [];
    const ownerList = allUsers.filter((u) => u && u.role === 'owner');
    owners.value = ownerList.length ? ownerList : currentUser.value ? [currentUser.value] : [];
  } catch (error) {
    console.error('Erro ao carregar proprietários:', error);
    owners.value = currentUser.value ? [currentUser.value] : [];
  } finally {
    ensureDefaultOwner();
  }
};

const loadCommunities = async () => {
  try {
    const response = await axios.get('/api/communities');
    const data = response.data?.data || response.data || [];
    communities.value = Array.isArray(data) ? data.filter((c) => c && c.id) : [];
  } catch (error) {
    console.error('Erro ao carregar comunidades:', error);
    communities.value = [];
  }
};

const loadTenants = async () => {
  try {
    const response = await axios.get('/api/users');
    const allUsers = Array.isArray(response.data?.data) ? response.data.data : Array.isArray(response.data) ? response.data : [];
    tenants.value = allUsers.filter((u) => u && u.role === 'tenant');
  } catch (error) {
    console.error('Erro ao carregar inquilinos:', error);
    tenants.value = [];
  }
};

const fetchAddressByZip = async (zip) => {
  const sanitized = (zip || '').replace(/\D/g, '').slice(0, 8);
  if (sanitized.length !== 8 || sanitized === lastZipFetched.value) return;

  zipLoading.value = true;
  lastZipFetched.value = sanitized;

  try {
    const { data } = await axios.get(`https://viacep.com.br/ws/${sanitized}/json/`);
    if (data && !data.erro) {
      form.value.address = data.logradouro || form.value.address;
      form.value.city = data.localidade || form.value.city;
      form.value.state = data.uf || form.value.state;
    }
  } catch (error) {
    console.error('Erro ao buscar CEP:', error);
  } finally {
    zipLoading.value = false;
  }
};

watch(
  () => form.value.zip_code,
  (zip) => {
    const digits = (zip || '').replace(/\D/g, '').slice(0, 8);
    if (digits !== (zip || '')) {
      form.value.zip_code = digits;
      return;
    }
    fetchAddressByZip(digits);
  }
);

const filteredProperties = computed(() => {
  return properties.value.filter((p) => {
    if (filters.value.status && p.status !== filters.value.status) return false;
    if (filters.value.community_id && String(p.community_id) !== String(filters.value.community_id)) return false;
    if (filters.value.tenant_id && String(p.tenant_id) !== String(filters.value.tenant_id)) return false;
    if (filters.value.owner_id && String(p.owner_id) !== String(filters.value.owner_id)) return false;
    return true;
  });
});

const resetForm = () => {
  form.value = {
    title: '',
    description: '',
    address: '',
    city: '',
    state: '',
    zip_code: '',
    price: '',
    bedrooms: '',
    bathrooms: '',
    area: '',
    community_id: null,
    tenant_id: null,
    owner_id: owners.value[0]?.id || null,
    status: 'available',
    photos: []
  };
  lastZipFetched.value = '';
};

const openCreateModal = () => {
  isEditing.value = false;
  editingId.value = null;
  resetForm();
  showModal.value = true;
};

const handleEditProperty = (property) => {
  isEditing.value = true;
  editingId.value = property.id;
  form.value = {
    title: property.title || '',
    description: property.description || '',
    address: property.address || '',
    city: property.city || '',
    state: property.state || '',
    zip_code: property.zip_code || '',
    price: property.price || '',
    bedrooms: property.bedrooms || '',
    bathrooms: property.bathrooms || '',
    area: property.area || '',
    community_id: property.community_id || null,
    tenant_id: property.tenant_id || null,
    owner_id: property.owner_id || null,
    status: property.status || 'available',
    photos: property.photos || []
  };
  lastZipFetched.value = (property.zip_code || '').replace(/\D/g, '').slice(0, 8);
  showModal.value = true;
};

const viewProperty = (property) => {
  router.push({ name: 'PropertyDetail', params: { id: property.id } });
};

const deleteProperty = async (property) => {
  if (confirm(`Tem certeza que deseja excluir ${property.title}?`)) {
    try {
      await axios.delete(`/api/properties/${property.id}`);
      loadProperties();
    } catch (error) {
      console.error('Erro ao excluir imóvel:', error);
      alert('Erro ao excluir imóvel');
    }
  }
};

const saveProperty = async () => {
  saving.value = true;
  try {
    const payload = { ...form.value, photos: form.value.photos || [] };
    if (!payload.owner_id) {
      delete payload.owner_id;
    }
    if (isEditing.value && editingId.value) {
      await axios.put(`/api/properties/${editingId.value}`, payload);
    } else {
      await axios.post('/api/properties', payload);
    }
    closeModal();
    loadProperties();
  } catch (error) {
    console.error('Erro ao salvar imóvel:', error);
    alert('Erro ao salvar imóvel');
  } finally {
    saving.value = false;
  }
};

const closeModal = () => {
  showModal.value = false;
};

onMounted(() => {
  loadCurrentUser().finally(async () => {
    await Promise.all([loadOwners(), loadProperties(), loadCommunities(), loadTenants()]);
  });
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
