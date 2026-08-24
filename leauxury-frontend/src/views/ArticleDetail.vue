<!-- src/views/ArticleDetail.vue -->
<template>
  <div v-if="article" class="px-6 py-16 max-w-3xl mx-auto">
    <div class="glass rounded-2xl overflow-hidden mb-8" v-if="article.thumbnail">
      <img :src="article.thumbnail" :alt="article.title" class="w-full aspect-video object-cover" />
    </div>
    <p class="eyebrow mb-2">Article</p>
    <h1 class="text-3xl font-bold text-charcoal mb-6">{{ article.title }}</h1>
    <div class="prose max-w-none text-charcoal/80 leading-relaxed" v-html="article.content"></div>

    <div v-if="article.images?.length" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-10">
      <div v-for="(img, i) in article.images" :key="i" class="glass rounded-xl overflow-hidden aspect-square">
        <img :src="img" class="w-full h-full object-cover" />
      </div>
    </div>
  </div>
  <div v-else class="px-6 py-32 text-center text-grey">Loading...</div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api/axios'

const route = useRoute()
const article = ref(null)

const loadArticle = async () => {
  article.value = null
  const res = await api.get(`/articles/${route.params.slug}`)
  article.value = res.data.data
}

watch(() => route.params.slug, loadArticle)
onMounted(loadArticle)
</script>