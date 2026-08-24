<!-- src/views/ProductDetail.vue -->
<template>
  <div
    v-if="product"
    class="px-6 py-16 max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10"
  >
    <div class="glass rounded-sm overflow-hidden">
      <div class="aspect-square bg-taupe/15">
        <img
          v-if="product.images?.length"
          :src="activeImage"
          :alt="product.title"
          class="w-full h-full object-cover"
        />

        <div
          v-else
          class="w-full h-full flex items-center justify-center text-grey"
        >
          Tidak ada gambar
        </div>
      </div>

      <div
        v-if="product.images?.length > 1"
        class="flex gap-2 p-3 overflow-x-auto"
      >
        <button
          v-for="(img, i) in product.images"
          :key="i"
          @click="activeImage = img"
          class="w-16 h-16 rounded-smlg overflow-hidden shrink-0 border"
          :class="
            activeImage === img
              ? 'border-gold-soft'
              : 'border-taupe'
          "
        >
          <img
            :src="img"
            :alt="`${product.title} ${i + 1}`"
            class="w-full h-full object-cover"
          />
        </button>
      </div>
    </div>

    <div>
      <p class="eyebrow mb-2">
        {{ product.category?.name }}
      </p>

      <h1 class="text-3xl font-bold text-charcoal mb-4">
        {{ product.title }}
      </h1>

      <div class="flex gap-6 text-grey text-sm mb-6">
        <span>♥ {{ product.like_count }} suka</span>
        <span>👁 {{ product.view_count }} dilihat</span>
      </div>

      <div class="glass rounded-sm p-4 mb-6 space-y-2 text-sm">
        <p class="text-charcoal/80">
          <span class="text-grey">Material:</span>
          {{ product.material }}
        </p>

        <p
          v-if="product.size"
          class="text-charcoal/80"
        >
          <span class="text-grey">Ukuran:</span>
          {{ product.size }}
        </p>
      </div>

      <p class="text-grey leading-relaxed mb-8">
        {{ product.description }}
      </p>

      <div class="flex flex-col sm:flex-row gap-4">
        <button
          @click="handleLike"
          :disabled="liked"
          class="glass-strong px-6 py-3 rounded-sm text-charcoal hover:text-gold-soft disabled:opacity-50 disabled:cursor-not-allowed transition-all"
        >
          {{ liked ? '♥ Sudah Disukai' : '♡ Suka Produk Ini' }}
        </button>

        <a
          :href="whatsappLink"
          target="_blank"
          rel="noopener noreferrer"
          class="bg-charcoal text-cream px-6 py-3 rounded-sm font-medium text-center hover:bg-gold-soft hover:text-charcoal transition-colors"
        >
          Tanya Produk via WhatsApp
        </a>
      </div>

      <div
        v-if="hasMarketplaceLinks"
        class="mt-4"
      >
        <p class="text-grey text-xs uppercase tracking-wide mb-3">
          Beli di Marketplace
        </p>

        <div class="flex gap-3">
          <!-- Shopee -->
          <a
            v-if="product.shopee_url"
            :href="product.shopee_url"
            target="_blank"
            rel="noopener noreferrer"
            class="w-11 h-11 rounded-full glass flex items-center justify-center text-charcoal hover:text-orange-500 hover:border-gold/60 transition-all"
            title="Shopee"
          >
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
            <path fill="#f4511e" d="M36.683,43H11.317c-2.136,0-3.896-1.679-3.996-3.813l-1.272-27.14C6.022,11.477,6.477,11,7.048,11 h33.904c0.571,0,1.026,0.477,0.999,1.047l-1.272,27.14C40.579,41.321,38.819,43,36.683,43z"></path><path fill="#f4511e" d="M32.5,11.5h-2C30.5,7.364,27.584,4,24,4s-6.5,3.364-6.5,7.5h-2C15.5,6.262,19.313,2,24,2 S32.5,6.262,32.5,11.5z"></path><path fill="#fafafa" d="M24.248,25.688c-2.741-1.002-4.405-1.743-4.405-3.577c0-1.851,1.776-3.195,4.224-3.195 c1.685,0,3.159,0.66,3.888,1.052c0.124,0.067,0.474,0.277,0.672,0.41l0.13,0.087l0.958-1.558l-0.157-0.103 c-0.772-0.521-2.854-1.733-5.49-1.733c-3.459,0-6.067,2.166-6.067,5.039c0,3.257,2.983,4.347,5.615,5.309 c3.07,1.122,4.934,1.975,4.934,4.349c0,1.828-2.067,3.314-4.609,3.314c-2.864,0-5.326-2.105-5.349-2.125l-0.128-0.118l-1.046,1.542 l0.106,0.087c0.712,0.577,3.276,2.458,6.416,2.458c3.619,0,6.454-2.266,6.454-5.158C30.393,27.933,27.128,26.741,24.248,25.688z"></path>
            </svg>
          </a>

          <!-- Tokopedia -->
          <a
            v-if="product.tokopedia_url"
            :href="product.tokopedia_url"
            target="_blank"
            rel="noopener noreferrer"
            class="w-11 h-11 rounded-full glass flex items-center justify-center text-charcoal hover:text-green-600 hover:border-gold/60 transition-all"
            title="Tokopedia"
          >
            <img
              src="/tokped.svg"
              alt="Tokopedia"
              class="w-5 h-5"
            />
          </a>

          <!-- TikTok -->
          <a
            v-if="product.tiktok_shop_url"
            :href="product.tiktok_shop_url"
            target="_blank"
            rel="noopener noreferrer"
            class="w-11 h-11 rounded-full glass flex items-center justify-center text-charcoal hover:text-gold-soft hover:border-gold/60 transition-all"
            title="TikTok Shop"
          >
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                <path d="M41,4H9C6.243,4,4,6.243,4,9v32c0,2.757,2.243,5,5,5h32c2.757,0,5-2.243,5-5V9C46,6.243,43.757,4,41,4z M37.006,22.323 c-0.227,0.021-0.457,0.035-0.69,0.035c-2.623,0-4.928-1.349-6.269-3.388c0,5.349,0,11.435,0,11.537c0,4.709-3.818,8.527-8.527,8.527 s-8.527-3.818-8.527-8.527s3.818-8.527,8.527-8.527c0.178,0,0.352,0.016,0.527,0.027v4.202c-0.175-0.021-0.347-0.053-0.527-0.053 c-2.404,0-4.352,1.948-4.352,4.352s1.948,4.352,4.352,4.352s4.527-1.894,4.527-4.298c0-0.095,0.042-19.594,0.042-19.594h4.016 c0.378,3.591,3.277,6.425,6.901,6.685V22.323z"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div
    v-else
    class="px-6 py-32 text-center text-grey"
  >
    Loading...
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { Icon } from '@iconify/vue'
import api from '../api/axios'
import { useIdentifier } from '../composables/useIdentifier'
import { getWhatsappLink } from '../config/whatsapp'

