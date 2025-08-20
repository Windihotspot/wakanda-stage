<script setup lang="ts">
import { ref, onMounted } from 'vue'
import router from '@/router'
import { RouterView } from 'vue-router'
import { useAutoLogout } from '@/composables/useAutoLogout'
import axios from 'axios'

useAutoLogout()

// check if we already stored the server status in localStorage
const serverOk = localStorage.getItem('serverOk') === 'true'
const checking = ref(!serverOk)

onMounted(async () => {
  if (serverOk) return // skip check if already confirmed

  try {
    await axios.get('https://dev02201.getjupita.com', { timeout: 5000 })
    localStorage.setItem('serverOk', 'true') // remember for next reloads
  } catch {
    localStorage.setItem('serverOk', 'false')
    router.replace('/sitedown')
  } finally {
    checking.value = false
  }
})
</script>

<template>
  <main class="text-neutral-800">
    <!-- Show loader only if not checked yet -->
    <div v-if="checking" class="m-4 h-screen flex flex-col items-center justify-center">
      <v-progress-linear
        indeterminate
        color="blue"
        height="6"
        class="rounded-full w-64"
      />
      <span class="text-gray-500 mt-6">Connecting to server...</span>
    </div>

    <!-- Show routes after check -->
    <RouterView v-else />
  </main>
</template>
