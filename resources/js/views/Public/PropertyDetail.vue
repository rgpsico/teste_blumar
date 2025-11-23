<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <router-link to="/" class="text-2xl font-bold text-blue-600">
            Imóveis
          </router-link>
        </div>
      </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <button
        @click="$router.back()"
        class="mb-4 text-blue-600 hover:text-blue-700"
      >
        ← Voltar
      </button>

      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-500">Carregando...</p>
      </div>

      <div v-else-if="property" class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-4">{{ property.title }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div v-if="property.photos && property.photos.length > 0">
            <img
              :src="property.photos[0]"
              :alt="property.title"
              class="w-full h-64 object-cover rounded"
            />
          </div>
        </div>

        <div class="mb-6">
          <div class="text-3xl font-bold text-blue-600 mb-4">
            R$ {{ formatPrice(property.price) }}
          </div>

          <div class="grid grid-cols-3 gap-4 text-center mb-6">
            <div class="bg-gray-100 p-3 rounded">
              <div class="font-bold">{{ property.bedrooms }}</div>
              <div class="text-sm text-gray-600">Quartos</div>
            </div>
            <div class="bg-gray-100 p-3 rounded">
              <div class="font-bold">{{ property.bathrooms }}</div>
              <div class="text-sm text-gray-600">Banheiros</div>
            </div>
            <div class="bg-gray-100 p-3 rounded">
              <div class="font-bold">{{ property.area }}m²</div>
              <div class="text-sm text-gray-600">Área</div>
            </div>
          </div>

          <div class="mb-4">
            <h3 class="font-bold text-lg mb-2">Descrição</h3>
            <p class="text-gray-700">{{ property.description }}</p>
          </div>

          <div class="mb-4">
            <h3 class="font-bold text-lg mb-2">Localização</h3>
            <p class="text-gray-700">
              {{ property.address }}<br />
              {{ property.city }}, {{ property.state }}<br />
              CEP: {{ property.zip_code }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const property = ref(null);
const loading = ref(true);

const loadProperty = async () => {
  try {
    const response = await axios.get(`/api/properties/${route.params.id}`);
    property.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar imóvel:', error);
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

onMounted(() => {
  loadProperty();
});
</script>
