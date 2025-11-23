<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <h2 class="text-3xl font-bold text-center mb-6">Login</h2>

      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Senha
          </label>
          <input
            v-model="form.password"
            type="password"
            id="password"
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
          {{ loading ? 'Entrando...' : 'Entrar' }}
        </button>
      </form>

      <div class="mt-4 text-center">
        <router-link to="/register" class="text-blue-500 hover:text-blue-600">
          Não tem uma conta? Registre-se
        </router-link>
      </div>

      <div class="mt-4 text-center">
        <router-link to="/" class="text-gray-500 hover:text-gray-600">
          Voltar para página inicial
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
  email: '',
  password: '',
});

const loading = ref(false);
const error = ref('');

const handleLogin = async () => {
  loading.value = true;
  error.value = '';

  try {
    await authStore.login(form.value);

    switch (authStore.user.role) {
      case 'admin':
        router.push({ name: 'AdminDashboard' });
        break;
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
    error.value = err.response?.data?.message || 'Erro ao fazer login';
  } finally {
    loading.value = false;
  }
};
</script>
