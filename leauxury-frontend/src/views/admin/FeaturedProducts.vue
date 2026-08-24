<!-- src/views/admin/FeaturedProducts.vue -->
<template>
  <div class="px-8 py-10 max-w-xl">
    <p class="eyebrow mb-2">Manage</p>
    <h1 class="text-2xl font-bold text-charcoal mb-2">Kelola Top 3 Produk Pilihan</h1>
    <p class="text-grey text-sm mb-8">Pilih hingga 3 produk dan tentukan urutannya.</p>

    <div v-if="loading" class="text-grey">Loading...</div>
    <div v-else class="glass rounded-2xl p-8 space-y-5">
      <div v-for="(slot, index) in slots" :key="index">
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Posisi {{ index + 1 }}</label>
        <select v-model="slot.product_id" class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft">
          <option :value="null">-- Kosong --</option>
          <option v-for="p in allProducts" :key="p.id" :value="p.id">{{ p.title }}</option>
        </select>
      </div>

      <button @click="handleSave" :disabled="saving" class="w-full bg-charcoal text-cream font-medium rounded-lg py-3 hover:bg-gold-soft hover:text-charcoal transition-colors disabled:opacity-50">
        {{ saving ? 'Menyimpan...' : 'Simpan' }}
      </button>
      <p v-if="error" class="text-red-500 text-sm text-center">{{ error }}</p>
      <p v-if="success" class="text-gold-soft text-sm text-center">Tersimpan!</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api/axios'

const allProducts = ref([])
const slots = ref([{ product_id: null }, { product_id: null }, { product_id: null }])
const loading = ref(true)
const saving = ref(false)
const error = ref('')
const success = ref(false)

onMounted(async () => {
  const [productsRes, featuredRes] = await Promise.all([
    api.get('/admin/products'),
    api.get('/products/featured'),
  ])
  allProducts.value = productsRes.data.data
  featuredRes.data.data.forEach((product, index) => {
    if (slots.value[index]) slots.value[index].product_id = product.id
  })
  loading.value = false
})

const handleSave = async () => {
  error.value = ''
  success.value = false
  const payload = slots.value
    .map((slot, index) => ({ product_id: slot.product_id, position: index + 1 }))
    .filter((item) => item.product_id !== null)

  const ids = payload.map((p) => p.product_id)
  if (new Set(ids).size !== ids.length) {
    error.value = 'Produk tidak boleh dipilih dua kali di posisi berbeda.'
    return
  }

  saving.value = true
  try {
    await api.post('/admin/products/featured', { products: payload })
    success.value = true
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal menyimpan.'
  } finally {
    saving.value = false
  }
}
</script>