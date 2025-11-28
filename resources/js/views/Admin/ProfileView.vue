<template>
  <div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-6">Meu Perfil</h3>
      <form @submit.prevent="updateProfile" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nome</label>
          <input
            v-model="profileForm.name"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input
            v-model="profileForm.email"
            type="email"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
          <input
            v-model="profileForm.phone"
            type="tel"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
        <div class="pt-4">
          <button
            type="submit"
            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-medium"
          >
            Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();
const profileForm = ref({ name: '', email: '', phone: '' });

const updateProfile = async () => {
  try {
    await axios.put('/api/profile', profileForm.value);
    const response = await axios.get('/api/me');
    authStore.user = response.data;
    alert('Perfil atualizado com sucesso!');
  } catch (error) {
    console.error('Erro ao atualizar perfil:', error);
    alert('Erro ao atualizar perfil');
  }
};

onMounted(async () => {
  await authStore.checkAuth();
  profileForm.value = {
    name: authStore.user?.name || '',
    email: authStore.user?.email || '',
    phone: authStore.user?.phone || ''
  };
});
</script>
