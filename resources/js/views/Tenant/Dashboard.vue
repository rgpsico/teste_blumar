<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="text-2xl font-bold text-blue-600">Dashboard Inquilino</div>
          <div class="flex space-x-4 items-center">
            <span class="text-gray-600">{{ authStore.user?.name }}</span>
            <router-link to="/" class="text-gray-700 hover:text-blue-600">
              Home
            </router-link>
            <button @click="handleLogout" class="text-gray-700 hover:text-blue-600">
              Sair
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold mb-8">Meu Perfil</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-2xl font-bold mb-6">Informações Pessoais</h2>

          <form @submit.prevent="updateProfile">
            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Nome</label>
              <input
                v-model="profile.name"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Email</label>
              <input
                v-model="profile.email"
                type="email"
                class="w-full px-3 py-2 border rounded-md bg-gray-100"
                disabled
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Telefone</label>
              <input
                v-model="profile.phone"
                type="tel"
                class="w-full px-3 py-2 border rounded-md"
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Foto (URL)</label>
              <input
                v-model="profile.photo"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                placeholder="https://..."
              />
            </div>

            <div class="mb-6">
              <label class="block text-sm font-bold mb-2">Cartão de Crédito</label>
              <input
                v-model="profile.credit_card"
                type="text"
                class="w-full px-3 py-2 border rounded-md"
                placeholder="**** **** **** ****"
              />
              <p class="text-xs text-gray-500 mt-1">
                Últimos 4 dígitos para referência
              </p>
            </div>

            <button
              type="submit"
              class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
            >
              Salvar Alterações
            </button>
          </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-2xl font-bold mb-6">Meu Imóvel</h2>

          <div v-if="myProperty">
            <div class="mb-4">
              <img
                v-if="myProperty.photos && myProperty.photos[0]"
                :src="myProperty.photos[0]"
                :alt="myProperty.title"
                class="w-full h-48 object-cover rounded"
              />
            </div>

            <h3 class="font-bold text-lg mb-2">{{ myProperty.title }}</h3>
            <p class="text-gray-600 mb-2">
              {{ myProperty.address }}<br />
              {{ myProperty.city }}, {{ myProperty.state }}
            </p>

            <div class="grid grid-cols-3 gap-2 text-center mb-4">
              <div class="bg-gray-100 p-2 rounded">
                <div class="font-bold">{{ myProperty.bedrooms }}</div>
                <div class="text-xs text-gray-600">Quartos</div>
              </div>
              <div class="bg-gray-100 p-2 rounded">
                <div class="font-bold">{{ myProperty.bathrooms }}</div>
                <div class="text-xs text-gray-600">Banheiros</div>
              </div>
              <div class="bg-gray-100 p-2 rounded">
                <div class="font-bold">{{ myProperty.area }}m²</div>
                <div class="text-xs text-gray-600">Área</div>
              </div>
            </div>

            <div class="text-2xl font-bold text-blue-600 mb-2">
              R$ {{ formatPrice(myProperty.price) }}/mês
            </div>

            <div class="mt-4 p-4 bg-gray-100 rounded">
              <h4 class="font-bold mb-2">Proprietário</h4>
              <p class="text-gray-700">{{ myProperty.owner?.name }}</p>
              <p class="text-gray-600 text-sm">{{ myProperty.owner?.phone }}</p>
            </div>
          </div>

          <div v-else class="text-center py-8 text-gray-500">
            Você não está alugando nenhum imóvel no momento.
          </div>
        </div>
      </div>

      <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-bold text-blue-800 mb-2">Dica</h3>
        <p class="text-blue-700 text-sm">
          Mantenha seus dados sempre atualizados para facilitar a comunicação com seu
          proprietário.
        </p>
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

const profile = ref({
  name: '',
  email: '',
  phone: '',
  photo: '',
  credit_card: '',
});

const myProperty = ref(null);

const loadProfile = () => {
  if (authStore.user) {
    profile.value = { ...authStore.user };
  }
};

const loadMyProperty = async () => {
  try {
    const response = await axios.get('/api/properties');
    const allProperties = response.data.data || response.data;
    myProperty.value = allProperties.find(
      (p) => p.tenant_id === authStore.user?.id
    );
  } catch (error) {
    console.error('Erro ao carregar imóvel:', error);
  }
};

const updateProfile = async () => {
  try {
    await authStore.updateProfile(profile.value);
    alert('Perfil atualizado com sucesso!');
  } catch (error) {
    console.error('Erro ao atualizar perfil:', error);
    alert('Erro ao atualizar perfil');
  }
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

onMounted(() => {
  loadProfile();
  loadMyProperty();
});
</script>
