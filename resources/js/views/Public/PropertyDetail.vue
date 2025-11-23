<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Navbar Moderna -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <router-link to="/" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
            </div>
            <div>
              <div class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                ImoveisPro
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <button
        @click="$router.back()"
        class="mb-6 flex items-center gap-2 text-gray-700 hover:text-blue-600 transition font-medium group"
      >
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Voltar para listagem
      </button>

      <div v-if="loading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="text-gray-500 mt-4">Carregando detalhes...</p>
      </div>

      <div v-else-if="property" class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Galeria de Fotos Moderna -->
        <div v-if="property.photos && property.photos.length > 0" class="relative">
          <div class="aspect-[21/9] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden group">
            <img
              :src="property.photos[currentPhotoIndex]"
              :alt="property.title"
              class="w-full h-full object-cover"
            />

            <!-- Overlay gradient no bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/60 to-transparent"></div>

            <!-- Contador de fotos -->
            <div class="absolute top-6 right-6 bg-black/60 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium">
              {{ currentPhotoIndex + 1 }} / {{ property.photos.length }}
            </div>
          </div>

          <!-- Navegação de Fotos -->
          <div v-if="property.photos.length > 1" class="absolute inset-0 flex items-center justify-between p-6 pointer-events-none">
            <button
              @click="previousPhoto"
              class="pointer-events-auto bg-white/90 backdrop-blur-sm text-gray-800 p-4 rounded-full hover:bg-white shadow-xl transition transform hover:scale-110"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button
              @click="nextPhoto"
              class="pointer-events-auto bg-white/90 backdrop-blur-sm text-gray-800 p-4 rounded-full hover:bg-white shadow-xl transition transform hover:scale-110"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>

          <!-- Miniaturas -->
          <div v-if="property.photos.length > 1" class="px-6 py-4 flex gap-3 overflow-x-auto bg-gray-50">
            <button
              v-for="(photo, index) in property.photos"
              :key="index"
              @click="currentPhotoIndex = index"
              class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden transition-all transform hover:scale-105"
              :class="currentPhotoIndex === index ? 'ring-4 ring-blue-500 shadow-lg' : 'ring-2 ring-gray-200 opacity-60 hover:opacity-100'"
            >
              <img
                :src="photo"
                :alt="`Foto ${index + 1}`"
                class="w-full h-full object-cover"
              />
            </button>
          </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="grid lg:grid-cols-3 gap-8 p-8">
          <!-- Coluna Principal -->
          <div class="lg:col-span-2 space-y-8">
            <div>
              <div class="flex items-start justify-between mb-4">
                <div>
                  <h1 class="text-4xl font-bold mb-2 text-gray-800">{{ property.title }}</h1>
                  <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ property.address }}, {{ property.city }} - {{ property.state }}
                  </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                  Disponível
                </div>
              </div>

              <!-- Características -->
              <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl text-center border border-blue-200">
                  <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  <div class="text-2xl font-bold text-gray-800">{{ property.bedrooms }}</div>
                  <div class="text-sm text-gray-600 font-medium">Quartos</div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl text-center border border-purple-200">
                  <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                  </svg>
                  <div class="text-2xl font-bold text-gray-800">{{ property.bathrooms }}</div>
                  <div class="text-sm text-gray-600 font-medium">Banheiros</div>
                </div>
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-6 rounded-xl text-center border border-pink-200">
                  <svg class="w-8 h-8 mx-auto mb-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                  </svg>
                  <div class="text-2xl font-bold text-gray-800">{{ property.area }}m²</div>
                  <div class="text-sm text-gray-600 font-medium">Área Total</div>
                </div>
              </div>
            </div>

            <!-- Descrição -->
            <div class="bg-gray-50 p-6 rounded-xl">
              <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                Descrição
              </h3>
              <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ property.description }}</p>
            </div>

            <!-- Localização -->
            <div class="bg-gray-50 p-6 rounded-xl">
              <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Localização
              </h3>
              <div class="space-y-2 text-gray-700">
                <p class="font-medium">{{ property.address }}</p>
                <p>{{ property.city }}, {{ property.state }}</p>
                <p class="text-sm text-gray-600">CEP: {{ property.zip_code }}</p>
              </div>
            </div>

            <!-- Vídeo -->
            <div v-if="property.video_url" class="bg-gray-50 p-6 rounded-xl">
              <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tour Virtual
              </h3>
              <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden shadow-lg">
                <iframe
                  v-if="getEmbedUrl(property.video_url)"
                  :src="getEmbedUrl(property.video_url)"
                  class="w-full h-full"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                ></iframe>
                <div v-else class="flex items-center justify-center h-full text-gray-400">
                  <a :href="property.video_url" target="_blank" class="text-blue-400 hover:text-blue-300 underline">
                    Assistir vídeo
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar - Card de Contato -->
          <div class="lg:col-span-1">
            <div class="sticky top-24 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-8 text-white">
              <div class="text-center mb-6">
                <div class="text-sm text-white/80 mb-2">Valor do Aluguel</div>
                <div class="text-4xl font-bold mb-1">R$ {{ formatPrice(property.price) }}</div>
                <div class="text-sm text-white/80">por mês</div>
              </div>

              <div v-if="property.owner" class="bg-white/10 backdrop-blur-sm rounded-xl p-6 mb-6">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Informações de Contato
                </h3>
                <div class="space-y-3">
                  <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ property.owner.name }}</span>
                  </div>
                  <div v-if="property.owner.phone" class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>{{ property.owner.phone }}</span>
                  </div>
                  <div v-if="property.owner.email" class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">{{ property.owner.email }}</span>
                  </div>
                </div>
              </div>

              <button class="w-full bg-white text-blue-600 py-4 rounded-xl font-bold hover:bg-gray-100 transition shadow-lg transform hover:scale-105 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Entrar em Contato
              </button>

              <button class="w-full mt-3 bg-white/10 backdrop-blur-sm text-white py-4 rounded-xl font-bold hover:bg-white/20 transition border border-white/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Agendar Visita
              </button>
            </div>
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
const currentPhotoIndex = ref(0);

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

const nextPhoto = () => {
  if (property.value.photos) {
    currentPhotoIndex.value = (currentPhotoIndex.value + 1) % property.value.photos.length;
  }
};

const previousPhoto = () => {
  if (property.value.photos) {
    currentPhotoIndex.value =
      currentPhotoIndex.value === 0
        ? property.value.photos.length - 1
        : currentPhotoIndex.value - 1;
  }
};

const getEmbedUrl = (url) => {
  if (!url) return null;

  // YouTube
  const youtubeMatch = url.match(
    /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\?\/]+)/
  );
  if (youtubeMatch) {
    return `https://www.youtube.com/embed/${youtubeMatch[1]}`;
  }

  // Vimeo
  const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
  if (vimeoMatch) {
    return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
  }

  return null;
};

onMounted(() => {
  loadProperty();
});
</script>
