<template>
<div class="px-6 py-16 max-w-6xl mx-auto">
  <p class="eyebrow mb-2">Curated Picks</p>
  <h1 class="text-3xl font-bold mb-12 text-charcoal">Gallery</h1>

  <section class="mb-16">
    <h2 class="text-xl font-semibold mb-6 text-gold-soft">
      Top 3 Produk Pilihan Admin
    </h2>

    <div
      v-if="adminPicks.length === 0"
      class="text-grey text-sm"
    >
      Belum ada produk pilihan.
    </div>

    <div
      v-else
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6"
    >
      <ProductCard
        v-for="product in adminPicks"
        :key="product.id"
        :product="product"
      />
    </div>
  </section>

  <section>
    <h2 class="text-xl font-semibold mb-6 text-gold-soft">
      Top 3 Produk Terlaris
    </h2>

    <div
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6"
    >
      <ProductCard
        v-for="product in mostLiked"
        :key="product.id"
        :product="product"
      />
    </div>
  </section>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import ProductCard from '../components/ProductCard.vue'

const adminPicks = ref([])
const mostLiked = ref([])

onMounted(async () => {
  const [featuredRes, likedRes] = await Promise.all([
    api.get('/products/featured'),
    api.get('/products/most-liked'),
  ])
  adminPicks.value = featuredRes.data.data
  mostLiked.value = likedRes.data.data
})
</script>