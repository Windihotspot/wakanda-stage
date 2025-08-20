<script setup lang="ts">
import { ref, onMounted } from 'vue'
import router from '@/router'
import { RouterView } from 'vue-router'
import { useAutoLogout } from '@/composables/useAutoLogout'
import axios from 'axios'

useAutoLogout()

const checking = ref(true) // block rendering until check is done

onMounted(async () => {
  try {
    await axios.get('https://dev02201.getjupita.com', { timeout: 5000 })
  } catch {
    router.replace('/sitedown') // replace so no "back" to login
  } finally {
    checking.value = false
  }
})
</script>

<template>
  <main class="text-neutral-800">
    <!-- Show loader while checking -->
    <div v-if="checking" class=" m-4 h-screen flex flex-col items-center justify-center">
      <v-progress-linear indeterminate color="blue" height="6" class="rounded-full w-64" />

      <span class="text-gray-500 mt-6">Connecting to server...</span>
    </div>

    <!-- Only show routes after check -->
    <RouterView v-else />
  </main>
</template>
