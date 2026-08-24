<!-- src/views/admin/Login.vue -->
<template>
  <div class="min-h-screen flex items-center justify-center px-6 bg-cream">
    <div class="glass-strong rounded-2xl p-8 w-full max-w-sm">
      <p class="eyebrow mb-2 text-center">Admin Access</p>
      <h1 class="text-2xl font-bold text-charcoal mb-6 text-center">Login</h1>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          required
          class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft"
        />
        <input
          v-model="password"
          type="password"
          placeholder="Password"
          required
          class="w-full bg-white/60 border border-taupe rounded-lg px-4 py-3 text-charcoal placeholder-grey focus:outline-none focus:border-gold-soft"
        />
        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-charcoal text-cream font-medium rounded-lg py-3 hover:bg-gold-soft hover:text-charcoal transition-colors disabled:opacity-50"
        >
          {{ loading ? 'Loading...' : 'Login' }}
        </button>
        <p v-if="error" class="text-red-500 text-sm text-center">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../../composables/useAuth'

const router = useRouter()
const { login } = useAuth()

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  try {
    await login(email.value, password.value)
    router.push({ name: 'admin-dashboard' })
  } catch (e) {
    if (e.response?.status === 429) {
      error.value = 'Terlalu banyak percobaan login. Coba lagi dalam beberapa saat.'
    } else {
      error.value = e.response?.data?.message || 'Login gagal.'
    }
  } finally {
    loading.value = false
  }
}
</script>