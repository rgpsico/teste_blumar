<template>
  <div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 space-y-6">
      <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <p class="text-sm text-gray-500">Painel do Super Admin</p>
          <h1 class="text-2xl font-bold text-gray-900">Proprietários</h1>
          <p class="text-sm text-gray-500">Listagem completa dos proprietários cadastrados.</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <router-link
            to="/admin"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:text-blue-600 hover:border-blue-200 shadow-sm"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar ao Dashboard
          </router-link>
          <button
            @click="openCreateModal"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-sm hover:shadow-md"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Proprietário
          </button>
        </div>
      </header>

      <DataTable
        title="Lista de Proprietários"
        subtitle="Gerencie proprietários, atualize dados e controle acessos"
        :columns="columns"
        :data="owners"
        :actions="actions"
        :loading="loading"
        create-button-text="Adicionar Proprietário"
        @create="openCreateModal"
        @edit="openEditModal"
        @delete="deleteOwner"
      >
        <template #cell-name="{ item }">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-semibold">
              {{ item.name?.[0]?.toUpperCase() || '?' }}
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ item.name }}</p>
              <p class="text-xs text-gray-500">{{ item.email }}</p>
            </div>
          </div>
        </template>
      </DataTable>
    </div>

    <transition name="fade">
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 px-4"
        role="dialog"
        aria-modal="true"
      >
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full p-6 space-y-4">
          <header class="flex items-start justify-between">
            <div>
              <p class="text-sm text-gray-500">{{ isEditing ? 'Edite os dados do proprietário' : 'Cadastre um novo proprietário' }}</p>
              <h2 class="text-xl font-bold text-gray-900">{{ isEditing ? 'Editar Proprietário' : 'Novo Proprietário' }}</h2>
            </div>
            <button
              type="button"
              class="text-gray-400 hover:text-gray-600"
              @click="closeModal"
              aria-label="Fechar"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </header>

          <form class="space-y-4" @submit.prevent="submitForm">
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
                  :required="!isEditing"
                  type="password"
                  :placeholder="isEditing ? 'Preencha para alterar' : 'Defina uma senha'"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p class="text-xs text-gray-500 mt-1" v-if="isEditing">Deixe em branco para manter a senha atual.</p>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
              <button
                type="button"
                class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                @click="closeModal"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition disabled:opacity-60"
              >
                <svg v-if="submitting" class="animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span>{{ isEditing ? 'Salvar Alterações' : 'Cadastrar' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const owners = ref([]);
const loading = ref(false);
const showModal = ref(false);
const submitting = ref(false);
const editingOwnerId = ref(null);
const form = ref({ name: '', email: '', phone: '', password: '' });

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome' },
  { key: 'phone', label: 'Telefone' },
  {
    key: 'created_at',
    label: 'Cadastrado em',
    format: (value) => (value ? new Date(value).toLocaleDateString('pt-BR') : '-')
  }
];

const actions = [
  {
    name: 'edit',
    label: 'Editar',
    event: 'edit',
    icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    class: 'text-blue-600 hover:bg-blue-50'
  },
  {
    name: 'delete',
    label: 'Excluir',
    event: 'delete',
    icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
    class: 'text-red-600 hover:bg-red-50'
  }
];

const isEditing = computed(() => Boolean(editingOwnerId.value));

const resetForm = () => {
  form.value = { name: '', email: '', phone: '', password: '' };
};

const loadOwners = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/users');
    const allUsers = Array.isArray(response.data?.data) ? response.data.data : Array.isArray(response.data) ? response.data : [];
    owners.value = allUsers.filter((user) => user?.role === 'owner');
  } catch (error) {
    console.error('Erro ao carregar proprietários:', error);
    owners.value = [];
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  editingOwnerId.value = null;
  resetForm();
  showModal.value = true;
};

const openEditModal = (owner) => {
  editingOwnerId.value = owner.id;
  form.value = {
    name: owner?.name || '',
    email: owner?.email || '',
    phone: owner?.phone || '',
    password: ''
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};

const submitForm = async () => {
  submitting.value = true;
  try {
    const payload = {
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      role: 'owner'
    };

    if (form.value.password) {
      payload.password = form.value.password;
    }

    if (isEditing.value) {
      await axios.put(`/api/users/${editingOwnerId.value}`, payload);
    } else {
      await axios.post('/api/users', payload);
    }

    await loadOwners();
    closeModal();
  } catch (error) {
    console.error('Erro ao salvar proprietário:', error);
    alert('Não foi possível salvar as informações.');
  } finally {
    submitting.value = false;
  }
};

const deleteOwner = async (owner) => {
  if (confirm(`Deseja realmente excluir ${owner.name}?`)) {
    try {
      await axios.delete(`/api/users/${owner.id}`);
      await loadOwners();
    } catch (error) {
      console.error('Erro ao excluir proprietário:', error);
      alert('Não foi possível excluir o proprietário.');
    }
  }
};

onMounted(loadOwners);
</script>
