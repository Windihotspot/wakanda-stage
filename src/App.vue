<script setup lang="ts">
import { RouterView } from 'vue-router'
import { useAutoLogout } from '@/composables/useAutoLogout'

useAutoLogout() 
</script>

<template>
  <main class="text-neutral-800">
    <RouterView />
  </main>
</template>
