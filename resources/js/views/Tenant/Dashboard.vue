<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-indigo-600 to-purple-700 text-white flex-shrink-0 hidden lg:flex lg:flex-col">
      <div class="p-6 flex-1">
        <div class="flex items-center space-x-3 mb-8">
          <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <div>
            <div class="font-bold text-lg">ImoveisPro</div>
            <div class="text-xs text-white/70">Painel do Inquilino</div>
          </div>
        </div>

        <!-- Menu -->
        <nav class="space-y-2">
          <button
            @click="currentView = 'property'"
            :class="currentView === 'property' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Meu Imóvel</span>
          </button>

          <button
            @click="currentView = 'messages'"
            :class="currentView === 'messages' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="font-medium">Mensagens</span>
            <span v-if="unreadMessages > 0" class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
              {{ unreadMessages }}
            </span>
          </button>

          <button
            @click="currentView = 'profile'"
            :class="currentView === 'profile' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="font-medium">Meu Perfil</span>
          </button>
        </nav>
      </div>

      <!-- User Info at Bottom -->
      <div class="p-6 border-t border-white/10">
        <div class="flex items-center space-x-3 mb-3">
          <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center overflow-hidden">
            <img
              v-if="authStore.user?.photo"
              :src="authStore.user.photo"
              alt="Avatar"
              class="w-full h-full object-cover"
            />
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="font-medium truncate">{{ authStore.user?.name }}</div>
            <div class="text-xs text-white/70 truncate">{{ authStore.user?.email }}</div>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span class="font-medium">Sair</span>
        </button>
      </div>
    </aside>

    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
      <button
        @click="mobileMenuOpen = !mobileMenuOpen"
        class="bg-indigo-600 text-white p-3 rounded-lg shadow-lg"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div
      v-if="mobileMenuOpen"
      @click="mobileMenuOpen = false"
      class="lg:hidden fixed inset-0 bg-black/50 z-40"
    ></div>

    <!-- Mobile Sidebar -->
    <aside
      :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
      class="lg:hidden fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-indigo-600 to-purple-700 text-white z-50 transition-transform duration-300 flex flex-col"
    >
      <div class="p-6 flex-1">
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
            </div>
            <div>
              <div class="font-bold text-lg">ImoveisPro</div>
              <div class="text-xs text-white/70">Painel do Inquilino</div>
            </div>
          </div>
          <button @click="mobileMenuOpen = false" class="p-1 hover:bg-white/10 rounded">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <nav class="space-y-2">
          <button
            @click="currentView = 'property'; mobileMenuOpen = false"
            :class="currentView === 'property' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Meu Imóvel</span>
          </button>

          <button
            @click="currentView = 'messages'; mobileMenuOpen = false"
            :class="currentView === 'messages' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="font-medium">Mensagens</span>
          </button>

          <button
            @click="currentView = 'profile'; mobileMenuOpen = false"
            :class="currentView === 'profile' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="font-medium">Meu Perfil</span>
          </button>
        </nav>
      </div>

      <div class="p-6 border-t border-white/10">
        <div class="flex items-center space-x-3 mb-3">
          <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="font-medium truncate">{{ authStore.user?.name }}</div>
            <div class="text-xs text-white/70 truncate">{{ authStore.user?.email }}</div>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span class="font-medium">Sair</span>
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top Bar -->
      <header class="bg-white shadow-sm">
        <div class="px-6 py-4 flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">
              {{ currentView === 'property' ? 'Meu Imóvel' : currentView === 'messages' ? 'Mensagens' : 'Meu Perfil' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
              {{ currentView === 'property' ? 'Detalhes do imóvel alugado' : currentView === 'messages' ? 'Comunique-se com o proprietário' : 'Atualize suas informações' }}
            </p>
          </div>
          <router-link
            to="/"
            class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Ver Site</span>
          </router-link>
        </div>
      </header>

      <!-- Content Area -->
      <div class="flex-1 overflow-auto p-6">
        <!-- Property View -->
        <div v-if="currentView === 'property'">
          <div v-if="myProperty" class="max-w-4xl">
            <!-- Property Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
              <!-- Photo Gallery -->
              <div v-if="myProperty.photos && myProperty.photos.length > 0" class="relative">
                <div class="aspect-[21/9] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden">
                  <img
                    :src="myProperty.photos[currentPhotoIndex]"
                    :alt="myProperty.title"
                    class="w-full h-full object-cover"
                  />
                </div>

                <!-- Photo Navigation -->
                <div v-if="myProperty.photos.length > 1" class="absolute inset-0 flex items-center justify-between p-6 pointer-events-none">
                  <button
                    @click="previousPhoto"
                    class="pointer-events-auto bg-white/90 backdrop-blur-sm text-gray-800 p-3 rounded-full hover:bg-white shadow-xl transition transform hover:scale-110"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                  <button
                    @click="nextPhoto"
                    class="pointer-events-auto bg-white/90 backdrop-blur-sm text-gray-800 p-3 rounded-full hover:bg-white shadow-xl transition transform hover:scale-110"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </div>
              </div>

              <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                  <div>
                    <h2 class="text-3xl font-bold mb-2 text-gray-800">{{ myProperty.title }}</h2>
                    <div class="flex items-center text-gray-600">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {{ myProperty.address }}, {{ myProperty.city }} - {{ myProperty.state }}
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="text-sm text-gray-500 mb-1">Valor Mensal</div>
                    <div class="text-3xl font-bold text-indigo-600">R$ {{ formatPrice(myProperty.price) }}</div>
                  </div>
                </div>

                <!-- Property Details -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                  <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl text-center border border-blue-200">
                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <div class="text-2xl font-bold text-gray-800">{{ myProperty.bedrooms }}</div>
                    <div class="text-sm text-gray-600 font-medium">Quartos</div>
                  </div>
                  <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl text-center border border-purple-200">
                    <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                    <div class="text-2xl font-bold text-gray-800">{{ myProperty.bathrooms }}</div>
                    <div class="text-sm text-gray-600 font-medium">Banheiros</div>
                  </div>
                  <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-6 rounded-xl text-center border border-pink-200">
                    <svg class="w-8 h-8 mx-auto mb-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    <div class="text-2xl font-bold text-gray-800">{{ myProperty.area }}m²</div>
                    <div class="text-sm text-gray-600 font-medium">Área Total</div>
                  </div>
                </div>

                <!-- Description -->
                <div class="bg-gray-50 p-6 rounded-xl mb-6">
                  <h3 class="text-lg font-bold mb-3 text-gray-800">Descrição</h3>
                  <p class="text-gray-700 leading-relaxed">{{ myProperty.description }}</p>
                </div>

                <!-- Owner Info -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 p-6 rounded-xl">
                  <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informações do Proprietário
                  </h3>
                  <div class="space-y-3">
                    <div class="flex items-center gap-3">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      <span class="text-gray-800 font-medium">{{ myProperty.owner?.name }}</span>
                    </div>
                    <div v-if="myProperty.owner?.phone" class="flex items-center gap-3">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      <span class="text-gray-700">{{ myProperty.owner.phone }}</span>
                    </div>
                    <div v-if="myProperty.owner?.email" class="flex items-center gap-3">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      <span class="text-gray-700">{{ myProperty.owner.email }}</span>
                    </div>
                  </div>
                  <button
                    @click="currentView = 'messages'"
                    class="mt-4 w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition flex items-center justify-center gap-2"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Enviar Mensagem
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
              <svg class="w-24 h-24 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <h3 class="text-2xl font-bold text-gray-800 mb-2">Nenhum Imóvel Alugado</h3>
              <p class="text-gray-600 mb-6">Você não está alugando nenhum imóvel no momento.</p>
              <router-link
                to="/"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Buscar Imóveis
              </router-link>
            </div>
          </div>
        </div>

        <!-- Messages View -->
        <div v-else-if="currentView === 'messages'">
          <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
              <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h2 class="text-xl font-bold text-gray-800">Mensagens com o Proprietário</h2>
                <p class="text-sm text-gray-600 mt-1">Envie mensagens sobre o imóvel ou questões relacionadas</p>
              </div>

              <!-- Messages Area -->
              <div class="p-6 h-96 overflow-y-auto bg-gray-50">
                <div v-if="messages.length === 0" class="text-center py-12">
                  <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <p class="text-gray-500">Nenhuma mensagem ainda</p>
                  <p class="text-sm text-gray-400 mt-1">Envie a primeira mensagem para o proprietário</p>
                </div>

                <div v-else class="space-y-4">
                  <div
                    v-for="message in messages"
                    :key="message.id"
                    :class="message.from_tenant ? 'text-right' : 'text-left'"
                  >
                    <div
                      :class="message.from_tenant ? 'bg-indigo-600 text-white ml-auto' : 'bg-white text-gray-800'"
                      class="inline-block px-4 py-3 rounded-lg max-w-md shadow"
                    >
                      <p class="text-sm">{{ message.message }}</p>
                      <p class="text-xs mt-1 opacity-70">{{ formatDate(message.created_at) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Message Input -->
              <div class="p-6 border-t border-gray-200 bg-white">
                <form @submit.prevent="sendMessage" class="flex gap-3">
                  <input
                    v-model="newMessage"
                    type="text"
                    placeholder="Digite sua mensagem..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    :disabled="!myProperty"
                  />
                  <button
                    type="submit"
                    :disabled="!newMessage.trim() || !myProperty"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                  </button>
                </form>
                <p v-if="!myProperty" class="text-xs text-gray-500 mt-2">
                  Você precisa estar alugando um imóvel para enviar mensagens
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile View -->
        <div v-else-if="currentView === 'profile'">
          <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">Meu Perfil</h2>

              <form @submit.prevent="updateProfile" class="space-y-6">
                <!-- Photo Upload -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Foto de Perfil</label>
                  <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                      <img
                        v-if="profileForm.photo"
                        :src="profileForm.photo"
                        alt="Avatar"
                        class="w-full h-full object-cover"
                      />
                      <svg v-else class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <div class="flex-1">
                      <label class="cursor-pointer">
                        <span class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition inline-block">
                          Fazer Upload de Foto
                        </span>
                        <input
                          type="file"
                          accept="image/*"
                          @change="handlePhotoUpload"
                          class="hidden"
                        />
                      </label>
                      <p class="text-xs text-gray-500 mt-2">JPG, PNG ou GIF (max. 5MB)</p>
                      <p v-if="uploadingPhoto" class="text-xs text-indigo-600 mt-1">Enviando foto...</p>
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Nome</label>
                  <input
                    v-model="profileForm.name"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                  <input
                    v-model="profileForm.email"
                    type="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
                  <input
                    v-model="profileForm.phone"
                    type="tel"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Comunidade</label>
                  <select
                    v-model.number="profileForm.community_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                  >
                    <option v-for="community in communities" :key="community.id" :value="community.id">
                      {{ community.name }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Cartão de Crédito (últimos 4 dígitos)</label>
                  <input
                    v-model="profileForm.credit_card"
                    type="text"
                    maxlength="4"
                    placeholder="****"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  />
                  <p class="text-xs text-gray-500 mt-1">Apenas para referência nos pagamentos</p>
                </div>

                <div class="pt-4 border-t border-gray-200">
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">Alterar Senha</h3>

                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Nova Senha (opcional)</label>
                      <input
                        v-model="profileForm.password"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Deixe em branco para não alterar"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Nova Senha</label>
                      <input
                        v-model="profileForm.password_confirmation"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Deixe em branco para não alterar"
                      />
                    </div>
                  </div>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                  <button
                    type="button"
                    @click="loadProfileData"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                  >
                    Cancelar
                  </button>
                  <button
                    type="submit"
                    :disabled="uploadingPhoto"
                    class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
                  >
                    Salvar Alterações
                  </button>
                </div>
              </form>
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

const currentView = ref('property');
const mobileMenuOpen = ref(false);
const myProperty = ref(null);
const currentPhotoIndex = ref(0);
const communities = ref([]);
const uploadingPhoto = ref(false);

const profileForm = ref({
  name: '',
  email: '',
  phone: '',
  community_id: '',
  photo: '',
  credit_card: '',
  password: '',
  password_confirmation: '',
});

const messages = ref([]);
const newMessage = ref('');
const unreadMessages = ref(0);

const loadMyProperty = async () => {
  try {
    const response = await axios.get('/api/properties');
    const allProperties = response.data.data || response.data;
    myProperty.value = allProperties.find(
      (p) => p.tenant_id === authStore.user?.id
    );
  } catch (error) {
    console.error('Erro ao carregar imóvel:', error);
  }
};

const loadCommunities = async () => {
  try {
    const response = await axios.get('/api/communities');
    communities.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar comunidades:', error);
  }
};

const loadProfileData = () => {
  profileForm.value = {
    name: authStore.user?.name || '',
    email: authStore.user?.email || '',
    phone: authStore.user?.phone || '',
    community_id: authStore.user?.community_id || '',
    photo: authStore.user?.photo || '',
    credit_card: authStore.user?.credit_card || '',
    password: '',
    password_confirmation: '',
  };
};

const handlePhotoUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (file.size > 5 * 1024 * 1024) {
    alert('A imagem deve ter no máximo 5MB');
    return;
  }

  uploadingPhoto.value = true;

  try {
    const formData = new FormData();
    formData.append('image', file);

    const response = await axios.post('/api/upload-image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    if (response.data.success) {
      profileForm.value.photo = response.data.url;
    }
  } catch (error) {
    console.error('Erro ao fazer upload:', error);
    alert('Erro ao fazer upload da foto');
  } finally {
    uploadingPhoto.value = false;
    event.target.value = '';
  }
};

const updateProfile = async () => {
  try {
    const data = {
      name: profileForm.value.name,
      email: profileForm.value.email,
      phone: profileForm.value.phone,
      community_id: profileForm.value.community_id,
      photo: profileForm.value.photo,
      credit_card: profileForm.value.credit_card,
    };

    if (profileForm.value.password && profileForm.value.password.trim() !== '') {
      if (profileForm.value.password !== profileForm.value.password_confirmation) {
        alert('As senhas não coincidem');
        return;
      }
      data.password = profileForm.value.password;
      data.password_confirmation = profileForm.value.password_confirmation;
    }

    await axios.put('/api/profile', data);

    const response = await axios.get('/api/me');
    authStore.user = response.data;

    alert('Perfil atualizado com sucesso!');
    loadProfileData();
  } catch (error) {
    console.error('Erro ao atualizar perfil:', error);
    alert('Erro ao atualizar perfil: ' + (error.response?.data?.message || error.message));
  }
};

const sendMessage = async () => {
  if (!newMessage.value.trim() || !myProperty.value) return;

  // Simulação - em produção, isso enviaria para uma API
  const message = {
    id: Date.now(),
    message: newMessage.value,
    from_tenant: true,
    created_at: new Date().toISOString(),
  };

  messages.value.push(message);
  newMessage.value = '';

  // TODO: Implementar envio real via API
  console.log('Enviando mensagem:', message);
};

const previousPhoto = () => {
  if (myProperty.value?.photos && myProperty.value.photos.length > 0) {
    currentPhotoIndex.value = (currentPhotoIndex.value - 1 + myProperty.value.photos.length) % myProperty.value.photos.length;
  }
};

const nextPhoto = () => {
  if (myProperty.value?.photos && myProperty.value.photos.length > 0) {
    currentPhotoIndex.value = (currentPhotoIndex.value + 1) % myProperty.value.photos.length;
  }
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

onMounted(() => {
  loadMyProperty();
  loadCommunities();
  loadProfileData();
});
</script>
