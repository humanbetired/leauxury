<!-- src/views/admin/Categories.vue -->
<template>
  <div class="px-8 py-10 max-w-2xl">
    <p class="eyebrow mb-2">Manage</p>
    <h1 class="text-2xl font-bold text-charcoal mb-8">Categories</h1>

    <form @submit.prevent="handleSubmit" class="glass rounded-2xl p-6 mb-8 flex gap-3">
      <input v-model="form.name" placeholder="Nama kategori..." required class="flex-1 bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft" />
      <button type="submit" :disabled="submitting" class="bg-charcoal text-cream px-6 py-3 rounded-lg font-medium hover:bg-gold-soft hover:text-charcoal transition-colors disabled:opacity-50">
        {{ editingId ? 'Update' : 'Tambah' }}
      </button>
      <button v-if="editingId" type="button" @click="cancelEdit" class="glass px-4 py-3 rounded-lg text-charcoal hover:text-gold-soft text-sm">
        Batal
      </button>
    </form>
    <p v-if="error" class="text-red-500 text-sm mb-6">{{ error }}</p>

    <div v-if="loading" class="text-grey">Loading...</div>
    <div v-else class="glass rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-grey border-b border-taupe/40">
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
                <button @click="startEdit(cat)" class="text-gold-soft hover:text-charcoal text-xs">Edit</button>
                <button @click="handleDelete(cat.id)" class="text-red-500/70 hover:text-red-500 text-xs">Hapus</button>
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

const categories = ref([])
const loading = ref(true)
const submitting = ref(false)
const error = ref('')
const editingId = ref(null)
const form = ref({ name: '' })

const fetchCategories = async () => {
  loading.value = true
  const res = await api.get('/admin/categories')
  categories.value = res.data.data
  loading.value = false
}

const startEdit = (cat) => {
  editingId.value = cat.id
  form.value.name = cat.name
}

const cancelEdit = () => {
  editingId.value = null
  form.value.name = ''
}

const handleSubmit = async () => {
  submitting.value = true
  error.value = ''
  try {
    if (editingId.value) {
      await api.put(`/admin/categories/${editingId.value}`, form.value)
    } else {
      await api.post('/admin/categories', form.value)
    }
    cancelEdit()
    fetchCategories()
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal menyimpan kategori.'
  } finally {
    submitting.value = false
  }
}

const handleDelete = async (id) => {
  if (!confirm('Yakin hapus kategori ini?')) return
  error.value = ''
  try {
    await api.delete(`/admin/categories/${id}`)
    fetchCategories()
  } catch (e) {
    error.value = e.response?.data?.errors?.category?.[0] || 'Gagal menghapus kategori.'
  }
}

onMounted(fetchCategories)
</script>