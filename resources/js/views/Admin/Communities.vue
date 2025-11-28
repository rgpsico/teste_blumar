<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <router-link to="/admin" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 mb-4">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Voltar ao Dashboard
        </router-link>
        <h1 class="text-3xl font-bold text-gray-900">Comunidades</h1>
        <p class="mt-2 text-sm text-gray-600">Gerencie as comunidades cadastradas no sistema</p>
      </div>

      <!-- Search and Add -->
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="relative flex-1 max-w-md">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Buscar comunidades..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
          />
          <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <button
          @click="openCreateModal"
          class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nova Comunidade
        </button>
      </div>

      <!-- Cards Grid -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="i in 6" :key="i" class="bg-white rounded-xl shadow-sm overflow-hidden animate-pulse">
          <div class="h-48 bg-gray-200"></div>
          <div class="p-6 space-y-3">
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
          </div>
        </div>
      </div>

      <div v-else-if="communities.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <p class="mt-2 text-gray-500">Nenhuma comunidade encontrada</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="community in communities" :key="community.id" class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
          <!-- Image -->
          <div class="relative h-48 bg-gradient-to-br from-purple-400 to-pink-400 overflow-hidden">
            <img
              v-if="community.image"
              :src="`/storage/${community.image}`"
              :alt="community.name"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
            />
            <div v-else class="flex items-center justify-center h-full">
              <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
              </svg>
            </div>
            <div class="absolute top-3 right-3">
              <span v-if="community.active" class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                Ativo
              </span>
              <span v-else class="px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">
                Inativo
              </span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ community.name }}</h3>
            <p v-if="community.description" class="text-sm text-gray-600 mb-4 line-clamp-2">
              {{ community.description }}
            </p>
            <div class="space-y-1 text-sm text-gray-500">
              <div v-if="community.city" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ community.city }}<span v-if="community.state">, {{ community.state }}</span>
              </div>
              <div v-if="community.zip_code" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                CEP: {{ community.zip_code }}
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-2">
              <button
                @click="openEditModal(community)"
                class="flex-1 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition font-medium"
              >
                Editar
              </button>
              <button
                @click="deleteCommunity(community)"
                class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="mt-8 flex justify-center">
        <div class="flex gap-2">
          <button
            @click="changePage(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 hover:bg-gray-50"
          >
            Anterior
          </button>
          <span class="px-4 py-2">
            Página {{ pagination.current_page }} de {{ pagination.last_page }}
          </span>
          <button
            @click="changePage(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 hover:bg-gray-50"
          >
            Próximo
          </button>
        </div>
      </div>
    </div>

    <!-- Modal (simplified for space) -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4">
          <div @click="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
          <div class="relative bg-white rounded-2xl max-w-2xl w-full p-6">
            <h3 class="text-2xl font-bold mb-6">{{ isEditing ? 'Editar' : 'Nova' }} Comunidade</h3>
            <form @submit.prevent="saveCommunity" class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-1">Nome *</label>
                <input v-model="form.name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Descrição</label>
                <textarea v-model="form.description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
              </div>
              <div class="grid grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1">Cidade</label>
                  <input v-model="form.city" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Estado</label>
                  <input v-model="form.state" maxlength="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 uppercase" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">CEP</label>
                  <input v-model="form.zip_code" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Imagem (upload)</label>
                <div class="flex items-center gap-4">
                  <div class="flex-1">
                    <input
                      type="file"
                      accept="image/*"
                      @change="handleImageUpload"
                      class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                    />
                  </div>
                  <div
                    v-if="imagePreview"
                    class="w-20 h-20 rounded-xl overflow-hidden border border-purple-100 shadow-sm hover:shadow-md transition"
                  >
                    <img :src="imagePreview" alt="Pré-visualização" class="w-full h-full object-cover" />
                  </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Formats: JPG, PNG ou WEBP. Tamanho máximo recomendado: 2MB.</p>
              </div>
              <div class="flex items-center">
                <input v-model="form.active" type="checkbox" class="w-4 h-4 text-purple-600 rounded" />
                <label class="ml-2 text-sm">Comunidade ativa</label>
              </div>
              <div class="flex justify-end gap-3 pt-4">
                <button type="button" @click="closeModal" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                  Cancelar
                </button>
                <button type="submit" :disabled="saving" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg disabled:opacity-50">
                  {{ saving ? 'Salvando...' : 'Salvar' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const communities = ref([])
const loading = ref(false)
const searchQuery = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const saving = ref(false)
const pagination = ref({ current_page: 1, last_page: 1 })

const form = ref({
  id: null,
  name: '',
  description: '',
  city: '',
  state: '',
  zip_code: '',
  image: '',
  active: true
})
const imagePreview = ref('')

let searchTimeout = null

onMounted(() => {
  fetchCommunities()
})

const fetchCommunities = async (page = 1) => {
  loading.value = true
  try {
    const params = { page }
    if (searchQuery.value) params.search = searchQuery.value
    const response = await axios.get('/api/communities', { params })
    communities.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page
    }
  } catch (error) {
    console.error('Erro ao buscar comunidades:', error)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => fetchCommunities(1), 500)
}

const changePage = (page) => {
  fetchCommunities(page)
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = { id: null, name: '', description: '', city: '', state: '', zip_code: '', image: '', active: true }
  imagePreview.value = ''
  showModal.value = true
}

const openEditModal = (community) => {
  isEditing.value = true
  form.value = { ...community }
  imagePreview.value = community.image ? `/storage/${community.image}` : ''
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const handleImageUpload = (event) => {
  const file = event.target.files?.[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = (e) => {
    form.value.image = e.target?.result
    imagePreview.value = e.target?.result
  }
  reader.readAsDataURL(file)
}

const saveCommunity = async () => {
  saving.value = true
  try {
    if (isEditing.value) {
      await axios.put(`/api/communities/${form.value.id}`, form.value)
    } else {
      await axios.post('/api/communities', form.value)
    }
    closeModal()
    fetchCommunities(pagination.value.current_page)
  } catch (error) {
    console.error('Erro ao salvar:', error)
    alert('Erro ao salvar comunidade')
  } finally {
    saving.value = false
  }
}

const deleteCommunity = async (community) => {
  if (!confirm(`Excluir ${community.name}?`)) return
  try {
    await axios.delete(`/api/communities/${community.id}`)
    fetchCommunities(pagination.value.current_page)
  } catch (error) {
    console.error('Erro ao excluir:', error)
    alert('Erro ao excluir comunidade')
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
