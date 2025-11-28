<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <router-link
          to="/admin"
          class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 mb-4"
        >
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar ao Dashboard
        </router-link>
        <h1 class="text-3xl font-bold text-gray-900">Proprietários</h1>
        <p class="mt-2 text-sm text-gray-600">Gerencie os proprietários cadastrados no sistema</p>
      </div>

      <!-- Actions Bar -->
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative flex-1 max-w-md">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Buscar por nome, email ou documento..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
          />
          <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <button
          @click="openCreateModal"
          class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Adicionar Proprietário</span>
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cidade</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
              </tr>
              <tr v-else-if="owners.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="mt-2">Nenhum proprietário encontrado</p>
                </td>
              </tr>
              <tr v-else v-for="owner in owners" :key="owner.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ owner.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ owner.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ owner.phone || '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ owner.document }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ owner.city || '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                  <button @click="openEditModal(owner)" class="text-blue-600 hover:text-blue-900">
                    Editar
                  </button>
                  <button @click="deleteOwner(owner)" class="text-red-600 hover:text-red-900">
                    Excluir
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
          <div class="text-sm text-gray-700">
            Mostrando <span class="font-medium">{{ pagination.from }}</span> até
            <span class="font-medium">{{ pagination.to }}</span> de
            <span class="font-medium">{{ pagination.total }}</span> resultados
          </div>
          <div class="flex gap-2">
            <button
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100"
            >
              Anterior
            </button>
            <button
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100"
            >
              Próximo
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <div @click="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>

          <!-- Modal panel -->
          <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">
                  {{ isEditing ? 'Editar Proprietário' : 'Novo Proprietário' }}
                </h3>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <form @submit.prevent="saveOwner" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input
                      v-model="form.name"
                      type="text"
                      required
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Nome completo"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="email@exemplo.com"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input
                      v-model="form.phone"
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="(00) 00000-0000"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Documento (CPF/CNPJ) *</label>
                    <input
                      v-model="form.document"
                      type="text"
                      required
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="000.000.000-00"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                    <input
                      v-model="form.zip_code"
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="00000-000"
                    />
                  </div>

                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                    <input
                      v-model="form.address"
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Rua, número, bairro"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                    <input
                      v-model="form.city"
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Cidade"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <input
                      v-model="form.state"
                      type="text"
                      maxlength="2"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                      placeholder="UF"
                    />
                  </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                  <button
                    type="button"
                    @click="closeModal"
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                  >
                    Cancelar
                  </button>
                  <button
                    type="submit"
                    :disabled="saving"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                  >
                    {{ saving ? 'Salvando...' : 'Salvar' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const owners = ref([])
const loading = ref(false)
const searchQuery = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const saving = ref(false)
const pagination = ref({
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0
})

const form = ref({
  id: null,
  name: '',
  email: '',
  phone: '',
  document: '',
  address: '',
  city: '',
  state: '',
  zip_code: ''
})

let searchTimeout = null

const route = useRoute()
const router = useRouter()

onMounted(() => {
  fetchOwners()
  initializeFromRoute()
})

watch(
  () => route.fullPath,
  () => {
    initializeFromRoute()
  }
)

const fetchOwners = async (page = 1) => {
  loading.value = true
  try {
    const params = { page }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    const response = await axios.get('/api/owners', { params })
    owners.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      from: response.data.from,
      to: response.data.to,
      total: response.data.total
    }
  } catch (error) {
    console.error('Erro ao buscar proprietários:', error)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchOwners(1)
  }, 500)
}

const changePage = (page) => {
  fetchOwners(page)
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = {
    id: null,
    name: '',
    email: '',
    phone: '',
    document: '',
    address: '',
    city: '',
    state: '',
    zip_code: ''
  }
  showModal.value = true
}

const openEditModal = (owner) => {
  isEditing.value = true
  form.value = { ...owner }
  showModal.value = true
}

const loadOwnerForEdit = async (ownerId) => {
  try {
    loading.value = true
    const response = await axios.get(`/api/owners/${ownerId}`)
    openEditModal(response.data)
  } catch (error) {
    console.error('Erro ao carregar proprietário para edição:', error)
    alert('Não foi possível carregar os dados do proprietário')
    router.replace({ name: 'AdminOwnerList' })
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showModal.value = false
  if (route.name !== 'AdminOwnerList') {
    router.replace({ name: 'AdminOwnerList' })
  }
}

const saveOwner = async () => {
  saving.value = true
  try {
    if (isEditing.value) {
      await axios.put(`/api/owners/${form.value.id}`, form.value)
    } else {
      await axios.post('/api/owners', form.value)
    }
    closeModal()
    fetchOwners(pagination.value.current_page)
  } catch (error) {
    console.error('Erro ao salvar proprietário:', error)
    const message = error.response?.data?.message || 'Erro ao salvar proprietário'
    alert(message)
  } finally {
    saving.value = false
  }
}

const deleteOwner = async (owner) => {
  if (!confirm(`Tem certeza que deseja excluir ${owner.name}?`)) {
    return
  }

  try {
    await axios.delete(`/api/owners/${owner.id}`)
    fetchOwners(pagination.value.current_page)
  } catch (error) {
    console.error('Erro ao excluir proprietário:', error)
    alert('Erro ao excluir proprietário')
  }
}

const initializeFromRoute = () => {
  if (route.name === 'AdminOwnerCreate') {
    openCreateModal()
  }

  if (route.name === 'AdminOwnerEdit' && route.params.id) {
    loadOwnerForEdit(route.params.id)
  }
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .inline-block,
.modal-leave-active .inline-block {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from .inline-block {
  opacity: 0;
  transform: scale(0.95) translateY(-20px);
}

.modal-leave-to .inline-block {
  opacity: 0;
  transform: scale(0.95) translateY(20px);
}
</style>
