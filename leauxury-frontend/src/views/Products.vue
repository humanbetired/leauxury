<!-- src/views/Products.vue -->
<template>
  <div class="px-6 py-16 max-w-6xl mx-auto">
    <p class="eyebrow mb-2">Our Catalog</p>
    <h1 class="text-3xl font-bold mb-8 text-charcoal">Products</h1>

    <div class="glass rounded-sm p-4 flex flex-col md:flex-row gap-4 mb-10">
      <input
        v-model="search"
        @input="onSearchChange"
        placeholder="Cari produk..."
        class="flex-1 bg-white/60 border border-taupe rounded-sm px-4 py-2 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft"
      />

        <select
          v-model="categoryId"
          @change="fetchProducts"
          aria-label="Filter berdasarkan kategori"
          class="bg-white/60 border border-taupe rounded-lg px-4 py-2 text-charcoal focus:outline-none focus:border-gold-soft"
        >
        <option value="">Semua Kategori</option>

        <option
          v-for="cat in categories"
          :key="cat.id"
          :value="cat.id"
        >
          {{ cat.name }}
        </option>
      </select>
    </div>

    <div v-if="loading" class="text-grey">
      Loading...
    </div>

    <div
      v-else-if="products.length === 0"
      class="text-grey"
    >
      Tidak ada produk ditemukan.
    </div>

    <div
      v-else
      class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6"
    >
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>

    <div
      v-if="meta && meta.last_page > 1"
      class="flex items-center justify-center gap-4 mt-10"
    >
      <button
        :disabled="meta.current_page === 1"
        @click="goToPage(meta.current_page - 1)"
        class="glass px-4 py-2 rounded-lg text-charcoal disabled:opacity-30 hover:text-gold-soft transition-colors"
      >
        Prev
      </button>

      <span class="text-grey text-sm">
        Halaman {{ meta.current_page }} / {{ meta.last_page }}
      </span>

      <button
        :disabled="meta.current_page === meta.last_page"
        @click="goToPage(meta.current_page + 1)"
        class="glass px-4 py-2 rounded-lg text-charcoal disabled:opacity-30 hover:text-gold-soft transition-colors"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import ProductCard from '../components/ProductCard.vue'

const products = ref([])
const categories = ref([])
const meta = ref(null)
const loading = ref(true)

const search = ref('')
const categoryId = ref('')
const currentPage = ref(1)

let searchTimeout = null

const fetchProducts = async () => {
  loading.value = true

  try {
    const res = await api.get('/products', {
      params: {
        search: search.value || undefined,
        category_id: categoryId.value || undefined,
        page: currentPage.value,
      },
    })

    products.value = res.data.data
    meta.value = res.data.meta
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

const onSearchChange = () => {
  clearTimeout(searchTimeout)

  searchTimeout = setTimeout(() => {
    currentPage.value = 1
    fetchProducts()
  }, 400)
}

const goToPage = (page) => {
  currentPage.value = page
  fetchProducts()
}

onMounted(async () => {
  try {
    const res = await api.get('/categories')
    categories.value = res.data.data

    await fetchProducts()
  } catch (e) {
    console.error(e)
  }
})
</script>