<template>
  <div
    v-if="!showConfettiModal && !showErrorModal"
    class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-lg"
  >
    <div class="flex justify-between items-center pb-2 m-1">
      <div>
        <h2 class="text-xl font-bold">Upload bank statement</h2>
        <p class="font-light mt-1">Upload your Bank statement pdf for analysis.</p>
      </div>
      <!-- TEST BUTTON (remove in production) -->
      <!-- <v-btn color="green" class="mt-4" @click="simulateSuccess"> 🔥 Simulate Success </v-btn> -->

      <div class="mb-4" @click="closeForm">
        <i class="fa-solid fa-close text-black text-3xl"></i>
      </div>
    </div>

    <form @submit.prevent="uploadFile">
      <!-- Drag and Drop Area -->
      <div
        class="border-dashed border-2 border-black-800 p-8 text-center rounded-lg mb-4 cursor-pointer"
        :class="{ 'bg-blue-50 border-blue-600': isDragging }"
        @click="triggerFileInput"
        @dragover.prevent="isDragging = true"
        @dragleave="isDragging = false"
        @drop.prevent="handleDrop"
      >
        <input ref="fileInput" type="file" accept=".pdf" class="hidden" @change="onFileSelect" />

        <v-icon size="48" class="text-blue-500">mdi-cloud-upload</v-icon>
        <p class="text-gray-700 font-medium">
          Drag 'n' drop your file here, or
          <span class="text-blue-600 cursor-pointer underline">Browse</span>
        </p>
        <p class="text-gray-500 text-sm mt-2">Max 10MB files are allowed</p>
      </div>
      <!-- Selected File Info -->
      <div v-if="selectedFile" class="border rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
          <div class="flex items-center gap-2">
            <i class="fas fa-file-pdf text-red-600"></i>
            <p class="text-gray-700 text-sm">{{ selectedFile.name }} ({{ fileSize }} MB)</p>
          </div>

          <i class="fas fa-trash-alt text-red-600 cursor-pointer" @click="removeFile"></i>
        </div>

        <div
          v-if="loading"
          class="absolute inset-0 border bg-white rounded-lg shadow-2xl bg-opacity-90 flex flex-col items-center justify-center z-50 p-8 space-y-6"
        >
          <img src="/src/assets/relax.png" class="w-35 h-50 mb-4" alt="" />
          <p class="text-md font-semibold text-center">
            🧘Please sit and relax while we process your file…
          </p>
          <v-progress-circular indeterminate color="blue" size="48" />
        </div>

        <p class="text-gray-600 text-sm font-semibold mb-2">Select a Statement Type</p>
        <div class="mb-4">
          <div class="mb-4">
            <div class="flex flex-wrap gap-6 mt-2">
              <div
                v-for="(type, index) in statementTypes"
                :key="index"
                class="flex items-center space-x-2 cursor-pointer transition"
              >
                <input
                  type="radio"
                  :id="type.value"
                  :value="type.value"
                  v-model="statementType"
                  name="statement-type"
                  class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500"
                />
                <label :for="type.value" class="text-gray-700 font-medium">
                  {{ type.label }}
                </label>
              </div>
            </div>

            <p class="text-gray-600 text-sm font-semibold mt-6 mb-2">Bank statement password</p>
            <v-text-field
              v-model="filePassword"
              label="File Password (for password protected files)"
              variant="outlined"
              class="mt-6"
            ></v-text-field>
          </div>
        </div>
      </div>

      <div class="flex justify-between items-center mt-6">
        <v-btn text color="gray" @click="closeForm"> Close </v-btn>

        <v-btn
          color="#1f5aa3"
          :disabled="!selectedFile"
          class="custom-btn text-white"
          type="submit"
        >
          Analyze Statement
        </v-btn>
      </div>
    </form>
  </div>

  <!-- ConfettiSuccessModal.vue -->
  <div v-if="showConfettiModal" class="fixed inset-0 z-50 flex items-center justify-center w-full">
    <div class="bg-white p-8 rounded-lg shadow-xl text-center relative">
      <!-- Confetti canvas -->
      <canvas
        ref="confettiCanvas"
        class="absolute top-0 left-0 w-full h-full pointer-events-none z-0"
      ></canvas>

      <!-- Success content -->
      <div class="relative z-10 items-center justify-center">
        <div class="mt-8">
          <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
        </div>

        <p class="text-gray-600">Your bank statement has been added successfully.</p>

        <v-btn color="#1f5aa3" variant="plain" @click="$emit('close')" class="custom-btn m-4">
          Return Home
        </v-btn>
      </div>
    </div>
  </div>

  <!-- Error Modal -->
  <v-dialog v-model="showErrorModal" max-width="500px" persistent>
    <template #default>
      <div class="p-6 text-center relative bg-white rounded-lg shadow-xl">
        <div class="text-red-500 text-6xl mb-4">
          <v-icon size="64">mdi-alert-circle</v-icon>
        </div>
        <h2 class="text-xl font-bold mb-2">Upload Failed</h2>
        <p class="text-gray-700">{{ errorMessage }}</p>
        <v-btn
          color="red"
          variant="plain"
          class="mt-6 text-white custom-btn"
           @click="() => { showErrorModal = false; closeForm() }"
        >
          Close
        </v-btn>
      </div>
    </template>
  </v-dialog>
</template>

