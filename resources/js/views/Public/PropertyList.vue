<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="text-2xl font-bold text-blue-600">Imóveis</div>
          <div class="flex space-x-4">
            <template v-if="authStore.isAuthenticated">
              <router-link
                v-if="authStore.isAdmin"
                to="/admin"
                class="text-gray-700 hover:text-blue-600"
              >
                Dashboard
              </router-link>
              <router-link
                v-if="authStore.isOwner"
                to="/owner"
                class="text-gray-700 hover:text-blue-600"
              >
                Dashboard
              </router-link>
              <router-link
                v-if="authStore.isTenant"
                to="/tenant"
                class="text-gray-700 hover:text-blue-600"
              >
                Dashboard
              </router-link>
              <button
                @click="handleLogout"
                class="text-gray-700 hover:text-blue-600"
              >
                Sair
              </button>
            </template>
            <template v-else>
              <router-link to="/login" class="text-gray-700 hover:text-blue-600">
                Login
              </router-link>
              <router-link
                to="/register"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
              >
                Registrar
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold mb-8">Imóveis Disponíveis</h1>

      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-500">Carregando imóveis...</p>
      </div>

      <div v-else-if="properties.length === 0" class="text-center py-12">
        <p class="text-gray-500">Nenhum imóvel disponível no momento.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="property in properties"
          :key="property.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition cursor-pointer"
          @click="goToProperty(property.id)"
        >
          <div class="h-48 bg-gray-300 relative">
            <img
              v-if="property.photos && property.photos[0]"
              :src="property.photos[0]"
              :alt="property.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="flex items-center justify-center h-full">
              <span class="text-gray-500">Sem foto</span>
            </div>
          </div>

          <div class="p-4">
            <h3 class="font-bold text-lg mb-2">{{ property.title }}</h3>
            <p class="text-gray-600 text-sm mb-2">{{ property.city }}, {{ property.state }}</p>
            <p class="text-gray-700 mb-3 line-clamp-2">{{ property.description }}</p>

            <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
              <span>{{ property.bedrooms }} quartos</span>
              <span>{{ property.bathrooms }} banheiros</span>
              <span>{{ property.area }}m²</span>
            </div>

            <div class="text-2xl font-bold text-blue-600">
              R$ {{ formatPrice(property.price) }}
            </div>
          </div>
        </div>
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
const loading = ref(true);

const loadProperties = async () => {
  try {
    const response = await axios.get('/api/properties');
    properties.value = response.data.data;
  } catch (error) {
    console.error('Erro ao carregar imóveis:', error);
  } finally {
    loading.value = false;
  }
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price);
};

const goToProperty = (id) => {
  router.push({ name: 'PropertyDetail', params: { id } });
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
