<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-white text-center px-4">
    <div ref="confettiContainer" class="absolute inset-0 z-0"></div>

    <!-- Check Icon -->
    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>

    <h2 class="text-xl font-semibold text-gray-800">Invitation Accepted</h2>

    <div class="mt-4 bg-orange-50 border border-orange-200 text-sm text-gray-700 px-6 py-4 rounded max-w-md">
      <div class="flex items-start">
  <i class="fas fa-info-circle text-blue-500 mr-2 mt-1"></i>
  <p>
    You have successfully accepted to join Jupita
  </p>
</div>

    </div>

    <v-btn class="mt-6" variant="plain" color="primary" @click="goToLogin">
      Click here to login
    </v-btn>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import confetti from 'canvas-confetti'
import { useRouter } from 'vue-router'

const confettiContainer = ref(null)
const router = useRouter()

onMounted(() => {
  // Fire confetti once on page load
  confetti({
    particleCount: 150,
    spread: 70,
    origin: { y: 0.6 },
  })
})

const goToLogin = () => {
  router.push('/')
}
</script>

<style scoped>
.v-btn{
  text-transform: none;
}
</style>
