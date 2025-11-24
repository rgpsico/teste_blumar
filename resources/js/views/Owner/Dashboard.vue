<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-600 to-purple-700 text-white flex-shrink-0 hidden lg:block">
      <div class="p-6">
        <div class="flex items-center space-x-3 mb-8">
          <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <div>
            <div class="font-bold text-lg">ImoveisPro</div>
            <div class="text-xs text-white/70">Painel do Proprietário</div>
          </div>
        </div>

        <!-- Menu -->
        <nav class="space-y-2">
          <button
            @click="currentView = 'properties'"
            :class="currentView === 'properties' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="font-medium">Meus Imóveis</span>
          </button>

          <button
            @click="currentView = 'tenants'"
            :class="currentView === 'tenants' ? 'bg-white/20 backdrop-blur-sm' : 'hover:bg-white/10'"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="font-medium">Inquilinos</span>
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
      <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-white/10">
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
              {{ currentView === 'properties' ? 'Meus Imóveis' : currentView === 'tenants' ? 'Inquilinos' : 'Meu Perfil' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
              {{ currentView === 'properties' ? 'Gerencie seus imóveis' : currentView === 'tenants' ? 'Gerencie seus inquilinos' : 'Atualize suas informações' }}
            </p>
          </div>
          <router-link
            to="/"
            class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition"
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
        <!-- Properties View -->
        <div v-if="currentView === 'properties'">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">Total de Imóveis</p>
                  <p class="text-3xl font-bold">{{ properties.length }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">Disponíveis</p>
                  <p class="text-3xl font-bold">{{ properties.filter((p) => p.status === 'available').length }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">Alugados</p>
                  <p class="text-3xl font-bold">{{ properties.filter((p) => p.status === 'rented').length }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Properties Table -->
          <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
              <h2 class="text-xl font-bold text-gray-800">Lista de Imóveis</h2>
              <button
                @click="openAddModal"
                class="flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Adicionar Imóvel</span>
              </button>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Imóvel</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Localização</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detalhes</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preço</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="property in properties" :key="property.id" class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                      <div class="flex items-center space-x-3">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                          <img
                            v-if="property.photos && property.photos.length > 0"
                            :src="getPhotoUrl(property.photos[0])"
                            :alt="property.title"
                            class="w-full h-full object-cover"
                          />
                          <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                          </div>
                        </div>
                        <div class="flex-1 min-w-0">
                          <p class="font-semibold text-gray-900 truncate">{{ property.title }}</p>
                          <p class="text-sm text-gray-500 truncate">{{ property.description }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm">
                        <p class="font-medium text-gray-900">{{ property.city }}</p>
                        <p class="text-gray-500">{{ property.state }}</p>
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex flex-col text-sm text-gray-600 space-y-1">
                        <span>{{ property.bedrooms }} quartos</span>
                        <span>{{ property.bathrooms }} banheiros</span>
                        <span>{{ property.area }}m²</span>
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <p class="text-lg font-bold text-blue-600">R$ {{ formatPrice(property.price) }}</p>
                    </td>
                    <td class="px-6 py-4">
                      <span
                        class="px-3 py-1 rounded-full text-xs font-semibold"
                        :class="{
                          'bg-green-100 text-green-800': property.status === 'available',
                          'bg-purple-100 text-purple-800': property.status === 'rented',
                          'bg-yellow-100 text-yellow-800': property.status === 'maintenance',
                        }"
                      >
                        {{ getStatusLabel(property.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center space-x-2">
                        <button
                          @click="viewProperty(property)"
                          class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                          title="Ver"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                        </button>
                        <button
                          @click="openFaqModal(property)"
                          class="flex items-center gap-1.5 px-2.5 py-1.5 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition font-medium text-sm"
                          title="Treinar Bot - Configurar respostas automáticas"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.8L3 20l1.2-3A7.965 7.965 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            />
                          </svg>
                          <span>Bot</span>
                        </button>
                        <button
                          @click="openEditModal(property)"
                          class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition"
                          title="Editar"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </button>
                        <button
                          @click="deleteProperty(property.id)"
                          class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                          title="Excluir"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-if="properties.length === 0" class="p-12 text-center">
              <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <p class="text-gray-500 mb-4">Nenhum imóvel cadastrado</p>
              <button
                @click="openAddModal"
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition"
              >
                Adicionar Primeiro Imóvel
              </button>
            </div>
          </div>
        </div>

        <!-- Tenants View -->
        <div v-else-if="currentView === 'tenants'">
          <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6 border-b border-gray-200">
              <h2 class="text-xl font-bold text-gray-800">Lista de Inquilinos</h2>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Imóvel</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contato</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr class="hover:bg-gray-50 transition">
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                      <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      Nenhum inquilino cadastrado
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Profile View -->
        <div v-else-if="currentView === 'profile'">
          <div class="max-w-2xl bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Meu Perfil</h2>

            <form @submit.prevent="updateProfile" class="space-y-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nome</label>
                <input
                  v-model="profileForm.name"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input
                  v-model="profileForm.email"
                  type="email"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
                <input
                  v-model="profileForm.phone"
                  type="tel"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Comunidade</label>
                <select
                  v-model.number="profileForm.community_id"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                >
                  <option v-for="community in communities" :key="community.id" :value="community.id">
                    {{ community.name }}
                  </option>
                </select>
              </div>

              <div class="pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Alterar Senha</h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nova Senha (opcional)</label>
                    <input
                      v-model="profileForm.password"
                      type="password"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Deixe em branco para não alterar"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Nova Senha</label>
                    <input
                      v-model="profileForm.password_confirmation"
                      type="password"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                  class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition"
                >
                  Salvar Alterações
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Bots/FAQs -->
    <div
      v-if="showFaqModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
      @click.self="closeFaqModal"
    >
      <div class="bg-white rounded-lg overflow-hidden max-w-3xl w-full max-h-[90vh] shadow-2xl">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 text-white relative">
          <div class="flex items-center gap-3">
            <span class="text-4xl">🤖</span>
            <div>
              <h2 class="text-2xl font-bold">Treinar Bot</h2>
              <p class="text-sm text-purple-100 mt-1">Configure respostas automáticas para este imóvel</p>
              <p v-if="selectedPropertyForFaqs" class="text-xs text-purple-200 mt-1 font-medium">
                📍 {{ selectedPropertyForFaqs.title }}
              </p>
            </div>
          </div>
          <button class="absolute top-4 right-4 text-white hover:text-gray-200 text-3xl font-light" @click="closeFaqModal">&times;</button>
        </div>

        <div class="space-y-6">
          <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg p-5 border-2 border-purple-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
              <span class="text-xl">➕</span> Cadastrar nova resposta
            </h3>
            <form class="space-y-4" @submit.prevent="saveFaq">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pergunta ou gatilho</label>
                <input
                  v-model="faqForm.question"
                  type="text"
                  class="w-full px-3 py-2 border rounded-md"
                  placeholder="Ex: Horários de check-in"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Resposta do bot</label>
                <textarea
                  v-model="faqForm.answer"
                  class="w-full px-3 py-2 border rounded-md"
                  rows="3"
                  placeholder="Inclua os detalhes que o bot deve responder"
                  required
                ></textarea>
              </div>
              <div class="flex justify-end">
                <button
                  type="submit"
                  :disabled="savingFaq"
                  class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 disabled:opacity-50 font-medium shadow-lg transition"
                >
                  {{ savingFaq ? '💾 Salvando...' : '💾 Salvar resposta' }}
                </button>
              </div>
            </form>
          </div>

          <div>
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <span class="text-xl">📚</span> Respostas cadastradas
              </h3>
              <span class="text-sm bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-medium">{{ faqs.length }} itens</span>
            </div>

            <div v-if="faqsLoading" class="text-center text-gray-500 py-8">
              <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-purple-500 border-t-transparent"></div>
              <p class="mt-2">Carregando respostas...</p>
            </div>
            <div v-else-if="faqs.length === 0" class="text-center text-gray-400 py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
              <span class="text-4xl block mb-2">📭</span>
              Nenhuma resposta cadastrada ainda.
            </div>
            <div v-else class="space-y-3 max-h-64 overflow-y-auto pr-2">
              <div
                v-for="faq in faqs"
                :key="faq.id"
                class="border-2 border-purple-100 bg-white rounded-lg p-4 flex items-start justify-between hover:shadow-md transition"
              >
                <div class="flex-1">
                  <p class="text-sm font-semibold text-purple-800 flex items-center gap-2">
                    <span>❓</span> {{ faq.question }}
                  </p>
                  <p class="text-sm text-gray-600 mt-2 whitespace-pre-line pl-5">{{ faq.answer }}</p>
                </div>
                <button
                  class="ml-4 text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded-md transition font-medium text-sm"
                  :disabled="deletingFaqId === faq.id"
                  @click="deleteFaq(faq.id)"
                >
                  {{ deletingFaqId === faq.id ? '🗑️ Excluindo...' : '🗑️ Excluir' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Adicionar/Editar -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg p-6 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">
          {{ isEditing ? 'Editar Imóvel' : 'Adicionar Novo Imóvel' }}
        </h2>
        <form @submit.prevent="saveProperty">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Título</label>
              <input
                v-model="propertyForm.title"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Descrição</label>
              <textarea
                v-model="propertyForm.description"
                class="w-full px-3 py-2 border rounded-md"
                rows="3"
                required
              ></textarea>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Endereço</label>
              <input
                v-model="propertyForm.address"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Cidade</label>
              <input
                v-model="propertyForm.city"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Estado</label>
              <input
                v-model="propertyForm.state"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">CEP</label>
              <input
                v-model="propertyForm.zip_code"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-bold mb-2">Comunidade</label>
              <select
                v-model.number="propertyForm.community_id"
                class="w-full px-3 py-2 border rounded-md"
              >
                <option :value="null">Usar minha comunidade ({{ authStore.user?.community?.name || 'Padrão' }})</option>
                <option v-for="community in communities" :key="community.id" :value="community.id">
                  {{ community.name }}
                </option>
              </select>
              <p class="text-xs text-gray-500 mt-1">
                {{ propertyForm.community_id ? 'Comunidade específica selecionada' : 'Será usada sua comunidade padrão' }}
              </p>
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Preço (R$)</label>
              <input
                v-model="propertyForm.price"
                type="number"
                step="0.01"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Quartos</label>
              <input
                v-model="propertyForm.bedrooms"
                type="number"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Banheiros</label>
              <input
                v-model="propertyForm.bathrooms"
                type="number"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-bold mb-2">Área (m²)</label>
              <input
                v-model="propertyForm.area"
                type="number"
                class="w-full px-3 py-2 border rounded-md"
                required
              />
            </div>
            <div v-if="isEditing">
              <label class="block text-sm font-bold mb-2">Status</label>
              <select
                v-model="propertyForm.status"
                class="w-full px-3 py-2 border rounded-md"
              >
                <option value="available">Disponível</option>
                <option value="rented">Alugado</option>
                <option value="maintenance">Manutenção</option>
              </select>
            </div>
          </div>

          <!-- Upload de Fotos -->
          <div class="mt-4">
            <label class="block text-sm font-bold mb-2">Fotos (até 5 imagens)</label>

            <!-- Fotos já carregadas -->
            <div v-if="propertyForm.photos.length > 0" class="mb-4">
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div
                  v-for="(photo, index) in propertyForm.photos"
                  :key="index"
                  class="relative group"
                >
                  <img
                    :src="getPhotoUrl(photo)"
                    :alt="`Foto ${index + 1}`"
                    class="w-full h-32 object-cover rounded"
                  />
                  <button
                    type="button"
                    @click="removePhoto(index)"
                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 opacity-0 group-hover:opacity-100 transition"
                  >
                    ✕
                  </button>
                </div>
              </div>
            </div>

            <!-- Botão de Upload -->
            <div v-if="propertyForm.photos.length < 5">
              <label
                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50"
              >
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <svg
                    class="w-8 h-8 mb-3 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    />
                  </svg>
                  <p class="mb-2 text-sm text-gray-500">
                    <span class="font-semibold">Clique para fazer upload</span>
                  </p>
                  <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP (max. 5MB)</p>
                  <p v-if="uploadingPhoto" class="text-xs text-blue-500 mt-2">
                    Enviando...
                  </p>
                </div>
                <input
                  type="file"
                  class="hidden"
                  accept="image/*"
                  multiple
                  @change="handlePhotoUpload"
                  :disabled="uploadingPhoto"
                />
              </label>
            </div>
            <p v-else class="text-sm text-gray-500 mt-2">
              Limite de 5 fotos atingido
            </p>
          </div>

          <!-- Vídeo -->
          <div class="mt-4">
            <label class="block text-sm font-bold mb-2">
              Vídeo (YouTube ou Vimeo) - Opcional
            </label>
            <input
              v-model="propertyForm.video_url"
              type="url"
              placeholder="https://www.youtube.com/watch?v=..."
              class="w-full px-3 py-2 border rounded-md"
            />
            <p class="text-xs text-gray-500 mt-1">
              Cole o link completo do YouTube ou Vimeo
            </p>
          </div>

          <div class="flex justify-end space-x-4 mt-6">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 border rounded-md hover:bg-gray-50"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="uploadingPhoto"
              class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50"
            >
              {{ isEditing ? 'Salvar' : 'Adicionar' }}
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

const currentView = ref('properties');
const properties = ref([]);
const communities = ref([]);
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const uploadingPhoto = ref(false);
const showFaqModal = ref(false);
const selectedPropertyForFaqs = ref(null);
const faqs = ref([]);
const faqsLoading = ref(false);
const faqForm = ref({ question: '', answer: '' });
const savingFaq = ref(false);
const deletingFaqId = ref(null);

const propertyForm = ref({
  title: '',
  description: '',
  address: '',
  city: '',
  state: '',
  zip_code: '',
  community_id: '',
  price: '',
  bedrooms: '',
  bathrooms: '',
  area: '',
  photos: [],
  video_url: '',
  status: 'available',
});

const profileForm = ref({
  name: '',
  email: '',
  phone: '',
  community_id: '',
  password: '',
  password_confirmation: '',
});

const loadProperties = async () => {
  try {
    const response = await axios.get('/api/properties');
    properties.value = response.data.data || response.data;
  } catch (error) {
    console.error('Erro ao carregar imóveis:', error);
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

const openFaqModal = (property) => {
  selectedPropertyForFaqs.value = property;
  faqForm.value = { question: '', answer: '' };
  showFaqModal.value = true;
  loadFaqs(property.id);
};

const closeFaqModal = () => {
  showFaqModal.value = false;
  selectedPropertyForFaqs.value = null;
  faqs.value = [];
  faqForm.value = { question: '', answer: '' };
};

const loadFaqs = async (propertyId) => {
  faqsLoading.value = true;
  try {
    const response = await axios.get(`/api/properties/${propertyId}/faqs`);
    faqs.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar bots/FAQs:', error);
    alert('Não foi possível carregar as respostas do imóvel.');
  } finally {
    faqsLoading.value = false;
  }
};

const saveFaq = async () => {
  if (!selectedPropertyForFaqs.value) return;

  savingFaq.value = true;
  try {
    await axios.post(`/api/properties/${selectedPropertyForFaqs.value.id}/faqs`, faqForm.value);
    faqForm.value = { question: '', answer: '' };
    await loadFaqs(selectedPropertyForFaqs.value.id);
  } catch (error) {
    console.error('Erro ao salvar FAQ:', error);
    alert('Erro ao salvar resposta: ' + (error.response?.data?.message || error.message));
  } finally {
    savingFaq.value = false;
  }
};

const deleteFaq = async (faqId) => {
  if (!selectedPropertyForFaqs.value || !confirm('Excluir esta resposta?')) {
    return;
  }

  deletingFaqId.value = faqId;
  try {
    await axios.delete(`/api/faqs/${faqId}`);
    await loadFaqs(selectedPropertyForFaqs.value.id);
  } catch (error) {
    console.error('Erro ao excluir FAQ:', error);
    alert('Erro ao excluir resposta: ' + (error.response?.data?.message || error.message));
  } finally {
    deletingFaqId.value = null;
  }
};

const openAddModal = () => {
  isEditing.value = false;
  editingId.value = null;
  propertyForm.value = {
    title: '',
    description: '',
    address: '',
    city: '',
    state: '',
    zip_code: '',
    community_id: authStore.user?.community_id || '',
    price: '',
    bedrooms: '',
    bathrooms: '',
    area: '',
    photos: [],
    video_url: '',
    status: 'available',
  };
  showModal.value = true;
};

const openEditModal = (property) => {
  isEditing.value = true;
  editingId.value = property.id;
  propertyForm.value = {
    title: property.title,
    description: property.description,
    address: property.address,
    city: property.city,
    state: property.state,
    zip_code: property.zip_code,
    community_id: property.community_id || authStore.user?.community_id || '',
    price: property.price,
    bedrooms: property.bedrooms,
    bathrooms: property.bathrooms,
    area: property.area,
    photos: property.photos && property.photos.length > 0 ? [...property.photos] : [],
    video_url: property.video_url || '',
    status: property.status,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  isEditing.value = false;
  editingId.value = null;
};

const handlePhotoUpload = async (event) => {
  const files = Array.from(event.target.files);
  if (files.length === 0) return;

  const currentCount = propertyForm.value.photos.length;
  const remainingSlots = 5 - currentCount;

  if (files.length > remainingSlots) {
    alert(`Você pode adicionar no máximo ${remainingSlots} foto(s). Selecionadas: ${files.length}`);
    event.target.value = '';
    return;
  }

  uploadingPhoto.value = true;

  try {
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const formData = new FormData();
      formData.append('image', file);

      const response = await axios.post('/api/upload-image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      if (response.data.success) {
        propertyForm.value.photos.push(response.data.url);
      }
    }
  } catch (error) {
    console.error('Erro ao fazer upload:', error);
    alert('Erro ao fazer upload de uma ou mais imagens');
  } finally {
    uploadingPhoto.value = false;
    event.target.value = '';
  }
};

const removePhoto = (index) => {
  propertyForm.value.photos.splice(index, 1);
};

const getPhotoUrl = (photo) => {
  if (photo.startsWith('http')) {
    return photo;
  }
  return photo;
};

const saveProperty = async () => {
  try {
    const data = {
      ...propertyForm.value,
      photos: propertyForm.value.photos.filter((p) => p && p.trim() !== ''),
    };

    if (isEditing.value) {
      await axios.put(`/api/properties/${editingId.value}`, data);
    } else {
      await axios.post('/api/properties', data);
    }

    closeModal();
    loadProperties();
  } catch (error) {
    console.error('Erro ao salvar imóvel:', error);
    alert('Erro ao salvar imóvel: ' + (error.response?.data?.message || error.message));
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

const viewProperty = (property) => {
  router.push({ name: 'PropertyDetail', params: { id: property.id } });
};

const loadProfileData = () => {
  profileForm.value = {
    name: authStore.user?.name || '',
    email: authStore.user?.email || '',
    phone: authStore.user?.phone || '',
    community_id: authStore.user?.community_id || '',
    password: '',
    password_confirmation: '',
  };
};

const updateProfile = async () => {
  try {
    const data = {
      name: profileForm.value.name,
      email: profileForm.value.email,
      phone: profileForm.value.phone,
      community_id: profileForm.value.community_id,
    };

    // Only include password if it's being changed
    if (profileForm.value.password && profileForm.value.password.trim() !== '') {
      if (profileForm.value.password !== profileForm.value.password_confirmation) {
        alert('As senhas não coincidem');
        return;
      }
      data.password = profileForm.value.password;
      data.password_confirmation = profileForm.value.password_confirmation;
    }

    await axios.put('/api/profile', data);

    // Update the auth store with new user data
    const response = await axios.get('/api/me');
    authStore.user = response.data;

    alert('Perfil atualizado com sucesso!');
    loadProfileData();
  } catch (error) {
    console.error('Erro ao atualizar perfil:', error);
    alert('Erro ao atualizar perfil: ' + (error.response?.data?.message || error.message));
  }
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

onMounted(async () => {
  await authStore.checkAuth();
  loadProperties();
  loadCommunities();
  loadProfileData();
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
