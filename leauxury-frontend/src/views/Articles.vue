<!-- src/views/Articles.vue -->
<template>
  <div class="px-6 py-16 max-w-6xl mx-auto">
    <p class="eyebrow mb-2">Latest Stories</p>
    <h1 class="text-3xl font-bold mb-10 text-charcoal">Artikel</h1>

    <div v-if="loading" class="text-grey">Loading...</div>
    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="article in articles" :key="article.id" class="glass rounded-2xl overflow-hidden hover:shadow-lg transition-all">
        <div class="aspect-video bg-taupe/15">
          <img v-if="article.thumbnail" :src="article.thumbnail" :alt="article.title" class="w-full h-full object-cover" />
        </div>
        <div class="p-5">
          <h3 class="font-medium text-charcoal mb-2">{{ article.title }}</h3>
          <p class="text-grey text-sm mb-4 line-clamp-2">{{ article.excerpt }}</p>
          <router-link
            :to="{ name: 'article-detail', params: { slug: article.slug } }"
            class="text-gold-soft text-sm hover:text-charcoal"
          >
            Baca selengkapnya
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'

const articles = ref([])
const loading = ref(true)

onMounted(async () => {
  const res = await api.get('/articles')
  articles.value = res.data.data
  loading.value = false
})
</script>