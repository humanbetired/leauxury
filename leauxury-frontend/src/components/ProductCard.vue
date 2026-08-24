<!-- src/components/ProductCard.vue -->
<template>
  <router-link
    :to="{ name: 'product-detail', params: { id: product.id } }"
    class="relative block rounded-2xl overflow-hidden aspect-square group glass"
  >
    <img
      v-if="product.images.length"
      :src="product.images[0]"
      :alt="product.title"
      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
    />
    <div v-else class="w-full h-full bg-taupe/15"></div>

    <div class="absolute inset-0 bg-linear-to-t from-charcoal/80 via-charcoal/10 to-transparent"></div>

    <div class="absolute bottom-0 left-0 right-0 p-3">
      <div class="flex items-end justify-between gap-3">
        <div class="min-w-0">
          <h3 class="text-cream font-medium text-sm truncate">
            {{ product.title }}
          </h3>

          <h4 class="text-cream/80 text-xs truncate">
            {{ product.material }}
          </h4>

          <h4 class="text-cream/70 text-xs truncate">
            {{ product.size }}
          </h4>
        </div>

        <button
          @click.prevent.stop="handleLike"
          :disabled="liked"
          class="shrink-0 w-8 h-8 rounded-full glass-strong flex items-center justify-center text-charcoal disabled:text-gold-soft transition-colors"
        >
          {{ liked ? '♥' : '♡' }}
        </button>
      </div>
    </div>
  </router-link>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import { useIdentifier } from '../composables/useIdentifier'

const props = defineProps({
  product: { type: Object, required: true },
})

const identifier = useIdentifier()
const liked = ref(false)

const handleLike = async () => {
  if (liked.value) return
  try {
    await api.post(`/products/${props.product.id}/like`, { identifier })
    liked.value = true
    localStorage.setItem(`liked_${props.product.id}`, 'true')
  } catch (e) {
    if (e.response?.status === 409) {
      liked.value = true
      localStorage.setItem(`liked_${props.product.id}`, 'true')
    }
  }
}

onMounted(() => {
  liked.value = localStorage.getItem(`liked_${props.product.id}`) === 'true'
})
</script>