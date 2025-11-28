<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <router-link to="/admin" class="inline-flex items-center text-sm text-gray-600 hover:text-emerald-600 mb-4">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar ao Dashboard
        </router-link>
        <h1 class="text-3xl font-bold text-gray-900">Inquilinos</h1>
        <p class="mt-2 text-sm text-gray-600">CRUD completo com listagem paginada e modais animados.</p>
      </div>

      <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative flex-1 max-w-lg">
          <input
            v-model="filters.search"
            @input="debouncedFetch"
            type="text"
            placeholder="Buscar por nome, email ou documento..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent shadow-sm"
          />
          <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <button
          @click="openCreateModal"
          class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-lg shadow hover:shadow-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Novo Inquilino</span>
        </button>
      </div>

      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriedade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrato</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" v-for="skeleton in 5" :key="skeleton" class="animate-pulse">
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
              </tr>
              <tr v-else-if="tenants.length === 0">
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="mt-2">Nenhum inquilino encontrado</p>
                </td>
              </tr>
              <tr v-else v-for="tenant in tenants" :key="tenant.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-gray-900">{{ tenant.name }}</div>
                  <p class="text-xs text-gray-500">Contrato #{{ tenant.id }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ tenant.contact || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ tenant.email || '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ tenant.document || '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ tenant.property?.title || 'Não vinculado' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(tenant.start_date) }} - {{ tenant.end_date ? formatDate(tenant.end_date) : 'indeterminado' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                  <button @click="openEditModal(tenant)" class="text-emerald-600 hover:text-emerald-800">Editar</button>
                  <button @click="deleteTenant(tenant)" class="text-red-600 hover:text-red-800">Excluir</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

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

    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center sm:p-0">
          <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>
          <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-4 text-white flex items-center justify-between">
              <div>
                <p class="text-xs uppercase tracking-widest">Gestão de Inquilinos</p>
                <h3 class="text-2xl font-bold">{{ isEditing ? 'Editar Inquilino' : 'Novo Inquilino' }}</h3>
              </div>
              <button @click="closeModal" class="text-white/80 hover:text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="px-6 pt-6 pb-4">
              <form @submit.prevent="saveTenant" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input
                      v-model="form.name"
                      required
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                      placeholder="Nome completo"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
                    <input
                      v-model="form.contact"
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                      placeholder="Telefone ou celular"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                      v-model="form.email"
                      type="email"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                      placeholder="email@exemplo.com"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Documento</label>
                    <input
                      v-model="form.document"
                      type="text"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                      placeholder="CPF/CNPJ"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Propriedade vinculada *</label>
                    <select
                      v-model="form.property_id"
                      required
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    >
                      <option value="" disabled>Selecione uma propriedade</option>
                      <option v-for="property in properties" :key="property.id" :value="property.id">
                        {{ property.title }}
                      </option>
                    </select>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Início do contrato *</label>
                    <input
                      v-model="form.start_date"
                      type="date"
                      required
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fim do contrato</label>
                    <input
                      v-model="form.end_date"
                      type="date"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                    />
                  </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                  <button type="button" @click="closeModal" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                    Cancelar
                  </button>
                  <button
                    type="submit"
                    :disabled="saving"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-lg hover:from-emerald-700 hover:to-green-700 transition disabled:opacity-60"
                  >
                    <svg v-if="saving" class="animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span>{{ isEditing ? 'Salvar alterações' : 'Salvar' }}</span>
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
import { onMounted, reactive, ref, computed } from 'vue'
import axios from 'axios'

const debounce = (fn, delay = 300) => {
  let timeout
  return (...args) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => fn(...args), delay)
  }
}

const tenants = ref([])
const properties = ref([])
const loading = ref(false)
const saving = ref(false)
const showModal = ref(false)
const editingTenant = ref(null)

const form = reactive({
  name: '',
  contact: '',
  email: '',
  document: '',
  property_id: '',
  start_date: '',
  end_date: ''
})

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0
})

const filters = reactive({
  search: ''
})

const isEditing = computed(() => Boolean(editingTenant.value))
const debouncedFetch = debounce(() => fetchTenants(), 400)

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('pt-BR')
}

const resetForm = () => {
  form.name = ''
  form.contact = ''
  form.email = ''
  form.document = ''
  form.property_id = ''
  form.start_date = ''
  form.end_date = ''
}

const openCreateModal = () => {
  editingTenant.value = null
  resetForm()
  showModal.value = true
}

const openEditModal = (tenant) => {
  editingTenant.value = tenant
  form.name = tenant.name
  form.contact = tenant.contact
  form.email = tenant.email
  form.document = tenant.document
  form.property_id = tenant.property_id
  form.start_date = tenant.start_date
  form.end_date = tenant.end_date
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const fetchProperties = async () => {
  try {
    const { data } = await axios.get('/api/properties', { params: { per_page: 100 } })
    properties.value = data?.data || data || []
  } catch (error) {
    console.error('Erro ao carregar propriedades', error)
  }
}

const fetchTenants = async (page = 1) => {
  loading.value = true
  try {
    const { data } = await axios.get('/api/tenants', {
      params: {
        page,
        per_page: pagination.per_page,
        search: filters.search || undefined
      }
    })
    tenants.value = data.data || []
    Object.assign(pagination, {
      current_page: data.current_page,
      last_page: data.last_page,
      per_page: data.per_page,
      total: data.total,
      from: data.from,
      to: data.to
    })
  } catch (error) {
    console.error('Erro ao buscar inquilinos', error)
  } finally {
    loading.value = false
  }
}

const changePage = (page) => {
  if (page < 1 || page > pagination.last_page) return
  pagination.current_page = page
  fetchTenants(page)
}

const saveTenant = async () => {
  saving.value = true
  try {
    if (isEditing.value) {
      await axios.put(`/api/tenants/${editingTenant.value.id}`, form)
    } else {
      await axios.post('/api/tenants', form)
    }
    await fetchTenants(pagination.current_page)
    closeModal()
  } catch (error) {
    console.error('Erro ao salvar inquilino', error)
    alert('Não foi possível salvar o inquilino. Verifique os dados e tente novamente.')
  } finally {
    saving.value = false
  }
}

const deleteTenant = async (tenant) => {
  if (!confirm(`Deseja excluir ${tenant.name}?`)) return
  try {
    await axios.delete(`/api/tenants/${tenant.id}`)
    await fetchTenants(pagination.current_page)
  } catch (error) {
    console.error('Erro ao excluir inquilino', error)
    alert('Não foi possível excluir o registro.')
  }
}

onMounted(async () => {
  await Promise.all([fetchProperties(), fetchTenants()])
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: translateY(12px);
}
</style>