<script setup>
import { ElNotification } from 'element-plus'
import { ref, computed, defineProps, onMounted, watch, nextTick } from 'vue'
import axios from 'axios'
const loading = ref(false)
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
const token = computed(() => authStore.token)
const tenantId = computed(() => authStore.tenant_id)
const emit = defineEmits(['close'])
import confetti from 'canvas-confetti'

const props = defineProps({
  onSuccess: Function,
  visible: Boolean
})
const showConfettiModal = ref(false)
const confettiCanvas = ref(null)

const showErrorModal = ref(false)
const errorMessage = ref('')

const selectedFile = ref(null)
const filePassword = ref('')
const fileInput = ref(null)
const isDragging = ref(false)
const selectedTypes = ref(null)
const closeForm = () => {
  // Emit the close event to the parent component (if needed)
  emit('close')
}

const simulateSuccess = () => {
  // pretend upload succeeded
  loading.value = false
  selectedFile.value = null
  statementType.value = ''
  filePassword.value = ''
  showConfettiModal.value = true
}

const statementType = ref('')

const statementTypes = [
  { label: 'Individual', value: 'consumer' },
  { label: 'Business', value: 'business' }
]

const handleDrop = (event) => {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  validateFile(file)
}

const fileSize = computed(() =>
  selectedFile.value ? (selectedFile.value.size / 1024 / 1024).toFixed(2) : '0'
)

const triggerFileInput = () => {
  fileInput.value.click()
}

const onFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    selectedFile.value = file
  }
}

const removeFile = () => {
  selectedFile.value = null
}

const progress = ref(0) // Track progress

const uploadFile = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  console.log(savedAuth)

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const tenantId = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  if (!selectedFile.value || !statementType.value) {
    ElNotification({
      title: 'No Statement type',
      message: 'Select a statement type!',
      type: 'warning',
      position: 'top-right',
      showClose: true
    })
    return
  }

  const formData = new FormData()
  formData.append('file', selectedFile.value)
  formData.append('statementType', statementType.value)
  if (filePassword.value) {
    formData.append('password', filePassword.value)
  }

  const API_URL = `${import.meta.env.VITE_API_BASE_URL}/api/${tenantId}/bank-statement-analyze`

  console.log('➡️ Uploading file...')
  for (const [key, value] of formData.entries()) {
    console.log(`   📌 ${key}:`, value instanceof Blob ? value.name : value)
  }

  try {
    loading.value = true

    // Axios upload with progress
    const response = await axios.post(API_URL, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json'
      },
      onUploadProgress: (event) => {
        if (event.lengthComputable) {
          // Calculate the percentage of the file uploaded
          progress.value = Math.round((100 * event.loaded) / event.total)
        }
      }
    })

    console.log('✅ Success:', response)
    if (props.onSuccess) {
      props.onSuccess()
    }
    ElNotification({
      title: 'Success',
      message: 'File uploaded!',
      type: 'success',
      position: 'top-right',
      showClose: true
    })

    console.log('🎉 Showing modal')
    setTimeout(() => {
      showConfettiModal.value = true
    }, 200)

    // Reset input values
    selectedFile.value = null
    filePassword.value = ''
    statementType.value = ''
  } catch (error) {
    // Reset input values
    selectedFile.value = null
    filePassword.value = ''
    statementType.value = ''
    console.log('❌ Upload error:', error.response?.data?.data?.error)

    errorMessage.value = 'An error occurred during upload.' // <-- update ref, not new let

    const rawError = error.response?.data?.data?.error
    if (typeof rawError === 'string') {
      try {
        // Find the JSON part after the colon
        const jsonMatch = rawError.match(/\{.*\}/)
        if (jsonMatch) {
          const parsed = JSON.parse(jsonMatch[0])
          errorMessage.value = parsed.message || errorMessage.value
        } else {
          errorMessage.value = rawError // fallback to whole string
        }
      } catch (e) {
        errorMessage.value = rawError // fallback if parsing fails
      }
    }

    ElNotification({
      title: 'Upload Failed',
      message: errorMessage.value,
      type: 'error',
      showClose: true,
    })
    showErrorModal.value = true
  } finally {
    loading.value = false
  }
}

watch(showConfettiModal, async (newVal) => {
  if (newVal) {
    await nextTick()

    const myConfetti = confetti.create(confettiCanvas.value, {
      resize: true
    })

    const colors = ['#1f5aa3', '#00b894', '#ffeaa7', '#fab1a0']
    const duration = 3 * 1000 // 5 seconds
    const end = Date.now() + duration

    function frame() {
      if (Date.now() > end) return // stop spawning after 5s

      myConfetti({
        particleCount: 8,
        spread: 70,
        origin: { x: Math.random(), y: 0 },
        colors,
        gravity: 1, // normal fall
        ticks: 200 // particles disappear fairly quickly
      })

      requestAnimationFrame(frame)
    }

    // start rain
    frame()

    // clear canvas after 5s (remove all particles)
    setTimeout(() => {
      confettiCanvas.value
        .getContext('2d')
        .clearRect(0, 0, confettiCanvas.value.width, confettiCanvas.value.height)
    }, duration + 1000) // small buffer to finish last particles
  }
})

onMounted(() => {})
</script>

<style scoped>
.hidden {
  display: none;
}
.custom-btn {
  text-transform: none;
}
</style>
