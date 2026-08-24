<!-- src/views/admin/Products.vue -->
<template>
  <div class="px-8 py-10">
    <div class="flex items-center justify-between mb-8">
      <div>
        <p class="eyebrow mb-2">Manage</p>
        <h1 class="text-2xl font-bold text-charcoal">Products</h1>
      </div>
      <router-link
        :to="{ name: 'admin-product-create' }"
        class="bg-charcoal text-cream px-5 py-2 rounded-lg font-medium hover:bg-gold-soft hover:text-charcoal transition-colors text-sm"
      >
        + Tambah Produk
      </router-link>
    </div>

    <div v-if="loading" class="text-grey">Loading...</div>
    <div v-else class="glass rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-grey border-b border-taupe/40">
            <th class="p-4 font-normal">Title</th>
            <th class="p-4 font-normal">Category</th>
            <th class="p-4 font-normal">Likes</th>
            <th class="p-4 font-normal">Views</th>
            <th class="p-4 font-normal">Featured</th>
            <th class="p-4 font-normal">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in products" :key="p.id" class="border-b border-taupe/20">
            <td class="p-4 text-charcoal">{{ p.title }}</td>
            <td class="p-4 text-grey">{{ p.category?.name }}</td>
            <td class="p-4 text-gold-soft">{{ p.like_count }}</td>
            <td class="p-4 text-grey">{{ p.view_count }}</td>
            <td class="p-4">
              <span v-if="p.is_featured" class="text-gold-soft text-xs">✓ Featured</span>
              <span v-else class="text-grey/50 text-xs">-</span>
            </td>
            <td class="p-4">
              <div class="flex gap-3">
                <router-link :to="{ name: 'admin-product-edit', params: { id: p.id } }" class="text-gold-soft hover:text-charcoal text-xs">
                  Edit
                </router-link>
                <button @click="handleDelete(p.id)" class="text-red-500/70 hover:text-red-500 text-xs">
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
import { ref, onMounted } from 'vue'
import api from '../../api/axios'

const products = ref([])
const loading = ref(true)

const fetchProducts = async () => {
  loading.value = true
  const res = await api.get('/admin/products')
  products.value = res.data.data
  loading.value = false
}

const handleDelete = async (id) => {
  if (!confirm('Yakin hapus produk ini?')) return
  await api.delete(`/admin/products/${id}`)
  fetchProducts()
}

onMounted(fetchProducts)
</script>