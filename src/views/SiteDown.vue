<script setup>
import { ref, onMounted } from 'vue'

const imageLoaded = ref(false)

onMounted(() => {
  const img = new Image()
  img.src = '/src/assets/sitedown.jpg'
  img.onload = () => {
    imageLoaded.value = true
  }
})
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-white">
    <!-- Show nothing until image is loaded -->
    <div v-if="imageLoaded" class="flex flex-col items-center text-center p-8">
      <h1 class="text-4xl font-bold text-blue-500">Site currently under maintenance</h1>
      <p class="mt-6">Sorry, we will be back shortly<br /></p>

      <!-- Illustration -->
      <div class="mt-10">
        <img
          src="/src/assets/sitedown.jpg"
          alt="Site Down illustration"
          class="max-w-md w-full"
        />
      </div>
    </div>
  </div>
</template>
