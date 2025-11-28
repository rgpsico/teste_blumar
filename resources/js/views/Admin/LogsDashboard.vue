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
        <h1 class="text-3xl font-bold text-gray-900">Dashboard de Logs</h1>
        <p class="mt-2 text-sm text-gray-600">Visualize estatísticas de acessos e atividades do sistema</p>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
            <input v-model="filters.start_date" type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
            <input v-model="filters.end_date" type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
            <input v-model="filters.user_id" type="text" placeholder="ID do usuário" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rota</label>
            <input v-model="filters.route" type="text" placeholder="Ex: /admin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
          </div>
        </div>
        <div class="mt-4 flex justify-end">
          <button @click="applyFilters" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Aplicar Filtros
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Visitas Hoje</p>
              <p class="text-3xl font-bold text-blue-600">{{ stats.visitas_hoje || 0 }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Visitas Semana</p>
              <p class="text-3xl font-bold text-green-600">{{ stats.visitas_semana || 0 }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Visitas Mês</p>
              <p class="text-3xl font-bold text-purple-600">{{ stats.visitas_mes || 0 }}</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Total Geral</p>
              <p class="text-3xl font-bold text-orange-600">{{ stats.visitas_total || 0 }}</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
              <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Chart -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Acessos por Dia</h2>
        <div class="h-80">
          <Line :data="chartData" :options="chartOptions" />
        </div>
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Logs Recentes</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data/Hora</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuário</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Página</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
              </tr>
              <tr v-else v-for="log in logs" :key="log.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(log.visited_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ log.user?.name || 'Anônimo' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ log.page_url }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ log.ip_address }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

const loading = ref(false)
const stats = ref({})
const logs = ref([])
const graphData = ref([])

const filters = ref({
  start_date: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
  end_date: new Date().toISOString().split('T')[0],
  user_id: '',
  route: ''
})

const chartData = computed(() => ({
  labels: graphData.value.map(d => formatDateShort(d.date)),
  datasets: [
    {
      label: 'Acessos',
      data: graphData.value.map(d => d.count),
      borderColor: 'rgb(59, 130, 246)',
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      tension: 0.4,
      fill: true
    }
  ]
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    },
    title: {
      display: false
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        precision: 0
      }
    }
  }
}

onMounted(() => {
  fetchDashboard()
})

const fetchDashboard = async () => {
  loading.value = true
  try {
    const params = {
      start_date: filters.value.start_date,
      end_date: filters.value.end_date
    }
    if (filters.value.user_id) params.user_id = filters.value.user_id
    if (filters.value.route) params.route = filters.value.route

    const response = await axios.get('/api/admin/logs-dashboard', { params })
    stats.value = response.data.cards
    graphData.value = response.data.grafico.acessos_por_dia
    logs.value = response.data.logs.data
  } catch (error) {
    console.error('Erro ao buscar dashboard:', error)
  } finally {
    loading.value = false
  }
}

const applyFilters = () => {
  fetchDashboard()
}

const formatDate = (date) => {
  return new Date(date).toLocaleString('pt-BR')
}

const formatDateShort = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' })
}
</script>
