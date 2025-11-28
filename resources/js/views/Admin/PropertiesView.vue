<template>
  <DataTable
    title="Imóveis"
    subtitle="Gerencie todos os imóveis do sistema"
    :columns="columns"
    :data="properties"
    :actions="actions"
    :loading="loading"
    @view="viewProperty"
    @edit="editProperty"
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
          'bg-yellow-100 text-yellow-800': value === 'maintenance'
        }"
        class="px-3 py-1 rounded-full text-xs font-semibold"
      >
        {{ getStatusLabel(value) }}
      </span>
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
  { key: 'title', label: 'Imóvel' },
  {
    key: 'price',
    label: 'Preço',
    format: (value) => `R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(value)}`
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
const loading = ref(false);

const getStatusLabel = (status) => {
  const labels = {
    available: 'Disponível',
    rented: 'Alugado',
    maintenance: 'Manutenção'
  };
  return labels[status] || status;
};

const loadProperties = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/properties');
    const allProperties = Array.isArray(response.data?.data)
      ? response.data.data
      : Array.isArray(response.data)
        ? response.data
        : [];
    properties.value = allProperties;
  } finally {
    loading.value = false;
  }
};

const viewProperty = (property) => {
  router.push({ name: 'PropertyDetail', params: { id: property.id } });
};

const editProperty = (property) => {
  alert(`Editar imóvel ${property.title} - Em desenvolvimento`);
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

onMounted(() => {
  loadProperties();
});
</script>
