<!-- src/views/Contact.vue -->
<template>
  <div class="px-6 py-16 max-w-4xl mx-auto">
    <p class="eyebrow mb-2">Get In Touch</p>
    <h1 class="text-3xl font-bold mb-10 text-charcoal">Contact</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="glass rounded-2xl p-8 space-y-5">
        <div>
          <p class="text-grey text-sm mb-1">Alamat</p>
          <p class="text-charcoal">{{ contactInfo.address }}</p>
        </div>
        <div>
          <p class="text-grey text-sm mb-1">WhatsApp</p>
          <p class="text-charcoal">{{ contactInfo.whatsapp }}</p>
        </div>
        <div>
          <p class="text-grey text-sm mb-1">Email</p>
          <p class="text-charcoal">{{ contactInfo.email }}</p>
        </div>

        <div class="flex gap-4 pt-2">
          <a :href="contactInfo.instagram" target="_blank" class="text-gold-soft hover:text-charcoal text-sm">Instagram</a>
          <a :href="contactInfo.tiktok" target="_blank" class="text-gold-soft hover:text-charcoal text-sm">TikTok</a>
          <a :href="contactInfo.facebook" target="_blank" class="text-gold-soft hover:text-charcoal text-sm">Facebook</a>
        </div>
      </div>

      <div class="glass rounded-2xl overflow-hidden">
        <iframe
          :src="contactInfo.mapEmbedUrl"
          width="100%"
          height="100%"
          style="border:0; min-height: 300px;"
          loading="lazy"
          referrerpolicy="strict-origin-when-cross-origin"
          allowfullscreen
        ></iframe>
      </div>
    </div>
  </div>
</template>

<script setup>
import { contactInfo } from '../config/contact'
</script>