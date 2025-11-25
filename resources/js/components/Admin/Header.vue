<template>
  <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
      <!-- Left: Mobile menu + Title -->
      <div class="flex items-center space-x-4">
        <button
          @click="$emit('toggle-sidebar')"
          class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition"
        >
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div>
          <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
          <p class="text-sm text-gray-500 mt-0.5">{{ subtitle }}</p>
        </div>
      </div>

      <!-- Right: Search, Notifications, Profile -->
      <div class="flex items-center space-x-4">
        <!-- Search (opcional) -->
        <div class="hidden md:flex items-center">
          <div class="relative">
            <input
              type="text"
              placeholder="Buscar..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <!-- Notifications -->
        <button class="relative p-2 rounded-lg hover:bg-gray-100 transition group">
          <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <span v-if="notificationCount > 0" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative" ref="profileDropdown">
          <button
            @click="showProfileMenu = !showProfileMenu"
            class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition"
          >
            <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-blue-500">
              <img
                v-if="user?.photo"
                :src="user.photo"
                :alt="user.name"
                class="w-full h-full object-cover"
              />
              <div
                v-else
                class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg"
              >
                {{ user?.name?.[0]?.toUpperCase() }}
              </div>
            </div>
            <div class="hidden md:block text-left">
              <p class="text-sm font-semibold text-gray-900">{{ user?.name }}</p>
              <p class="text-xs text-gray-500">{{ user?.role === 'admin' ? 'Administrador' : user?.role }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-500 transition-transform" :class="showProfileMenu ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <div
              v-if="showProfileMenu"
              class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50"
            >
              <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-900">{{ user?.name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ user?.email }}</p>
              </div>

              <a
                @click="$emit('navigate', 'profile')"
                class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition"
              >
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Meu Perfil</span>
              </a>

              <a
                @click="$emit('navigate', 'settings')"
                class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition"
              >
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Configurações</span>
              </a>

              <div class="border-t border-gray-100 mt-2 pt-2">
                <button
                  @click="$emit('logout')"
                  class="flex items-center space-x-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  <span>Sair</span>
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  },
  subtitle: {
    type: String,
    default: ''
  },
  user: {
    type: Object,
    default: () => ({})
  },
  notificationCount: {
    type: Number,
    default: 0
  }
});

defineEmits(['toggle-sidebar', 'navigate', 'logout']);

const showProfileMenu = ref(false);
const profileDropdown = ref(null);

const handleClickOutside = (event) => {
  if (profileDropdown.value && !profileDropdown.value.contains(event.target)) {
    showProfileMenu.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
