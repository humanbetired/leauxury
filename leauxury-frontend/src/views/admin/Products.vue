<!-- src/views/admin/Products.vue -->
<template>
  <div class="px-4 py-6 sm:px-8 sm:py-10">
    <div class="mb-6 flex items-center justify-between sm:mb-8">
      <div>
        <p class="eyebrow mb-2">Manage</p>
        <h1 class="text-2xl font-bold text-charcoal">Products</h1>
      </div>

      <router-link
        :to="{ name: 'admin-product-create' }"
        class="rounded-lg bg-charcoal px-4 py-2 text-sm font-medium text-cream transition-colors hover:bg-gold-soft hover:text-charcoal sm:px-5"
      >
        + Tambah Produk
      </router-link>
    </div>

    <div v-if="loading" class="text-grey">Loading...</div>

    <div v-else class="glass overflow-x-auto rounded-2xl">
      <table class="w-full min-w-175 text-sm">
        <thead>
          <tr class="border-b border-taupe/40 text-left text-grey">
            <th class="p-4 font-normal">Title</th>
            <th class="p-4 font-normal">Category</th>
            <th class="p-4 font-normal">Likes</th>
            <th class="p-4 font-normal">Views</th>
            <th class="p-4 font-normal">Featured</th>
            <th class="p-4 font-normal">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="p in products"
            :key="p.id"
            class="border-b border-taupe/20"
          >
            <td class="p-4 text-charcoal">{{ p.title }}</td>
            <td class="p-4 text-grey">{{ p.category?.name }}</td>
            <td class="p-4 text-gold-soft">{{ p.like_count }}</td>
            <td class="p-4 text-grey">{{ p.view_count }}</td>
            <td class="p-4">
              <span v-if="p.is_featured" class="text-xs text-gold-soft">
                ✓ Featured
              </span>
              <span v-else class="text-xs text-grey/50">-</span>
            </td>
            <td class="p-4">
              <div class="flex gap-3">
                <router-link
                  :to="{ name: 'admin-product-edit', params: { id: p.id } }"
                  class="text-xs text-gold-soft hover:text-charcoal"
                >
                  Edit
                </router-link>
                <button
                  @click="handleDelete(p.id)"
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

const products = ref([])
const loading = ref(true)

const fetchProducts = async () => {
  loading.value = true
  const res = await api.get('/admin/products')
  products.value = res.data.data
  loading.value = false
}

const handleDelete = async id => {
  if (!confirm('Yakin hapus produk ini?')) return
  await api.delete(`/admin/products/${id}`)
  fetchProducts()
}

onMounted(fetchProducts)
</script>