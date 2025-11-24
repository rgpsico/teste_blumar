<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 flex">
    <!-- Sidebar Desktop -->
    <aside class="hidden lg:flex lg:flex-col lg:w-72 bg-white shadow-2xl border-r border-gray-200">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">FavelaImoveis</h1>
            <p class="text-xs text-gray-500">Painel do Inquilino</p>
          </div>
        </div>
      </div>

      <nav class="flex-1 p-4 space-y-1">
        <button
          @click="currentView = 'property'"
          :class="currentView === 'property' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100'"
          class="w-full flex items-center space-x-3 px-4 py-3.5 rounded-xl transition-all duration-200 font-medium group"
        >
          <svg class="w-5 h-5" :class="currentView === 'property' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span>Meu Imóvel</span>
        </button>

        <button
          @click="currentView = 'messages'"
          :class="currentView === 'messages' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100'"
          class="w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-200 font-medium group relative"
        >
          <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" :class="currentView === 'messages' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>Mensagens</span>
          </div>
          <span v-if="unreadMessages > 0" class="bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
            {{ unreadMessages }}
          </span>
        </button>

        <button
          @click="currentView = 'profile'"
          :class="currentView === 'profile' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100'"
          class="w-full flex items-center space-x-3 px-4 py-3.5 rounded-xl transition-all duration-200 font-medium group"
        >
          <svg class="w-5 h-5" :class="currentView === 'profile' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span>Meu Perfil</span>
        </button>
      </nav>

      <!-- User Info Bottom -->
      <div class="p-6 border-t border-gray-200">
        <div class="flex items-center space-x-4 mb-4">
          <div class="w-12 h-12 rounded-full overflow-hidden ring-4 ring-indigo-100">
            <img v-if="authStore.user?.photo" :src="authStore.user.photo" alt="Avatar" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-xl">
              {{ authStore.user?.name?.[0]?.toUpperCase() }}
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-gray-900 truncate">{{ authStore.user?.name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ authStore.user?.email }}</p>
          </div>
        </div>
        <button @click="handleLogout" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Sair</span>
        </button>
      </div>
    </aside>

    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
      <button @click="mobileMenuOpen = !mobileMenuOpen" class="bg-white p-3 rounded-full shadow-2xl border border-gray-200">
        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'" />
        </svg>
      </button>
    </div>

    <!-- Mobile Sidebar -->
    <div v-if="mobileMenuOpen" class="fixed inset-0 z-40 lg:hidden">
      <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
      <div class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl">
        <!-- mesmo conteúdo do sidebar desktop aqui -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-gray-900">FavelaImoveis</h1>
                <p class="text-xs text-gray-500">Painel do Inquilino</p>
              </div>
            </div>
            <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <nav class="flex-1 p-4 space-y-1">
          <button
            @click="currentView = 'property'; mobileMenuOpen = false"
            :class="currentView === 'property' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100'"
            class="w-full flex items-center space-x-3 px-4 py-3.5 rounded-xl transition-all duration-200 font-medium"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Meu Imóvel</span>
          </button>

          <button
            @click="currentView = 'messages'; mobileMenuOpen = false"
            :class="currentView === 'messages' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100'"
            class="w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-200 font-medium"
          >
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              <span>Mensagens</span>
            </div>
            <span v-if="unreadMessages > 0" class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
              {{ unreadMessages }}
            </span>
          </button>

          <button
            @click="currentView = 'profile'; mobileMenuOpen = false"
            :class="currentView === 'profile' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100'"
            class="w-full flex items-center space-x-3 px-4 py-3.5 rounded-xl transition-all duration-200 font-medium"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Meu Perfil</span>
          </button>
        </nav>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <header class="bg-white/80 backdrop-blur-lg shadow-sm border-b border-gray-200 sticky top-0 z-30">
        <div class="px-6 py-5 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">
              {{ titles[currentView] }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">{{ subtitles[currentView] }}</p>
          </div>
          <a href="/" class="flex items-center space-x-2 text-indigo-600 hover:text-indigo-700 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Voltar ao Site</span>
          </a>
        </div>
      </header>

      <main class="flex-1 overflow-auto p-6 lg:p-8">
        <!-- Suas views (property, messages, profile) continuam iguais -->
        <!-- Só melhorei o container com mais espaço e scroll suave -->
        <div class="max-w-7xl mx-auto">
          <!-- Aqui ficam suas views existentes -->
          <div v-if="currentView === 'property'">...</div>
          <div v-else-if="currentView === 'messages'">...</div>
          <div v-else-if="currentView === 'profile'">...</div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const currentView = ref('property');
const mobileMenuOpen = ref(false);
const unreadMessages = ref(0);

const titles = {
  property: 'Meu Imóvel',
  messages: 'Mensagens',
  profile: 'Meu Perfil'
};

const subtitles = {
  property: 'Detalhes do seu imóvel alugado',
  messages: 'Converse com o proprietário',
  profile: 'Gerencie suas informações pessoais'
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

onMounted(async () => {
  await authStore.checkAuth();
});
</script>

<style scoped>
/* Scroll suave global */
html {
  scroll-behavior: smooth;
}

/* Animação do badge de mensagens não lidas */
@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.2); }
}
.animate-pulse {
  animation: pulse 2s infinite;
}
</style>