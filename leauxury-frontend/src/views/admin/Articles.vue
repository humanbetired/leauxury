<!-- src/views/admin/Articles.vue -->
<template>
  <div class="px-8 py-10">
    <div class="flex items-center justify-between mb-8">
      <div>
        <p class="eyebrow mb-2">Manage</p>
        <h1 class="text-2xl font-bold text-charcoal">Articles</h1>
      </div>
      <router-link :to="{ name: 'admin-article-create' }" class="bg-charcoal text-cream px-5 py-2 rounded-lg font-medium hover:bg-gold-soft hover:text-charcoal transition-colors text-sm">
        + Tambah Artikel
      </router-link>
    </div>

    <div v-if="loading" class="text-grey">Loading...</div>
    <div v-else class="glass rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-grey border-b border-taupe/40">
            <th class="p-4 font-normal">Title</th>
            <th class="p-4 font-normal">Status</th>
            <th class="p-4 font-normal">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="a in articles" :key="a.id" class="border-b border-taupe/20">
            <td class="p-4 text-charcoal">{{ a.title }}</td>
            <td class="p-4">
              <span class="text-xs px-2 py-1 rounded-full" :class="a.status === 'published' ? 'bg-gold-soft/15 text-gold-soft' : 'bg-taupe/20 text-grey'">
                {{ a.status }}
              </span>
            </td>
            <td class="p-4">
              <div class="flex gap-3">
                <router-link :to="{ name: 'admin-article-edit', params: { id: a.id } }" class="text-gold-soft hover:text-charcoal text-xs">Edit</router-link>
                <button @click="handleDelete(a.id)" class="text-red-500/70 hover:text-red-500 text-xs">Hapus</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api/axios'

const articles = ref([])
const loading = ref(true)

const fetchArticles = async () => {
  loading.value = true
  const res = await api.get('/admin/articles')
  articles.value = res.data.data
  loading.value = false
}

const handleDelete = async (id) => {
  if (!confirm('Yakin hapus artikel ini?')) return
  await api.delete(`/admin/articles/${id}`)
  fetchArticles()
}

onMounted(fetchArticles)
</script>