<!-- src/views/admin/Categories.vue -->
<template>
  <div class="max-w-2xl px-4 py-6 sm:px-8 sm:py-10">
    <p class="eyebrow mb-2">Manage</p>
    <h1 class="mb-6 text-2xl font-bold text-charcoal sm:mb-8">Categories</h1>

    <form @submit.prevent="handleSubmit" class="glass mb-6 flex flex-col gap-3 rounded-2xl p-4 sm:mb-8 sm:flex-row sm:p-6">
      <input v-model="form.name" placeholder="Nama kategori..." required
        class="flex-1 rounded-lg border border-taupe bg-white/60 px-4 py-3 text-charcoal placeholder-grey focus:border-gold-soft focus:outline-none" />

      <div class="flex gap-2 sm:contents">
        <button type="submit" :disabled="submitting"
          class="flex-1 rounded-lg bg-charcoal px-6 py-3 font-medium text-cream transition-colors hover:bg-gold-soft hover:text-charcoal disabled:opacity-50 sm:flex-none">
          {{ editingId ? 'Update' : 'Tambah' }}
        </button>

        <button v-if="editingId" type="button" @click="cancelEdit"
          class="rounded-lg glass px-4 py-3 text-sm text-charcoal hover:text-gold-soft">
          Batal
        </button>
      </div>
    </form>

    <p v-if="error" class="mb-6 text-sm text-red-500">{{ error }}</p>

    <div v-if="loading" class="text-grey">Loading...</div>

    <div v-else class="glass overflow-x-auto rounded-2xl">
      <table class="w-full min-w-125 text-sm">
        <thead>
          <tr class="border-b border-taupe/40 text-left text-grey">
            <th class="p-4 font-normal">Nama</th>
            <th class="p-4 font-normal">Jumlah Produk</th>
            <th class="p-4 font-normal">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="cat in categories" :key="cat.id" class="border-b border-taupe/20">
            <td class="p-4 text-charcoal">{{ cat.name }}</td>
            <td class="p-4 text-grey">{{ cat.products_count }}</td>
            <td class="p-4">
              <div class="flex gap-3">
                <button @click="startEdit(cat)" class="text-xs text-gold-soft hover:text-charcoal">Edit</button>
                <button @click="handleDelete(cat.id)" class="text-xs text-red-500/70 hover:text-red-500">Hapus</button>
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

const categories = ref([])
const loading = ref(true)
const submitting = ref(false)
const error = ref('')
const editingId = ref(null)
const form = ref({ name: '' })

const fetchCategories = async () => {
  loading.value = true
  try {
    const res = await api.get('/admin/categories')
    categories.value = res.data.data
  } finally {
    loading.value = false
  }
}

const startEdit = ({ id, name }) => {
  editingId.value = id
  form.value.name = name
}

const cancelEdit = () => {
  editingId.value = null
  form.value.name = ''
}

const handleSubmit = async () => {
  submitting.value = true
  error.value = ''

  try {
    const url = editingId.value
      ? `/admin/categories/${editingId.value}`
      : '/admin/categories'

    await api[editingId.value ? 'put' : 'post'](url, form.value)
    cancelEdit()
    await fetchCategories()
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal menyimpan kategori.'
  } finally {
    submitting.value = false
  }
}

const handleDelete = async id => {
  if (!confirm('Yakin hapus kategori ini?')) return

  error.value = ''

  try {
    await api.delete(`/admin/categories/${id}`)
    await fetchCategories()
  } catch (e) {
    error.value = e.response?.data?.errors?.category?.[0] || 'Gagal menghapus kategori.'
  }
}

onMounted(fetchCategories)
</script>