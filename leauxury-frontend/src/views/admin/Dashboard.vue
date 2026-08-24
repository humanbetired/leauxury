<!-- src/views/admin/Dashboard.vue -->
<template>
  <div class="px-8 py-10">
    <p class="eyebrow mb-2">Overview</p>
    <h1 class="text-2xl font-bold text-charcoal mb-8">Dashboard</h1>

    <div v-if="loading" class="text-grey">Loading...</div>
    <div v-else>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-2">Total Produk</p>
          <p class="text-3xl font-bold text-gold-soft">{{ stats.total_products }}</p>
        </div>
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-2">Total Artikel</p>
          <p class="text-3xl font-bold text-gold-soft">{{ stats.total_articles }}</p>
        </div>
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-2">Total Likes</p>
          <p class="text-3xl font-bold text-gold-soft">{{ stats.total_likes }}</p>
        </div>
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-2">Total Views</p>
          <p class="text-3xl font-bold text-gold-soft">{{ stats.total_views }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-2">Produk Paling Disukai</p>
          <p class="text-charcoal font-medium">{{ stats.most_liked_product?.title || '-' }}</p>
          <p class="text-gold-soft text-sm mt-1">{{ stats.most_liked_product?.like_count || 0 }} suka</p>
        </div>
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-2">Produk Paling Dilihat</p>
          <p class="text-charcoal font-medium">{{ stats.most_viewed_product?.title || '-' }}</p>
          <p class="text-gold-soft text-sm mt-1">{{ stats.most_viewed_product?.view_count || 0 }} views</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-4">Top 5 Produk Paling Disukai</p>
          <div class="h-72 md:h-64">
            <BarChart :labels="likedLabels" :values="likedValues" label="Likes" color="#C9A56A" />
          </div>
        </div>

        <div class="glass rounded-2xl p-6">
          <p class="text-grey text-xs uppercase tracking-wide mb-4">Top 5 Produk Paling Dilihat</p>
          <div class="h-72 md:h-64">
            <BarChart :labels="viewedLabels" :values="viewedValues" label="Views" color="#B8935A" />
          </div>
        </div>
      </div>

        <div class="glass rounded-2xl p-6 mb-10">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <p class="text-grey text-xs uppercase tracking-wide">Pertumbuhan Produk</p>
            <div class="flex flex-col sm:flex-row gap-3">
              <select
                v-model="selectedProductId"
                @change="fetchGrowth"
                class="w-full sm:w-auto bg-white/60 border border-taupe rounded-lg px-3 py-2 text-charcoal text-sm focus:outline-none focus:border-gold-soft"
              >
                <option value="">Pilih produk...</option>
                <option v-for="p in allProducts" :key="p.id" :value="p.id">{{ p.title }}</option>
              </select>
              <select
                v-model="selectedDays"
                @change="fetchGrowth"
                class="w-full sm:w-auto bg-white/60 border border-taupe rounded-lg px-3 py-2 text-charcoal text-sm focus:outline-none focus:border-gold-soft"
              >
                <option :value="7">7 Hari</option>
                <option :value="30">30 Hari</option>
                <option :value="90">90 Hari</option>
              </select>
            </div>
          </div>

            <div v-if="!selectedProductId" class="h-72 flex items-center justify-center text-grey text-sm">
              Pilih produk untuk melihat grafik pertumbuhan.
            </div>
            <div v-else-if="loadingGrowth" class="h-72 flex items-center justify-center text-grey text-sm">
              Loading...
            </div>
            <div v-else class="h-72">
              <LineChart :labels="growthData.labels" :likes="growthData.likes" :views="growthData.views" />
            </div>
          </div>

      <div class="glass rounded-2xl p-6">
        <p class="text-grey text-xs uppercase tracking-wide mb-4">Top 5 Produk Terlaris</p>
        <div class="overflow-x-auto -mx-6 px-6">
          <table class="w-full text-sm min-w-105">
            <thead>
              <tr class="text-left text-grey border-b border-taupe/40">
                <th class="pb-3 pr-4 font-normal">Produk</th>
                <th class="pb-3 pr-4 font-normal">Likes</th>
                <th class="pb-3 font-normal">Views</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="p in stats.top_5_liked_products" :key="p.id" class="border-b border-taupe/20">
                <td class="py-3 pr-4 text-charcoal">{{ p.title }}</td>
                <td class="py-3 pr-4 text-gold-soft">{{ p.like_count }}</td>
                <td class="py-3 text-grey">{{ p.view_count }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, defineAsyncComponent } from 'vue'
import api from '../../api/axios'

const BarChart = defineAsyncComponent(() => import('../../components/BarChart.vue'))
const LineChart = defineAsyncComponent(() => import('../../components/LineChart.vue'))

const stats = ref({})
const loading = ref(true)

const likedLabels = computed(() => (stats.value.top_5_liked_products || []).map((p) => p.title))
const likedValues = computed(() => (stats.value.top_5_liked_products || []).map((p) => p.like_count))
const viewedLabels = computed(() => (stats.value.top_5_viewed_products || []).map((p) => p.title))
const viewedValues = computed(() => (stats.value.top_5_viewed_products || []).map((p) => p.view_count))

const allProducts = ref([])
const selectedProductId = ref('')
const selectedDays = ref(30)
const growthData = ref({ labels: [], likes: [], views: [] })
const loadingGrowth = ref(false)

const fetchGrowth = async () => {
  if (!selectedProductId.value) return
  loadingGrowth.value = true
  try {
    const res = await api.get(`/admin/products/${selectedProductId.value}/growth`, {
      params: { days: selectedDays.value },
    })
    growthData.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loadingGrowth.value = false
  }
}

onMounted(async () => {
  const res = await api.get('/admin/dashboard')
  stats.value = res.data
  loading.value = false

  const productsRes = await api.get('/admin/products')
  allProducts.value = productsRes.data.data
})
</script>