const route = useRoute()

const product = ref(null)
const liked = ref(false)
const activeImage = ref('')
const identifier = useIdentifier()

const hasMarketplaceLinks = computed(() => {
  return Boolean(
    product.value?.shopee_url ||
    product.value?.tokopedia_url ||
    product.value?.tiktok_shop_url
  )
})

const whatsappLink = computed(() => {
  return product.value
    ? getWhatsappLink(product.value)
    : '#'
})

const recordView = async (id) => {
  try {
    await api.post(`/products/${id}/view`, {
      identifier
    })
  } catch (e) {
    console.error(e)
  }
}

const handleLike = async () => {
  if (!product.value) return

  try {
    const res = await api.post(
      `/products/${product.value.id}/like`,
      {
        identifier
      }
    )

    product.value.like_count = res.data.like_count
    liked.value = true

    localStorage.setItem(
      `liked_${product.value.id}`,
      'true'
    )
  } catch (e) {
    if (e.response?.status === 409) {
      liked.value = true

      localStorage.setItem(
        `liked_${product.value.id}`,
        'true'
      )
    } else {
      console.error(e)
    }
  }
}

const loadProduct = async () => {
  product.value = null

  try {
    const res = await api.get(
      `/products/${route.params.id}`
    )

    product.value = res.data.data

    activeImage.value =
      product.value.images?.[0] || ''

    liked.value =
      localStorage.getItem(
        `liked_${product.value.id}`
      ) === 'true'

    recordView(product.value.id)
  } catch (e) {
    console.error(e)
  }
}

watch(
  () => route.params.id,
  loadProduct
)

onMounted(loadProduct)
</script>