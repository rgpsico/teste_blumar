<template>
  <footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-white">
    <!-- Banner Ad (Full Width) -->
    <div class="bg-gray-800 py-4">
      <div class="max-w-7xl mx-auto px-4">
        <AdSense
          ad-slot="5555555555"
          ad-format="horizontal"
          :style="{ display: 'block', minHeight: '90px', maxHeight: '120px' }"
        />
      </div>
    </div>

    <!-- Footer Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sobre -->
        <div>
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
            </div>
            <div class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
              FIMOVEIS
            </div>
          </div>
          <p class="text-gray-400 text-sm">
            Encontre o imóvel perfeito para você e sua família. Centenas de opções em comunidades exclusivas.
          </p>
        </div>

        <!-- Links Rápidos -->
        <div>
          <h3 class="font-bold text-lg mb-4">Links Rápidos</h3>
          <ul class="space-y-2">
            <li>
              <router-link to="/" class="text-gray-400 hover:text-white transition text-sm">
                Home
              </router-link>
            </li>
            <li>
              <router-link to="/login" class="text-gray-400 hover:text-white transition text-sm">
                Login
              </router-link>
            </li>
            <li>
              <router-link to="/register" class="text-gray-400 hover:text-white transition text-sm">
                Cadastrar
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contato -->
        <div>
          <h3 class="font-bold text-lg mb-4">Contato</h3>
          <ul class="space-y-3 text-sm text-gray-400">
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              contato@FIMOVEIS.com
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              (11) 9999-9999
            </li>
          </ul>
        </div>
      </div>

      <!-- Copyright -->
      <div class="border-t border-gray-700 mt-8 pt-8 text-center">
        <p class="text-gray-400 text-sm">
          &copy; {{ new Date().getFullYear() }} FIMOVEIS. Todos os direitos reservados.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import AdSense from './AdSense.vue';
</script>
