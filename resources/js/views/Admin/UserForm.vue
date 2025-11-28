<template>
  <div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto px-4 space-y-6">
      <header class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">{{ subtitle }}</p>
          <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
        </div>
        <router-link
          :to="returnRoute"
          class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:text-blue-600 hover:border-blue-200 shadow-sm"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar
        </router-link>
      </header>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form @submit.prevent="submitForm" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nome</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Senha</label>
              <input
                v-model="form.password"
                :required="isCreate"
                type="password"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :placeholder="isCreate ? 'Defina uma senha' : 'Preencha para alterar'"
              />
              <p class="text-xs text-gray-500 mt-1" v-if="!isCreate">Deixe em branco para manter a senha atual.</p>
            </div>
          </div>

          <div class="pt-2">
            <button
              type="submit"
              :disabled="submitting"
              class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition disabled:opacity-60"
            >
              <svg v-if="submitting" class="animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
              </svg>
              <span>{{ isCreate ? 'Cadastrar' : 'Salvar Alterações' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const props = defineProps({
  role: { type: String, required: true },
  mode: { type: String, default: 'create' },
  title: { type: String, required: true },
  subtitle: { type: String, required: true },
  returnRoute: { type: String, required: true }
});

const router = useRouter();
const route = useRoute();
const form = ref({ name: '', email: '', phone: '', password: '' });
const submitting = ref(false);

const isCreate = computed(() => props.mode === 'create');

const loadUser = async () => {
  if (isCreate.value) return;
  try {
    const response = await axios.get(`/api/users/${route.params.id}`);
    const user = response.data?.data || response.data;
    form.value = {
      name: user?.name || '',
      email: user?.email || '',
      phone: user?.phone || '',
      password: ''
    };
  } catch (error) {
    console.error('Erro ao carregar usuário:', error);
  }
};

const submitForm = async () => {
  submitting.value = true;
  try {
    const payload = {
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      role: props.role
    };

    if (form.value.password) {
      payload.password = form.value.password;
    }

    if (isCreate.value) {
      await axios.post('/api/users', payload);
    } else {
      await axios.put(`/api/users/${route.params.id}`, payload);
    }

    router.push({ path: props.returnRoute });
  } catch (error) {
    console.error('Erro ao salvar usuário:', error);
    alert('Não foi possível salvar as informações.');
  } finally {
    submitting.value = false;
  }
};

onMounted(loadUser);
</script>
