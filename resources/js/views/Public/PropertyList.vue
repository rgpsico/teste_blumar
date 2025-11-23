<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Navbar Moderna -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
            </div>
            <div>
              <div class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                ImoveisPro
              </div>
              <div class="text-xs text-gray-500">Encontre seu lar ideal</div>
            </div>
          </div>
          <div class="flex space-x-4 items-center">
            <template v-if="authStore.isAuthenticated">
              <router-link
                v-if="authStore.isAdmin"
                to="/admin"
                class="text-gray-700 hover:text-blue-600 transition font-medium"
              >
                Dashboard
              </router-link>
              <router-link
                v-if="authStore.isOwner"
                to="/owner"
                class="text-gray-700 hover:text-blue-600 transition font-medium"
              >
                Dashboard
              </router-link>
              <router-link
                v-if="authStore.isTenant"
                to="/tenant"
                class="text-gray-700 hover:text-blue-600 transition font-medium"
              >
                Dashboard
              </router-link>
              <button
                @click="handleLogout"
                class="text-gray-700 hover:text-red-600 transition font-medium"
              >
                Sair
              </button>
            </template>
            <template v-else>
              <router-link to="/login" class="text-gray-700 hover:text-blue-600 transition font-medium">
                Login
              </router-link>
              <router-link
                to="/register"
                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full hover:shadow-lg transition transform hover:-translate-y-0.5"
              >
                Registrar
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white overflow-hidden">
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDQwIDAgTCAwIDAgMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIwLjUiIG9wYWNpdHk9IjAuMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-20"></div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-4 drop-shadow-lg">
          Encontre o imóvel dos seus sonhos
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-white/90 drop-shadow">
          Centenas de opções para você e sua família
        </p>

        <!-- Search Bar -->
        <div class="max-w-3xl mx-auto bg-white rounded-full shadow-2xl p-2 flex items-center gap-2">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar por cidade, bairro ou título..."
            class="flex-1 px-6 py-3 text-gray-800 outline-none rounded-full"
          />
          <button
            @click="filterProperties"
            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-full hover:shadow-lg transition font-semibold"
          >
            Buscar
          </button>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Filtros Rápidos -->
      <div class="mb-8 flex flex-wrap gap-3">
        <button
          @click="quickFilter = null"
          :class="quickFilter === null ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
          class="px-6 py-2 rounded-full shadow-sm hover:shadow-md transition font-medium"
        >
          Todos
        </button>
        <button
          @click="quickFilter = 'bedrooms-1'"
          :class="quickFilter === 'bedrooms-1' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
          class="px-6 py-2 rounded-full shadow-sm hover:shadow-md transition font-medium"
        >
          1 Quarto
        </button>
        <button
          @click="quickFilter = 'bedrooms-2'"
          :class="quickFilter === 'bedrooms-2' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
          class="px-6 py-2 rounded-full shadow-sm hover:shadow-md transition font-medium"
        >
          2 Quartos
        </button>
        <button
          @click="quickFilter = 'bedrooms-3'"
          :class="quickFilter === 'bedrooms-3' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
          class="px-6 py-2 rounded-full shadow-sm hover:shadow-md transition font-medium"
        >
          3+ Quartos
        </button>
      </div>

      <div v-if="loading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="text-gray-500 mt-4">Carregando imóveis incríveis...</p>
      </div>

      <div v-else-if="filteredProperties.length === 0" class="text-center py-20">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="text-gray-500 mt-4 text-lg">Nenhum imóvel encontrado com os filtros selecionados.</p>
      </div>

      <div v-else>
        <div class="mb-6 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">
            {{ filteredProperties.length }} imóveis disponíveis
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="property in filteredProperties"
            :key="property.id"
            class="group bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2"
            @click="goToProperty(property.id)"
          >
            <div class="relative h-56 bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden">
              <img
                v-if="property.photos && property.photos[0]"
                :src="property.photos[0]"
                :alt="property.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div v-else class="flex items-center justify-center h-full">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>

              <!-- Badge de Disponível -->
              <div class="absolute top-4 left-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                Disponível
              </div>

              <!-- Número de fotos -->
              <div v-if="property.photos && property.photos.length > 1" class="absolute top-4 right-4 bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ property.photos.length }}
              </div>
            </div>

            <div class="p-6">
              <h3 class="font-bold text-xl mb-2 text-gray-800 group-hover:text-blue-600 transition">
                {{ property.title }}
              </h3>

              <div class="flex items-center text-gray-600 text-sm mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ property.city }}, {{ property.state }}
              </div>

              <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{{ property.description }}</p>

              <div class="flex items-center justify-between text-sm text-gray-700 mb-4 bg-gray-50 p-3 rounded-lg">
                <div class="flex items-center gap-1">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  <span class="font-medium">{{ property.bedrooms }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                  </svg>
                  <span class="font-medium">{{ property.bathrooms }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                  </svg>
                  <span class="font-medium">{{ property.area }}m²</span>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm text-gray-500">A partir de</div>
                  <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    R$ {{ formatPrice(property.price) }}
                  </div>
                </div>
                <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-3 rounded-full hover:shadow-lg transition transform group-hover:scale-110">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import axios from 'axios';

const router = useRouter();
const authStore = useAuthStore();

const properties = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const quickFilter = ref(null);

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

const filteredProperties = computed(() => {
  let result = properties.value;

  // Filtro de busca
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (property) =>
        property.title.toLowerCase().includes(query) ||
        property.city.toLowerCase().includes(query) ||
        property.state.toLowerCase().includes(query) ||
        property.description.toLowerCase().includes(query)
    );
  }

  // Filtro rápido por quartos
  if (quickFilter.value === 'bedrooms-1') {
    result = result.filter((property) => property.bedrooms === 1);
  } else if (quickFilter.value === 'bedrooms-2') {
    result = result.filter((property) => property.bedrooms === 2);
  } else if (quickFilter.value === 'bedrooms-3') {
    result = result.filter((property) => property.bedrooms >= 3);
  }

  return result;
});

const filterProperties = () => {
  // A filtragem acontece automaticamente via computed
  // Esta função existe para o botão de busca
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

// Limpar filtro rápido quando buscar por texto
watch(searchQuery, () => {
  if (searchQuery.value) {
    quickFilter.value = null;
  }
});

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
