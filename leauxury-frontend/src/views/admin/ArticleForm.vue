<!-- src/views/admin/ArticleForm.vue -->
<template>
  <div class="px-8 py-10 max-w-2xl">
    <p class="eyebrow mb-2">{{ isEdit ? 'Edit' : 'Create' }}</p>
    <h1 class="text-2xl font-bold text-charcoal mb-8">{{ isEdit ? 'Edit Artikel' : 'Tambah Artikel' }}</h1>

    <form @submit.prevent="handleSubmit" class="glass rounded-2xl p-8 space-y-5">
      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Judul Artikel</label>
        <input v-model="form.title" required class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft" />
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Ringkasan Singkat</label>
        <textarea v-model="form.excerpt" required rows="2" class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"></textarea>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Isi Artikel</label>
        <textarea v-model="form.content" required rows="10" class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"></textarea>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Thumbnail (gambar utama)</label>
        <input type="file" accept="image/*" @change="handleThumbnailChange" class="w-full text-grey text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-charcoal file:text-cream file:font-medium hover:file:bg-gold-soft hover:file:text-charcoal file:cursor-pointer" />
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Gambar Tambahan (opsional)</label>
        <input type="file" multiple accept="image/*" @change="handleImagesChange" class="w-full text-grey text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-charcoal file:text-cream file:font-medium hover:file:bg-gold-soft hover:file:text-charcoal file:cursor-pointer" />
        <div v-if="existingImages.length" class="flex gap-2 mt-3 flex-wrap">
          <img v-for="(img, i) in existingImages" :key="i" :src="img" class="w-16 h-16 object-cover rounded-lg border border-taupe" />
        </div>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">Status</label>
        <select v-model="form.status" class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft">
          <option value="draft">Draft</option>
          <option value="published">Published</option>
        </select>
      </div>

      <button type="submit" :disabled="submitting" class="w-full bg-charcoal text-cream font-medium rounded-lg py-3 hover:bg-gold-soft hover:text-charcoal transition-colors disabled:opacity-50">
        {{ submitting ? 'Menyimpan...' : 'Simpan' }}
      </button>
      <p v-if="error" class="text-red-500 text-sm text-center">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../api/axios'

const route = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)

const form = ref({ title: '', excerpt: '', content: '', status: 'draft' })
const thumbnail = ref(null)
const images = ref([])
const existingImages = ref([])
const submitting = ref(false)
const error = ref('')

const handleThumbnailChange = (e) => { thumbnail.value = e.target.files[0] }
const handleImagesChange = (e) => { images.value = Array.from(e.target.files) }

const handleSubmit = async () => {
  submitting.value = true
  error.value = ''

  const formData = new FormData()
  formData.append('title', form.value.title)
  formData.append('excerpt', form.value.excerpt)
  formData.append('content', form.value.content)
  formData.append('status', form.value.status)
  if (thumbnail.value) formData.append('thumbnail', thumbnail.value)
  images.value.forEach((file) => formData.append('images[]', file))

  try {
    if (isEdit.value) {
      formData.append('_method', 'PUT')
      await api.post(`/admin/articles/${route.params.id}`, formData)
    } else {
      await api.post('/admin/articles', formData)
    }
    router.push({ name: 'admin-articles' })
  } catch (e) {
    error.value = e.response?.data?.message || 'Gagal menyimpan artikel.'
  } finally {
    submitting.value = false
  }
}

const loadArticle = async () => {
  form.value = { title: '', excerpt: '', content: '', status: 'draft' }
  thumbnail.value = null
  images.value = []
  existingImages.value = []
  error.value = ''

  if (isEdit.value) {
    const res = await api.get(`/admin/articles/${route.params.id}`)
    const a = res.data.data
    form.value = { title: a.title, excerpt: a.excerpt, content: a.content, status: a.status }
    existingImages.value = a.images || []
  }
}

watch(() => route.params.id, loadArticle)
onMounted(loadArticle)
</script>