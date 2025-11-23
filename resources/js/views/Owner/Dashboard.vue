<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="text-2xl font-bold text-blue-600">Dashboard Proprietário</div>
          <div class="flex space-x-4 items-center">
            <span class="text-gray-600">{{ authStore.user?.name }}</span>
            <router-link to="/" class="text-gray-700 hover:text-blue-600">
              Home
            </router-link>
            <button @click="handleLogout" class="text-gray-700 hover:text-blue-600">
              Sair
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Meus Imóveis</h1>
        <button
          @click="showAddModal = true"
          class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600"
        >
          Adicionar Imóvel
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-gray-500 text-sm font-semibold mb-2">Total de Imóveis</h3>
          <div class="text-3xl font-bold text-blue-600">{{ properties.length }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-gray-500 text-sm font-semibold mb-2">Disponíveis</h3>
          <div class="text-3xl font-bold text-green-600">
            {{ properties.filter((p) => p.status === 'available').length }}
          </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-gray-500 text-sm font-semibold mb-2">Alugados</h3>
          <div class="text-3xl font-bold text-purple-600">
            {{ properties.filter((p) => p.status === 'rented').length }}
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Lista de Imóveis</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="property in properties"
            :key="property.id"
            class="border rounded-lg p-4 hover:shadow-md transition"
          >
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-bold flex-1">{{ property.title }}</h3>
              <button
                @click="deleteProperty(property.id)"
                class="text-red-500 hover:text-red-700 text-sm"
              >
                Excluir
              </button>
            </div>
            <p class="text-sm text-gray-600 mb-2">
              {{ property.city }}, {{ property.state }}
            </p>
            <p class="text-sm text-gray-700 mb-3 line-clamp-2">
              {{ property.description }}
            </p>
            <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
              <span>{{ property.bedrooms }} quartos</span>
              <span>{{ property.bathrooms }} banheiros</span>
              <span>{{ property.area }}m²</span>
            </div>
            <div class="text-lg font-bold text-blue-600 mb-2">
              R$ {{ formatPrice(property.price) }}
            </div>
            <div class="flex items-center justify-between">
              <span
                class="px-2 py-1 rounded text-xs"
                :class="{
                  'bg-green-100 text-green-800': property.status === 'available',
                  'bg-purple-100 text-purple-800': property.status === 'rented',
                  'bg-yellow-100 text-yellow-800': property.status === 'maintenance',
                }"
              >
                {{ getStatusLabel(property.status) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="showAddModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
      @click.self="showAddModal = false"
    >
      <div class="bg-white rounded-lg p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">Adicionar Novo Imóvel</h2>
        <form @submit.prevent="addProperty">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Título</label>
              <input
                v-model="newProperty.title"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Descrição</label>
              <textarea
                v-model="newProperty.description"
                class="w-full px-3 py-2 border rounded-md"
                rows="3"
                required
              ></textarea>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Endereço</label>
              <input
                v-model="newProperty.address"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Cidade</label>
              <input
                v-model="newProperty.city"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Estado</label>
              <input
                v-model="newProperty.state"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">CEP</label>
              <input
                v-model="newProperty.zip_code"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Preço</label>
              <input
                v-model="newProperty.price"
                type="number"
                step="0.01"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Quartos</label>
              <input
                v-model="newProperty.bedrooms"
                type="number"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Banheiros</label>
              <input
                v-model="newProperty.bathrooms"
                type="number"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Área (m²)</label>
              <input
                v-model="newProperty.area"
                type="number"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
          </div>

          <div class="flex justify-end space-x-4 mt-6">
            <button
              type="button"
              @click="showAddModal = false"
              class="px-4 py-2 border rounded-md hover:bg-gray-50"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
            >
              Adicionar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import axios from 'axios';

const router = useRouter();
const authStore = useAuthStore();

const properties = ref([]);
const showAddModal = ref(false);
const newProperty = ref({
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
});

const loadProperties = async () => {
  try {
    const response = await axios.get('/api/properties');
    properties.value = response.data.data || response.data;
  } catch (error) {
    console.error('Erro ao carregar imóveis:', error);
  }
};

const addProperty = async () => {
  try {
    await axios.post('/api/properties', newProperty.value);
    showAddModal.value = false;
    newProperty.value = {
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
    };
    loadProperties();
  } catch (error) {
    console.error('Erro ao adicionar imóvel:', error);
    alert('Erro ao adicionar imóvel');
  }
};

const deleteProperty = async (id) => {
  if (confirm('Tem certeza que deseja excluir este imóvel?')) {
    try {
      await axios.delete(`/api/properties/${id}`);
      loadProperties();
    } catch (error) {
      console.error('Erro ao excluir imóvel:', error);
      alert('Erro ao excluir imóvel');
    }
  }
};

const getStatusLabel = (status) => {
  const labels = {
    available: 'Disponível',
    rented: 'Alugado',
    maintenance: 'Manutenção',
  };
  return labels[status] || status;
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price);
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

onMounted(() => {
  loadProperties();
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
