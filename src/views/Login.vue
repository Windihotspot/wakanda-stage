<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
import { useRouter } from 'vue-router'
const router = useRouter()

const loginForm = ref({
  email: '',
  password: '',
  remember: false,
  errors: {
    email: '',
    password: ''
  },
  processing: false
})
const showPassword = ref(false)

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}
// Handle Login Submission
const submitForm = async () => {
  loginForm.value.processing = true
  loginForm.value.errors = {} // Clear previous errors

  try {
    const response = await axios.post('https://staging.getjupita.com/api/login', {
      email: loginForm.value.email,
      password: loginForm.value.password
    })

    console.log('Login successful:', response.data)
    // Extract data from the response properly
    const { token, user, verification_status } = response.data.data

    localStorage.setItem('data', JSON.stringify(response.data.data))

    // Pass the data correctly to the store
    authStore.setAuthData({ token, verification_status, user })

    router.push('/dashboard')

    // Handle success (e.g., store token, redirect user)
  } catch (error) {
    console.log('Login failed:', error.response?.data)

    // Handle errors
    if (error.response?.data?.errors) {
      loginForm.value.errors = error.response.data.errors
    } else {
      loginForm.value.errors.email = 'Invalid email or password'
    }
  } finally {
    loginForm.value.processing = false
  }
}

const images = [
  'https://images.unsplash.com/photo-1705948354275-d55101017fb6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGNyZWRpdCUyMHNlYXJjaHxlbnwwfHwwfHx8MA%3D%3D',
  'https://images.unsplash.com/photo-1654263937085-48fb17a63d66?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGNyZWRpdCUyMHNlYXJjaHxlbnwwfHwwfHx8MA%3D%3D',
  'https://plus.unsplash.com/premium_photo-1702634273888-1999beb6120b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8bmlnZXJpYW4lMjBidXNpbmVzcyUyMHdvbWFuJTIwbWFya2V0fGVufDB8fDB8fHww'
]

const currentImage = ref(images[0])
const showImage = ref(true)
let intervalId

const changeImage = () => {
  showImage.value = false // Start fade-out transition

  setTimeout(() => {
    const currentIndex = images.indexOf(currentImage.value)
    currentImage.value = images[(currentIndex + 1) % images.length]
    showImage.value = true // Start fade-in transition
  }, 500) // Match with CSS transition duration
}

onMounted(() => {
  intervalId = setInterval(changeImage, 6000)
})

onUnmounted(() => {
  clearInterval(intervalId)
})
</script>

<template>
  <div class="flex flex-col md:flex-row h-auto h-screen bg-white">
    <!-- Image Section -->
    <!-- Image Section -->
    <div class="w-full sm:w-full md:w-1/2 h-full justify-center items-center p-4">
      <!-- Logo -->
      <div class="flex items-center px-2">
        <img src="/src/assets/logo.png" alt="Logo" class="w-10 h-10 object-contain" />
        <span class="text-md font-semibold text-[#1F5AA3]">Jupita</span>
      </div>
      <img
        src="/src/assets/new-logo.jpg"
        alt="Onboarding Image"
        class="w-full h-full max-h-full object-contain"
      />
    </div>

    <!-- Login Form Section -->
    <div class="w-full md:w-1/2 pt-8 flex items-center justify-center bg-white h-full">
      <div class="max-w-md w-full px-4 sm:px-6 lg:px-8 py-8 md:py-0">
        <!-- Heading -->
        <h1 class="text-3xl font-bold text-gray-800 text-center">Welcome back!</h1>
        <p class="text-gray-600 mt-2 text-center">
          Provide your login credentials to access your account
        </p>

        <!-- Login Form -->
        <form @submit.prevent="submitForm" class="mt-6 space-y-4">
          <!-- Email Error -->
          <p v-if="loginForm.errors.email" class="text-red-500 text-sm">
            {{ loginForm.errors.email }}
          </p>

          <!-- Email Input -->
          <v-text-field
            type="email"
            label="Email address"
            v-model="loginForm.email"
            variant="outlined"
            color="blue"
          />

          <!-- Password Error -->
          <p v-if="loginForm.errors.password" class="text-red-500 text-sm">
            {{ loginForm.errors.password }}
          </p>

          <!-- Password Input -->
          <v-text-field
            :type="showPassword ? 'text' : 'password'"
            v-model="loginForm.password"
            label="Password"
            variant="outlined"
            color="blue"
          >
            <template #append-inner>
              <i
                :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                @click="togglePasswordVisibility"
                class="cursor-pointer text-black"
              ></i>
            </template>
          </v-text-field>

          <!-- Remember me + Reset -->
          <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2">
              <el-checkbox v-model="loginForm.remember" size="large" />
              <span class="text-sm text-gray-700">Remember me</span>
            </label>

            <RouterLink to="/resetpassword">
              <v-btn
                no-uppercase
                variant="text"
                size="small"
                color="#1f5aa3"
                class="normal-case text-none"
              >
                Forgot password?
              </v-btn>
            </RouterLink>
          </div>

          <div class="bg-red-100 text-red-800 text-sm p-3 rounded">
            <span class="font-bold mr-1">Warning:</span>
            Don’t tick this box if you are using a public or shared device
          </div>

          <!-- Submit Button -->
          <v-btn
            type="submit"
            :loading="loginForm.processing"
            class="w-full text-white font-semibold text-sm custom-btn"
            height="40"
          >
            {{ loginForm.processing ? 'Signing in...' : 'Sign in' }}
          </v-btn>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
  transform: translateX(0);
}
/* Slide Transition */
.slide-enter-active,
.slide-leave-active {
  transition:
    transform 0.4s ease-in-out,
    opacity 0.4s;
}
.slide-enter-from {
  transform: translateX(100%);
  opacity: 0;
}
.slide-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}
.custom-btn {
  background-color: #1f5aa3;
}
.v-btn {
  text-transform: none;
}
</style>
