<!-- src/views/admin/Articles.vue -->
<template>
  <div class="px-4 py-6 sm:px-8 sm:py-10">
    <div class="mb-6 flex items-center justify-between sm:mb-8">
      <div>
        <p class="eyebrow mb-2">Manage</p>
        <h1 class="text-2xl font-bold text-charcoal">Articles</h1>
      </div>

      <router-link
        :to="{ name: 'admin-article-create' }"
        class="rounded-lg bg-charcoal px-4 py-2 text-sm font-medium text-cream transition-colors hover:bg-gold-soft hover:text-charcoal sm:px-5"
      >
        + Tambah Artikel
      </router-link>
    </div>

    <div v-if="loading" class="text-grey">Loading...</div>

    <div v-else class="glass overflow-x-auto rounded-2xl">
      <table class="w-full min-w-150 text-sm">
        <thead>
          <tr class="border-b border-taupe/40 text-left text-grey">
            <th class="p-4 font-normal">Title</th>
            <th class="p-4 font-normal">Status</th>
            <th class="p-4 font-normal">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="a in articles"
            :key="a.id"
            class="border-b border-taupe/20"
          >
            <td class="p-4 text-charcoal">{{ a.title }}</td>

            <td class="p-4">
              <span
                class="rounded-full px-2 py-1 text-xs"
                :class="
                  a.status === 'published'
                    ? 'bg-gold-soft/15 text-gold-soft'
                    : 'bg-taupe/20 text-grey'
                "
              >
                {{ a.status }}
              </span>
            </td>

            <td class="p-4">
              <div class="flex gap-3">
                <router-link
                  :to="{ name: 'admin-article-edit', params: { id: a.id } }"
                  class="text-xs text-gold-soft hover:text-charcoal"
                >
                  Edit
                </router-link>
                <button
                  @click="handleDelete(a.id)"
                  class="text-xs text-red-500/70 hover:text-red-500"
                >
                  Hapus
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import api from '../../api/axios'

const articles = ref([])
const loading = ref(true)

const fetchArticles = async () => {
  loading.value = true
  const res = await api.get('/admin/articles')
  articles.value = res.data.data
  loading.value = false
}

const handleDelete = async id => {
  if (!confirm('Yakin hapus artikel ini?')) return
  await api.delete(`/admin/articles/${id}`)
  fetchArticles()
}

onMounted(fetchArticles)
</script>