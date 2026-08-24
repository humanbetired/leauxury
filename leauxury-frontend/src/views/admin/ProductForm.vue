<!-- src/views/admin/ProductForm.vue -->
<template>
  <div class="px-8 py-10 max-w-2xl">
    <p class="eyebrow mb-2">{{ isEdit ? 'Edit' : 'Create' }}</p>

    <h1 class="text-2xl font-bold text-charcoal mb-8">
      {{ isEdit ? 'Edit Produk' : 'Tambah Produk' }}
    </h1>

    <form
      @submit.prevent="handleSubmit"
      class="glass rounded-2xl p-8 space-y-5"
    >
      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">
          Kategori
        </label>

        <select
          v-model="form.category_id"
          required
          class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"
        >
          <option value="">Pilih Kategori</option>

          <option
            v-for="cat in categories"
            :key="cat.id"
            :value="cat.id"
          >
            {{ cat.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">
          Judul Produk
        </label>

        <input
          v-model="form.title"
          required
          class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-grey text-xs uppercase tracking-wide block mb-2">
            Material
          </label>

          <input
            v-model="form.material"
            required
            class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"
          />
        </div>

        <div>
          <label class="text-grey text-xs uppercase tracking-wide block mb-2">
            Ukuran (opsional)
          </label>

          <input
            v-model="form.size"
            class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"
          />
        </div>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">
          Deskripsi
        </label>

        <textarea
          v-model="form.description"
          required
          rows="4"
          class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal focus:outline-none focus:border-gold-soft"
        ></textarea>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">
          Link Marketplace (opsional)
        </label>

        <div class="space-y-3">
          <input
            v-model="form.shopee_url"
            type="url"
            placeholder="Link Shopee"
            class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft"
          />

          <input
            v-model="form.tokopedia_url"
            type="url"
            placeholder="Link Tokopedia"
            class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft"
          />

          <input
            v-model="form.tiktok_shop_url"
            type="url"
            placeholder="Link TikTok Shop"
            class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft"
          />
        </div>
      </div>

      <div>
        <label class="text-grey text-xs uppercase tracking-wide block mb-2">
          Foto Produk
        </label>

        <input
          type="file"
          multiple
          accept="image/*"
          @change="handleFileChange"
          class="w-full text-grey text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-charcoal file:text-cream file:font-medium hover:file:bg-gold-soft hover:file:text-charcoal file:cursor-pointer"
        />
      </div>

      <button
        type="submit"
        :disabled="submitting"
        class="w-full bg-charcoal text-cream font-medium rounded-lg py-3 hover:bg-gold-soft hover:text-charcoal transition-colors disabled:opacity-50"
      >
        {{ submitting ? 'Menyimpan...' : 'Simpan' }}
      </button>

      <p
        v-if="error"
        class="text-red-500 text-sm text-center"
      >
        {{ error }}
      </p>
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

const categories = ref([])
const files = ref([])
const submitting = ref(false)
const error = ref('')

const createEmptyForm = () => ({
  category_id: '',
  title: '',
  material: '',
  size: '',
  description: '',
  shopee_url: '',
  tokopedia_url: '',
  tiktok_shop_url: '',
})

const form = ref(createEmptyForm())

const handleFileChange = (e) => {
  files.value = Array.from(e.target.files || [])
}

const handleSubmit = async () => {
  submitting.value = true
  error.value = ''

  const formData = new FormData()

  formData.append('category_id', form.value.category_id)
  formData.append('title', form.value.title)
  formData.append('material', form.value.material)
  formData.append('size', form.value.size || '')
  formData.append('description', form.value.description)
  formData.append('shopee_url', form.value.shopee_url || '')
  formData.append('tokopedia_url', form.value.tokopedia_url || '')
  formData.append('tiktok_shop_url', form.value.tiktok_shop_url || '')

  files.value.forEach((file) => {
    formData.append('images[]', file)
  })

  try {
    if (isEdit.value) {
      formData.append('_method', 'PUT')

      await api.post(
        `/admin/products/${route.params.id}`,
        formData
      )
    } else {
      await api.post('/admin/products', formData)
    }

    router.push({ name: 'admin-products' })
  } catch (e) {
    error.value =
      e.response?.data?.message ||
      'Gagal menyimpan produk.'
  } finally {
    submitting.value = false
  }
}

const loadProduct = async () => {
  form.value = createEmptyForm()
  files.value = []
  error.value = ''

  if (!isEdit.value) {
    return
  }

  try {
    const res = await api.get(
      `/admin/products/${route.params.id}`
    )

    const p = res.data.data

    form.value = {
      category_id: p.category?.id || '',
      title: p.title || '',
      material: p.material || '',
      size: p.size || '',
      description: p.description || '',
      shopee_url: p.shopee_url || '',
      tokopedia_url: p.tokopedia_url || '',
      tiktok_shop_url: p.tiktok_shop_url || '',
    }
  } catch (e) {
    error.value =
      e.response?.data?.message ||
      'Gagal memuat data produk.'
  }
}

onMounted(async () => {
  try {
    const catRes = await api.get('/categories')
    categories.value = catRes.data.data || []

    await loadProduct()
  } catch (e) {
    error.value =
      e.response?.data?.message ||
      'Gagal memuat data.'
  }
})

watch(
  () => route.params.id,
  () => loadProduct()
)
</script>