<template>
  <div class="min-h-screen bg-cream flex flex-col">
    <header
      class="absolute top-0 left-0 right-0 z-40 bg-transparent px-4 md:px-8 py-4 flex items-center justify-between"
    >
      <router-link
        :to="{ name: 'home' }"
        class="flex items-center gap-2"
      >
        <img
          src="/logo.png"
          alt="Leauxury Industrial"
          class="h-13 w-auto object-contain"
        />

        <div class="flex flex-col">
          <span
            :class="[
              'text-sm font-medium tracking-[0.12em] transition-colors',
              isHome ? 'text-white/80' : 'text-charcoal'
            ]"
          >
            LEAUXURY
          </span>

          <span
            :class="[
              'text-[8px] font-light tracking-[0.3em] transition-colors',
              isHome ? 'text-white/80' : 'text-grey'
            ]"
          >
            INDUSTRIAL
          </span>
        </div>
      </router-link>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center gap-8 text-sm">
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="{ name: item.name }"
          :class="[
            'font-semibold transition-colors pb-1 border-b-2',
            route.name === item.name
              ? 'text-gold-soft border-gold-soft'
              : isHome
                ? 'text-white/80 hover:text-white border-transparent'
                : 'text-charcoal/70 hover:text-black border-transparent'
          ]"
        >
          {{ item.label }}
        </router-link>
      </nav>

      <!-- Mobile Button -->
      <button
        @click="menuOpen = !menuOpen"
        :class="[
          'md:hidden w-10 h-10 rounded-lg transition-colors',
          isHome
            ? 'text-white hover:bg-white/10'
            : 'text-charcoal hover:bg-taupe/20'
        ]"
      >
        {{ menuOpen ? '✕' : '☰' }}
      </button>
    </header>

    <!-- Mobile Overlay -->
    <Transition name="fade">
      <div
        v-if="menuOpen"
        class="md:hidden fixed inset-0 z-40 bg-charcoal/40 backdrop-blur-sm"
        @click="menuOpen = false"
      ></div>
    </Transition>

    <!-- Mobile Navigation -->
    <Transition name="slide">
      <nav
        v-if="menuOpen"
        class="md:hidden fixed top-0 right-0 z-50 w-64 h-screen glass-strong flex flex-col p-6 gap-2 mt-2"
      >
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="{ name: item.name }"
          @click="menuOpen = false"
          class="px-4 py-3 rounded-lg text-charcoal/70 hover:bg-taupe/20 hover:text-charcoal transition-colors flex items-center justify-between"
          :class="route.name === item.name ? 'bg-gold-soft/15 text-gold-soft font-semibold' : ''"
        >
          {{ item.label }}
          <span
            v-if="route.name === item.name"
            class="w-1.5 h-1.5 rounded-full bg-gold-soft"
          ></span>
        </router-link>
      </nav>
    </Transition>

    <main class="flex-1" :class="{ 'pt-24 md:pt-28': !isHome }">
      <router-view />
    </main>

    <footer
      class="glass-strong mt-20 px-6 py-10 text-center text-grey text-sm"
    >
      <p>
        &copy; {{ new Date().getFullYear() }} Leauxury Industrial.
        All rights reserved.
      </p>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const menuOpen = ref(false)

const isHome = computed(() => route.name === 'home')

const menuItems = [
  { name: 'home', label: 'Home' },
  { name: 'products', label: 'Products' },
  { name: 'gallery', label: 'Gallery' },
  { name: 'articles', label: 'Articles' },
  { name: 'contact', label: 'Contact' },
]
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
}
</style>