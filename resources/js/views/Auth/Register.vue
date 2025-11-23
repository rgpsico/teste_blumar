<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 py-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <h2 class="text-3xl font-bold text-center mb-6">Registro</h2>

      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Telefone</label>
          <input
            v-model="form.phone"
            type="tel"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Usuário</label>
          <select
            v-model="form.role"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
            <option value="">Selecione...</option>
            <option value="owner">Proprietário</option>
            <option value="tenant">Inquilino</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
          <input
            v-model="form.password"
            type="password"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2">Confirmar Senha</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div v-if="error" class="mb-4 p-3 bg-red-100 text-red-700 rounded">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
        >
          {{ loading ? 'Registrando...' : 'Registrar' }}
        </button>
      </form>

      <div class="mt-4 text-center">
        <router-link to="/login" class="text-blue-500 hover:text-blue-600">
          Já tem uma conta? Faça login
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  name: '',
  email: '',
  phone: '',
  role: '',
  password: '',
  password_confirmation: '',
});

const loading = ref(false);
const error = ref('');

const handleRegister = async () => {
  loading.value = true;
  error.value = '';

  try {
    await authStore.register(form.value);

    switch (authStore.user.role) {
      case 'owner':
        router.push({ name: 'OwnerDashboard' });
        break;
      case 'tenant':
        router.push({ name: 'TenantDashboard' });
        break;
      default:
        router.push({ name: 'Home' });
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Erro ao registrar';
  } finally {
    loading.value = false;
  }
};
</script>
