<template>
  <div>
    <section class="py-0">
      <div
        class="relative w-full min-h-125 md:min-h-150 overflow-hidden flex items-center"
      >
        <!-- Background Image -->
        <img
          src="/banner.jpeg"
          alt="Leauxury Industrial Furniture"
          class="absolute inset-0 w-full h-full object-cover"
        />

        <!-- Overlay -->
        <div
          class="absolute inset-0 bg-linear-to-r from-black/70 via-black/40 to-black/10"
        ></div>

        <!-- Content -->
        <div class="relative z-10 w-full px-6 md:px-12 lg:px-20 py-16">
          <div class="max-w-3xl">
            <p class="eyebrow mb-4 text-cream">
              Leauxury Industrial
            </p>

            <h1
              class="font-display text-5xl sm:text-6xl md:text-7xl font-medium tracking-tight leading-[0.95] mb-6 text-cream"
            >
              Timeless
              <span class="text-gold-soft">Craftsmanship</span>.
              <br class="hidden sm:block" />
              Modern Living
            </h1>

            <p class="text-cream/80 text-base sm:text-lg mb-8 max-w-xl">
              Furniture industrial berkualitas tinggi, dirancang untuk ruang
              modern yang elegan.
            </p>

            <router-link
              :to="{ name: 'products' }"
              class="inline-block bg-black/30 backdrop-blur-sm border border-black/70 text-white px-8 py-3 rounded-lg hover:bg-black/50 transition-all"
            >
              Lihat Produk
            </router-link>
          </div>
        </div>
      </div>
    </section>
    
    <section class="py-16">
      <div class="max-w-6xl mx-auto px-6 flex items-end justify-between mb-10 flex-wrap gap-4">
        <div>
          <p class="eyebrow mb-2">Our Selection</p>
          <h2 class="text-3xl font-bold text-charcoal">
            Produk <span class="text-gold-soft">Pilihan</span>
          </h2>
        </div>
      </div>

      <div v-if="loadingFeatured" class="text-grey px-6 max-w-6xl mx-auto">
        Loading...
      </div>

      <!-- Mobile: carousel geser -->
      <div v-else class="md:hidden relative">
        <div
          ref="carouselRef"
          class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 px-6 scrollbar-hide"
        >
          <div
            v-for="product in featuredProducts"
            :key="product.id"
            class="snap-start shrink-0 w-[70%] xs:w-[60%] sm:w-[45%]"
          >
            <ProductCard :product="product" />
          </div>
        </div>
      </div>

      <!-- Desktop: grid statis seperti semula -->
      <div v-if="!loadingFeatured" class="hidden md:grid max-w-6xl mx-auto px-6 grid-cols-3 gap-6">
        <ProductCard
          v-for="product in featuredProducts"
          :key="product.id"
          :product="product"
        />
      </div>
    </section>

    <section class="px-6 py-16 max-w-6xl mx-auto">
      <p class="eyebrow mb-2">Latest Stories</p>

      <h2 class="text-3xl font-bold text-charcoal mb-10">
        Artikel <span class="text-gold-soft">Terbaru</span>
      </h2>

      <div v-if="loadingArticles" class="text-grey">
        Loading...
      </div>

      <div
        v-else
        class="grid grid-cols-1 md:grid-cols-3 gap-6"
      >
        <div
          v-for="article in latestArticles"
          :key="article.id"
          class="glass rounded-2xl p-6"
        >
          <h3 class="font-medium text-charcoal mb-2">
            {{ article.title }}
          </h3>

          <p class="text-grey text-sm mb-4 line-clamp-2">
            {{ article.excerpt }}
          </p>

          <router-link
            :to="{ name: 'article-detail', params: { slug: article.slug } }"
            class="text-gold-soft text-sm hover:text-charcoal"
          >
            Baca selengkapnya
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import ProductCard from '../components/ProductCard.vue'

const featuredProducts = ref([])
const latestArticles = ref([])
const loadingFeatured = ref(true)
const loadingArticles = ref(true)
const carouselRef = ref(null)

onMounted(async () => {
  try {
    const res = await api.get('/products/featured')
    featuredProducts.value = res.data.data
  } catch (e) {
    console.error(e)
  } finally {
    loadingFeatured.value = false
  }

  try {
    const res = await api.get('/articles')
    latestArticles.value = res.data.data.slice(0, 3)
  } catch (e) {
    console.error(e)
  } finally {
    loadingArticles.value = false
  }
})
</script>