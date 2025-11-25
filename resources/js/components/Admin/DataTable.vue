<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Table Header with Actions -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
        <p v-if="subtitle" class="text-sm text-gray-500 mt-0.5">{{ subtitle }}</p>
      </div>
      <div class="flex items-center space-x-3">
        <slot name="header-actions">
          <button
            v-if="showCreateButton"
            @click="$emit('create')"
            class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-sm hover:shadow-md"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>{{ createButtonText }}</span>
          </button>
        </slot>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <div class="flex flex-col items-center space-y-3">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
        <p class="text-sm text-gray-500">Carregando...</p>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!data || data.length === 0" class="flex flex-col items-center justify-center py-12">
      <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
      </svg>
      <p class="text-gray-500 text-lg mb-2">{{ emptyMessage }}</p>
      <button
        v-if="showCreateButton"
        @click="$emit('create')"
        class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
      >
        {{ createButtonText }}
      </button>
    </div>

    <!-- Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
              :class="column.headerClass"
            >
              {{ column.label }}
            </th>
            <th v-if="actions.length > 0" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Ações
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr
            v-for="(item, index) in data"
            :key="item.id || index"
            class="hover:bg-gray-50 transition"
          >
            <td
              v-for="column in columns"
              :key="column.key"
              class="px-6 py-4 text-sm"
              :class="column.cellClass"
            >
              <slot :name="`cell-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">
                {{ formatCellValue(item, column) }}
              </slot>
            </td>
            <td v-if="actions.length > 0" class="px-6 py-4 text-sm">
              <div class="flex items-center space-x-2">
                <button
                  v-for="action in actions"
                  :key="action.name"
                  @click="$emit(action.event, item)"
                  :class="[
                    'p-2 rounded-lg transition',
                    action.class || 'text-gray-600 hover:bg-gray-100 hover:text-blue-600'
                  ]"
                  :title="action.label"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="action.icon" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && data && data.length > 0" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50">
      <div class="text-sm text-gray-600">
        Mostrando <span class="font-semibold">{{ pagination.from }}</span> a <span class="font-semibold">{{ pagination.to }}</span> de <span class="font-semibold">{{ pagination.total }}</span> registros
      </div>
      <div class="flex items-center space-x-2">
        <button
          @click="$emit('page-change', pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
          Anterior
        </button>
        <div class="flex space-x-1">
          <button
            v-for="page in visiblePages"
            :key="page"
            @click="$emit('page-change', page)"
            :class="[
              'px-3 py-1 rounded-lg text-sm font-medium transition',
              page === pagination.current_page
                ? 'bg-blue-500 text-white'
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            {{ page }}
          </button>
        </div>
        <button
          @click="$emit('page-change', pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
          Próxima
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: String,
  columns: {
    type: Array,
    required: true
  },
  data: {
    type: Array,
    default: () => []
  },
  actions: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  pagination: Object,
  showCreateButton: {
    type: Boolean,
    default: true
  },
  createButtonText: {
    type: String,
    default: 'Criar Novo'
  },
  emptyMessage: {
    type: String,
    default: 'Nenhum registro encontrado'
  }
});

defineEmits(['create', 'view', 'edit', 'delete', 'page-change']);

const getNestedValue = (obj, path) => {
  return path.split('.').reduce((value, key) => value?.[key], obj);
};

const formatCellValue = (item, column) => {
  const value = getNestedValue(item, column.key);

  if (column.format) {
    return column.format(value, item);
  }

  return value ?? '-';
};

const visiblePages = computed(() => {
  if (!props.pagination) return [];

  const current = props.pagination.current_page;
  const last = props.pagination.last_page;
  const delta = 2;
  const pages = [];

  for (let i = Math.max(1, current - delta); i <= Math.min(last, current + delta); i++) {
    pages.push(i);
  }

  return pages;
});
</script>
