<template>
  <div class="space-y-6">
    <DataTable
      title="Inquilinos"
      subtitle="Gerencie todos os inquilinos do sistema"
      :columns="columns"
      :data="tenants"
      :actions="tableActions"
      :loading="loading"
      @create="openCreateUserModal"
      @edit="handleEditUser"
      @delete="handleDeleteUser"
    >
      <template #cell-name="{ item }">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold">
            {{ item.name?.[0]?.toUpperCase() || '?' }}
          </div>
          <div>
            <p class="font-semibold text-gray-900">{{ item.name }}</p>
            <p class="text-xs text-gray-500">{{ item.email }}</p>
          </div>
        </div>
      </template>
    </DataTable>

    <Transition name="modal">
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        @click.self="closeModal"
      >
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6">
          <div class="flex items-start justify-between mb-6">
            <div>
              <p class="text-sm text-gray-500">{{ isEditing ? 'Edite os dados do inquilino' : 'Cadastre um novo inquilino' }}</p>
              <h3 class="text-2xl font-bold text-gray-900">{{ isEditing ? 'Editar inquilino' : 'Novo inquilino' }}</h3>
            </div>
            <button @click="closeModal" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <form @submit.prevent="saveTenant" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nome *</label>
                <input
                  v-model="form.name"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  :disabled="isEditing"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Telefone</label>
                <input
                  v-model="form.phone"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Senha {{ isEditing ? '(opcional)' : '*' }}</label>
                <input
                  v-model="form.password"
                  type="password"
                  :required="!isEditing"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar senha {{ isEditing ? '(opcional)' : '*' }}</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  :required="!isEditing"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                />
              </div>
            </div>

            <div class="flex items-center gap-2">
              <input v-model="form.active" type="checkbox" class="w-4 h-4 text-emerald-600 rounded" />
              <span class="text-sm text-gray-700">Inquilino ativo</span>
            </div>

            <div class="flex justify-end gap-3 pt-4">
              <button type="button" @click="closeModal" class="px-5 py-2 border rounded-lg hover:bg-gray-50">
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-lg hover:shadow-lg disabled:opacity-60"
              >
                {{ saving ? 'Salvando...' : 'Salvar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from '../../components/Admin/DataTable.vue';

const columns = [
  { key: 'id', label: 'ID', cellClass: 'font-mono text-gray-500' },
  { key: 'name', label: 'Nome' },
  { key: 'phone', label: 'Telefone' },
  {
    key: 'created_at',
    label: 'Cadastrado em',
    format: (value) => new Date(value).toLocaleDateString('pt-BR')
  }
];

const tableActions = [
  {
    name: 'edit',
    label: 'Editar',
    event: 'edit',
    icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    class: 'text-green-600 hover:bg-green-50'
  },
  {
    name: 'delete',
    label: 'Excluir',
    event: 'delete',
    icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
    class: 'text-red-600 hover:bg-red-50'
  }
];

const tenants = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const isEditing = ref(false);

const form = ref({
  id: null,
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  role: 'tenant',
  active: true
});

const resetForm = () => {
  form.value = {
    id: null,
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    role: 'tenant',
    active: true
  };
};

const loadTenants = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/users');
    const allUsers = Array.isArray(response.data?.data)
      ? response.data.data
      : Array.isArray(response.data)
        ? response.data
        : [];
    tenants.value = allUsers.filter((u) => u && u.role === 'tenant');
  } finally {
    loading.value = false;
  }
};

const openCreateUserModal = () => {
  isEditing.value = false;
  resetForm();
  showModal.value = true;
};

const handleEditUser = (user) => {
  isEditing.value = true;
  form.value = {
    id: user.id,
    name: user.name || '',
    email: user.email || '',
    phone: user.phone || '',
    password: '',
    password_confirmation: '',
    role: 'tenant',
    active: user.active ?? true
  };
  showModal.value = true;
};

const handleDeleteUser = async (user) => {
  if (confirm(`Tem certeza que deseja excluir ${user.name}?`)) {
    try {
      await axios.delete(`/api/users/${user.id}`);
      loadTenants();
    } catch (error) {
      console.error('Erro ao excluir usuário:', error);
      alert('Erro ao excluir usuário');
    }
  }
};

const saveTenant = async () => {
  saving.value = true;
  try {
    if (form.value.password || form.value.password_confirmation) {
      if (form.value.password !== form.value.password_confirmation) {
        alert('As senhas precisam coincidir.');
        saving.value = false;
        return;
      }
    }

    if (isEditing.value) {
      const payload = { ...form.value };
      if (!payload.password) {
        delete payload.password;
        delete payload.password_confirmation;
      }
      // Email n�o editavel: evita enviar modifica��o se alterado no form
      delete payload.email;
      await axios.put(`/api/users/${form.value.id}`, payload);
    } else {
      await axios.post('/api/users', form.value);
    }
    closeModal();
    loadTenants();
  } catch (error) {
    console.error('Erro ao salvar usuário:', error);
    alert('Erro ao salvar usuário');
  } finally {
    saving.value = false;
  }
};

const closeModal = () => {
  showModal.value = false;
};

onMounted(() => {
  loadTenants();
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
