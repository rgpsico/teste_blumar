<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
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
        <div v-if="property.photos && property.photos.length > 0" class="relative">
          <div class="aspect-square md:aspect-[21/9] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden group">
            <img
              :src="property.photos[currentPhotoIndex]"
              :alt="property.title"
              class="w-full h-full object-cover"
            />

            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/60 to-transparent"></div>

            <div class="absolute top-6 right-6 bg-black/60 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium">
              {{ currentPhotoIndex + 1 }} / {{ property.photos.length }}
            </div>
          </div>

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

        <div class="grid lg:grid-cols-3 gap-8 p-8">
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

            <div class="bg-gray-50 p-6 rounded-xl">
              <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                Descrição
              </h3>
              <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ property.description }}</p>
            </div>

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

            <div v-if="property.video_url" class="bg-gray-50 p-4 md:p-6 rounded-xl">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
                  <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Tour Virtual
                </h3>
                <button
                  @click="toggleFullscreen"
                  class="md:hidden bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                  </svg>
                  Maximizar
                </button>
              </div>
              <div
                ref="videoContainer"
                class="aspect-video md:aspect-video aspect-[16/12] bg-gray-900 rounded-lg overflow-hidden shadow-lg relative"
              >
                <iframe
                  v-if="getEmbedUrl(property.video_url)"
                  :src="getEmbedUrl(property.video_url)"
                  class="w-full h-full"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                  allowfullscreen
                  webkitallowfullscreen
                  mozallowfullscreen
                ></iframe>
                <div v-else class="flex items-center justify-center h-full text-gray-400">
                  <a :href="property.video_url" target="_blank" class="text-blue-400 hover:text-blue-300 underline">
                    Assistir vídeo
                  </a>
                </div>
              </div>
            </div>
          </div>

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

              <button
                @click="contactViaWhatsApp"
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl font-bold hover:from-green-600 hover:to-green-700 transition shadow-lg transform hover:scale-105 flex items-center justify-center gap-2"
              >
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Entrar em Contato via WhatsApp
              </button>

              <button
                @click="scheduleVisit"
                class="w-full mt-3 bg-white/10 backdrop-blur-sm text-white py-4 rounded-xl font-bold hover:bg-white/20 transition border border-white/20 flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Agendar Visita
              </button>
            </div>

            <!-- AdSense Sidebar Ad -->
            <div class="mt-6 bg-white rounded-2xl overflow-hidden shadow-lg">
              <AdSense
                ad-slot="0987654321"
                ad-format="auto"
                :style="{ display: 'block', minHeight: '250px' }"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <ChatWidget :propertyId="property.id" v-if="property" />

    <Footer />
  </div>
</template>

<script setup>
import ChatWidget from '../../components/ChatWidget.vue';
import AdSense from '../../components/AdSense.vue';
import Footer from '../../components/Footer.vue';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const property = ref(null);
const loading = ref(true);
const currentPhotoIndex = ref(0);
const videoContainer = ref(null);

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

const toggleFullscreen = () => {
  if (!videoContainer.value) return;

  if (!document.fullscreenElement) {
    // Entrar em tela cheia
    if (videoContainer.value.requestFullscreen) {
      videoContainer.value.requestFullscreen();
    } else if (videoContainer.value.webkitRequestFullscreen) {
      // Safari
      videoContainer.value.webkitRequestFullscreen();
    } else if (videoContainer.value.mozRequestFullScreen) {
      // Firefox
      videoContainer.value.mozRequestFullScreen();
    } else if (videoContainer.value.msRequestFullscreen) {
      // IE/Edge
      videoContainer.value.msRequestFullscreen();
    }
  } else {
    // Sair de tela cheia
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    }
  }
};

const contactViaWhatsApp = () => {
  if (!property.value?.owner?.phone) {
    alert('Número de telefone não disponível');
    return;
  }

  // Limpar o número de telefone (remover caracteres não numéricos)
  const phone = property.value.owner.phone.replace(/\D/g, '');

  // Criar mensagem pré-formatada
  const message = `Olá! Tenho interesse no imóvel: *${property.value.title}*

📍 Localização: ${property.value.address}, ${property.value.city} - ${property.value.state}
💰 Valor: R$ ${formatPrice(property.value.price)}/mês
🏠 ${property.value.bedrooms} quartos, ${property.value.bathrooms} banheiros, ${property.value.area}m²

Gostaria de saber mais informações!`;

  // Criar URL do WhatsApp (funciona em mobile e desktop)
  const whatsappUrl = `https://wa.me/55${phone}?text=${encodeURIComponent(message)}`;

  // Abrir em nova aba
  window.open(whatsappUrl, '_blank');
};

const scheduleVisit = () => {
  if (!property.value?.owner?.phone) {
    alert('Número de telefone não disponível');
    return;
  }

  // Limpar o número de telefone
  const phone = property.value.owner.phone.replace(/\D/g, '');

  // Criar mensagem para agendamento de visita
  const message = `Olá! Gostaria de agendar uma visita ao imóvel:

🏠 *${property.value.title}*
📍 ${property.value.address}, ${property.value.city} - ${property.value.state}

Quais são os horários disponíveis?`;

  // Abrir WhatsApp
  const whatsappUrl = `https://wa.me/55${phone}?text=${encodeURIComponent(message)}`;
  window.open(whatsappUrl, '_blank');
};

onMounted(() => {
  loadProperty();
});
</script>