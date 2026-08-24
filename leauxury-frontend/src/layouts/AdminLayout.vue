<template>
  <div class="min-h-screen bg-cream flex">
    <header class="md:hidden fixed top-0 left-0 right-0 z-40 glass-strong px-4 py-3 flex items-center justify-between">
      <router-link :to="{ name: 'admin-dashboard' }" class="flex items-center gap-2">
        <img src="/logo.png" alt="Leauxury Industrial" class="h-13 w-auto object-contain" />
        <div class="flex flex-col">
          <span class="text-sm font-medium tracking-[0.12em] text-charcoal">LEAUXURY</span>
          <span class="text-[8px] font-light tracking-[0.3em] text-grey">INDUSTRIAL</span>
        </div>
      </router-link>
      <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-lg text-charcoal hover:bg-taupe/20">{{ sidebarOpen ? '✕' : '☰' }}</button>
    </header>

    <Transition name="fade">
      <div v-if="sidebarOpen" class="md:hidden fixed inset-0 z-40 bg-charcoal/40 backdrop-blur-sm" @click="sidebarOpen = false"></div>
    </Transition>

    <aside v-if="isAuthenticated()" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:sticky top-0 left-0 z-50 md:z-auto w-64 h-screen shrink-0 glass-strong flex flex-col p-6 transform transition-transform duration-300">
      <router-link :to="{ name: 'admin-dashboard' }" class="flex items-center gap-2" @click="closeSidebar">
        <img src="/logo.jpeg" alt="Leauxury Industrial" class="h-14 w-auto object-contain" />
        <div class="flex flex-col">
          <span class="text-lg font-medium tracking-[0.12em] text-charcoal">LEAUXURY</span>
          <span class="text-[10px] font-light tracking-[0.35em] text-grey">INDUSTRIAL</span>
        </div>
      </router-link>

      <nav class="flex flex-col gap-2 text-sm mt-8">
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="{ name: item.name }"
          @click="closeSidebar"
          class="px-4 py-2 rounded-lg text-charcoal/60 hover:bg-taupe/20 hover:text-gold-soft transition-colors"
          active-class="bg-taupe/20 text-charcoal font-medium"
        >
          {{ item.label }}
        </router-link>
      </nav>

      <button @click="handleLogout" class="mt-auto px-4 py-2 rounded-lg text-grey hover:bg-red-500/10 hover:text-red-500 transition-colors text-sm text-left">
        Logout
      </button>
    </aside>

    <main class="flex-1 min-w-0 pt-20 md:pt-0">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { isAuthenticated, logout } = useAuth()
const sidebarOpen = ref(false)

const menuItems = [
  { name: 'admin-dashboard', label: 'Dashboard' },
  { name: 'admin-products', label: 'Products' },
  { name: 'admin-featured', label: 'Featured Products' },
  { name: 'admin-articles', label: 'Articles' },
  { name: 'admin-categories', label: 'Categories' }
]

const closeSidebar = () => sidebarOpen.value = false

const handleLogout = async () => {
  await logout()
  closeSidebar()
  router.push({ name: 'admin-login' })
}
</script>

<style scoped>
.fade-enter-active,.fade-leave-active{transition:opacity .3s ease}
.fade-enter-from,.fade-leave-to{opacity:0}
</style>