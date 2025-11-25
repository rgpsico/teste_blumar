<template>
  <teleport to="body">
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="modelValue"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="handleClose"
      >
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Container -->
        <div class="flex min-h-screen items-center justify-center p-4">
          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              v-if="modelValue"
              :class="[
                'relative bg-white rounded-2xl shadow-2xl overflow-hidden',
                sizeClasses
              ]"
              @click.stop
            >
              <!-- Header -->
              <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ title }}</h3>
                    <p v-if="subtitle" class="text-sm text-gray-600 mt-1">{{ subtitle }}</p>
                  </div>
                  <button
                    v-if="closeable"
                    @click="handleClose"
                    class="text-gray-400 hover:text-gray-600 transition"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="px-6 py-6 max-h-[calc(100vh-16rem)] overflow-y-auto">
                <slot />
              </div>

              <!-- Footer -->
              <div v-if="$slots.footer || showDefaultFooter" class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end space-x-3">
                <slot name="footer">
                  <button
                    v-if="showDefaultFooter"
                    @click="handleClose"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium"
                  >
                    Cancelar
                  </button>
                  <button
                    v-if="showDefaultFooter"
                    @click="$emit('confirm')"
                    :disabled="confirmLoading"
                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                  >
                    <span v-if="confirmLoading" class="inline-block animate-spin">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                    </span>
                    <span>{{ confirmText }}</span>
                  </button>
                </slot>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { computed, watch } from 'vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    required: true
  },
  subtitle: String,
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl', 'full'].includes(value)
  },
  closeable: {
    type: Boolean,
    default: true
  },
  showDefaultFooter: {
    type: Boolean,
    default: false
  },
  confirmText: {
    type: String,
    default: 'Confirmar'
  },
  confirmLoading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue', 'close', 'confirm']);

const sizeClasses = computed(() => {
  const sizes = {
    sm: 'w-full max-w-md',
    md: 'w-full max-w-2xl',
    lg: 'w-full max-w-4xl',
    xl: 'w-full max-w-6xl',
    full: 'w-full max-w-7xl'
  };
  return sizes[props.size];
});

const handleClose = () => {
  if (props.closeable) {
    emit('update:modelValue', false);
    emit('close');
  }
};

// Prevenir scroll do body quando modal estiver aberto
watch(() => props.modelValue, (isOpen) => {
  if (isOpen) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});
</script>
