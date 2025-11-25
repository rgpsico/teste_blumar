<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="text-2xl font-bold text-blue-600">Admin Dashboard</div>
          <div class="flex space-x-4 items-center">
            <span class="text-gray-600">{{ authStore.user?.name }}</span>
            <router-link to="/" class="text-gray-700 hover:text-blue-600">
              Home
            </router-link>
            <button
              @click="handleLogout"
              class="text-gray-700 hover:text-blue-600"
            >
              Sair
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold mb-8">Painel Administrativo</h1>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-gray-500 text-sm font-semibold mb-2">Total de Usuários</h3>
          <div class="text-3xl font-bold text-blue-600">{{ stats.users }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-gray-500 text-sm font-semibold mb-2">Total de Imóveis</h3>
          <div class="text-3xl font-bold text-green-600">{{ stats.properties }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-gray-500 text-sm font-semibold mb-2">Imóveis Alugados</h3>
          <div class="text-3xl font-bold text-purple-600">{{ stats.rented }}</div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Usuários</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="border-b">
                <th class="text-left py-3 px-4">Nome</th>
                <th class="text-left py-3 px-4">Email</th>
                <th class="text-left py-3 px-4">Tipo</th>
                <th class="text-left py-3 px-4">Telefone</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">{{ user.name }}</td>
                <td class="py-3 px-4">{{ user.email }}</td>
                <td class="py-3 px-4">
                  <span
                    class="px-2 py-1 rounded text-sm"
                    :class="{
                      'bg-red-100 text-red-800': user.role === 'admin',
                      'bg-blue-100 text-blue-800': user.role === 'owner',
                      'bg-green-100 text-green-800': user.role === 'tenant',
                    }"
                  >
                    {{ getRoleLabel(user.role) }}
                  </span>
                </td>
                <td class="py-3 px-4">{{ user.phone || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Todos os Imóveis</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="property in properties"
            :key="property.id"
            class="border rounded-lg p-4 hover:shadow-md transition"
          >
            <h3 class="font-bold mb-2 truncate" :title="property.title">{{ property.title }}</h3>
            <p class="text-sm text-gray-600 mb-2">{{ property.city }}</p>
            <div class="text-lg font-bold text-blue-600 mb-2">
              R$ {{ formatPrice(property.price) }}
            </div>
            <div class="flex justify-between text-xs text-gray-500">
              <span>Status: {{ property.status }}</span>
              <span>{{ property.owner?.name }}</span>
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

const stats = ref({ users: 0, properties: 0, rented: 0 });
const users = ref([]);
const properties = ref([]);

const loadData = async () => {
  try {
    const [usersRes, propertiesRes] = await Promise.all([
      axios.get('/api/users'),
      axios.get('/api/properties'),
    ]);

    users.value = usersRes.data.data || usersRes.data;
    properties.value = propertiesRes.data.data || propertiesRes.data;

    stats.value.users = users.value.length;
    stats.value.properties = properties.value.length;
    stats.value.rented = properties.value.filter((p) => p.status === 'rented').length;
  } catch (error) {
    console.error('Erro ao carregar dados:', error);
  }
};

const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrador',
    owner: 'Proprietário',
    tenant: 'Inquilino',
  };
  return labels[role] || role;
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price);
};

const handleLogout = async () => {
  await authStore.logout();
  router.push({ name: 'Home' });
};

onMounted(async () => {
  await authStore.checkAuth();
  loadData();
});
</script>
