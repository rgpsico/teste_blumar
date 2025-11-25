<template>
  <aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl z-50 transition-transform duration-300"
         :class="isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <!-- Logo -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-700">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-bold text-white">Admin Panel</h1>
          <p class="text-xs text-slate-400">FIMOVEIS</p>
        </div>
      </div>
      <button @click="$emit('toggle')" class="lg:hidden text-slate-400 hover:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
      <a
        v-for="item in menuItems"
        :key="item.name"
        @click.prevent="$emit('navigate', item.view)"
        :class="[
          'flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 cursor-pointer group',
          currentView === item.view
            ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/50'
            : 'text-slate-300 hover:bg-slate-700/50 hover:text-white'
        ]"
      >
        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
        </svg>
        <span class="font-medium">{{ item.name }}</span>
        <span v-if="item.badge" class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
          {{ item.badge }}
        </span>
      </a>
    </nav>

    <!-- Footer -->
    <div class="px-4 py-4 border-t border-slate-700">
      <div class="text-xs text-slate-500 text-center">
        <p>v1.0.0</p>
        <p class="mt-1">© 2025 FIMOVEIS</p>
      </div>
    </div>
  </aside>

  <!-- Overlay for mobile -->
  <div
    v-if="isOpen"
    @click="$emit('toggle')"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
  ></div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  currentView: {
    type: String,
    default: 'dashboard'
  }
});

defineEmits(['toggle', 'navigate']);

const menuItems = [
  {
    name: 'Dashboard',
    view: 'dashboard',
    icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
  },
  {
    name: 'Proprietários',
    view: 'owners',
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
  },
  {
    name: 'Inquilinos',
    view: 'tenants',
    icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
  },
  {
    name: 'Imóveis',
    view: 'properties',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
  },
  {
    name: 'Comunidades',
    view: 'communities',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
  },
  {
    name: 'Configurações',
    view: 'settings',
    icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
  },
  {
    name: 'Perfil',
    view: 'profile',
    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
  },
  {
    name: 'Logs do Sistema',
    view: 'logs',
    icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
  }
];
</script>
