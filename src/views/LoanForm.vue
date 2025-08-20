<template>
  <MobileLayout>
    <div class="flex flex-col h-full">
      <!-- Header with back + progress -->
      <div class="flex items-center justify-between mb-6">
        <v-btn variant="text" size="small" @click="prevStep" :disabled="step === 1">
          <i class="fas fa-arrow-left text-xl"></i>
        </v-btn>

        <div class="flex-1 mx-4">
          <div class="h-1 bg-gray-200 rounded">
            <div
              class="h-1 bg-blue-600 rounded transition-all"
              :style="{ width: `${(step / totalSteps) * 100}%` }"
            ></div>
          </div>
        </div>

        <span class="text-sm font-medium">{{ step }}/{{ totalSteps }}</span>
      </div>

      <!-- Step Content -->
      <div class="flex-1 overflow-y-auto pb-6">
        <!-- Step 1 Loan information -->
        <div v-if="step === 1">
          <h2 class="font-semibold text-lg mb-4">Loan Information</h2>

          <v-text-field
            v-model="form.loanAmount"
            type="number"
            label="How much loan do you need?"
            placeholder="enter the amount you need"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <v-select
            v-model="form.tenor"
            :items="tenors"
            label="How long do you want to repay for?"
            placeholder="select tenor"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <v-text-field
            v-model="form.purpose"
            label="What is the purpose of this loan?"
            placeholder="enter the reason for this loan"
            variant="outlined"
            density="comfortable"
          />
        </div>

        <!-- Step 2 Personal information -->
        <div v-if="step === 2">
          <h2 class="font-semibold text-lg mb-4">Personal Information</h2>

          <v-text-field
            v-model="form.fullName"
            label="Full Name"
            placeholder="enter full name"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <v-text-field
            v-model="form.dob"
            type="date"
            label="Date of birth"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          >
            <template #append-inner>
              <v-icon>mdi-calendar</v-icon>
            </template>
          </v-text-field>

          <v-text-field
            v-model="form.mobile"
            type="tel"
            label="Mobile Number"
            placeholder="enter mobile number"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <v-text-field
            v-model="form.email"
            type="email"
            label="Email Address"
            placeholder="enter email address"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <v-text-field
            v-model="form.address"
            label="Home Address"
            placeholder="enter address"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <div class="grid grid-cols-2 gap-4">
            <v-select
              v-model="form.state"
              :items="states"
              label="State of Residence"
              placeholder="Select state"
              variant="outlined"
              density="comfortable"
            />
            <v-text-field
              v-model="form.lga"
              label="Local Govt. Area"
              placeholder="Select local govt. area"
              variant="outlined"
              density="comfortable"
            />
          </div>
        </div>
        <!-- step 3 ID Validation -->
        <div v-if="step === 3">
          <h2 class="font-semibold text-lg mb-4">ID Validation</h2>

          <v-text-field
            v-model="form.fullName"
            label="Bank Verification Number"
            placeholder="Enter bvn"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          />

          <v-text-field
            v-model="form.dob"
            label="National Identification Number"
            placeholder="Enter NIN"
            variant="outlined"
            density="comfortable"
            class="mb-4"
          >
          </v-text-field>
        </div>

        <!-- step 4 Take a selfie -->
        <div v-if="step === 4">
          <!-- Camera Section -->
          <div class="flex flex-col flex-1 justify-center items-center w-full">
            <p class="font-semibold mb-4">Take a Selfie</p>

            <!-- Live Camera -->
            <div
              v-if="!captured"
              class="w-60 h-60 rounded-full overflow-hidden border-2 border-gray-300 mb-4"
            >
              <video ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
            </div>

            <!-- Captured Selfie Preview -->
            <div
              v-else
              class="w-60 h-60 rounded-full overflow-hidden border-2 border-gray-300 mb-4"
            >
              <img :src="preview" alt="Selfie Preview" class="w-full h-full object-cover" />
            </div>

            <!-- Capture / Retake -->
            <v-btn v-if="!captured" color="primary" class="rounded px-6 py-2" @click="capturePhoto">
              <i class="fas fa-camera mr-2"></i> Capture
            </v-btn>
            <v-btn v-else color="success" class="rounded-lg px-6 py-2" @click="retakePhoto">
              <i class="fas fa-redo mr-2"></i> Retake
            </v-btn>
          </div>
        </div>

        <!-- step 5 Employment history -->
        <div v-if="step === 5" class="px-4 py-6 space-y-4">
          <h2 class="font-semibold text-lg">Employment Information</h2>

          <!-- Employer Name -->
          <v-text-field
            v-model="form.employerName"
            label="Employer Name"
            placeholder="enter employer name"
            variant="outlined"
            density="comfortable"
          />

          <!-- Employment Type -->
          <v-select
            v-model="form.employmentType"
            :items="employmentTypes"
            label="Employment Type"
            placeholder="select employment type"
            variant="outlined"
            density="comfortable"
          />

          <!-- Company Sector -->
          <v-select
            v-model="form.companySector"
            :items="companySectors"
            label="Company Sector"
            placeholder="select company sector"
            variant="outlined"
            density="comfortable"
          />

          <!-- Employment Start Date -->
          <v-text-field
            v-model="form.startDate"
            label="Employment Start Date"
            type="date"
            variant="outlined"
            density="comfortable"
          />

          <!-- Designation -->
          <v-text-field
            v-model="form.designation"
            label="Designation"
            placeholder="enter designation"
            variant="outlined"
            density="comfortable"
          />

          <!-- Net Income -->
          <v-text-field
            v-model="form.netIncome"
            label="Net Income"
            placeholder="enter net income"
            type="number"
            prefix="₦"
            variant="outlined"
            density="comfortable"
          />

          <!-- Work Email -->
          <v-text-field
            v-model="form.workEmail"
            label="Work email"
            placeholder="enter work email"
            type="email"
            variant="outlined"
            density="comfortable"
          />

          <!-- Employer Address -->
          <v-text-field
            v-model="form.employerAddress"
            label="Employer Address"
            placeholder="enter employer address"
            variant="outlined"
            density="comfortable"
          />

          <!-- State & LGA -->
          <div class="grid grid-cols-2 gap-4">
            <v-select
              v-model="form.state"
              :items="states"
              label="State"
              placeholder="select state"
              variant="outlined"
              density="comfortable"
            />
            <v-select
              v-model="form.lga"
              :items="lgas[form.state] || []"
              label="Local Govt. Area"
              placeholder="select LGA"
              variant="outlined"
              density="comfortable"
            />
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-auto">
        <v-btn block color="primary" size="large" class="rounded" @click="nextStep">
          Continue
        </v-btn>
        <p class="text-center text-xs text-gray-500 mt-2 font-semibold">Powered by Jupita</p>
      </div>
    </div>
  </MobileLayout>
</template>

<script setup>
import { ref, watch, onBeforeUnmount } from 'vue'
import MobileLayout from '@/layouts/full/MobileLayout.vue'

const step = ref(1)
const totalSteps = 6

const video = ref(null)
const stream = ref(null)
const preview = ref(null)
const captured = ref(false)

const tenors = ['3 months', '6 months', '12 months']
const states = ['Lagos', 'Abuja', 'Kano']

const form = ref({
  loanAmount: '',
  tenor: '',
  purpose: '',
  fullName: '',
  dob: '',
  mobile: '',
  email: '',
  address: '',
  state: '',
  lga: ''
})

const nextStep = () => {
  if (step.value < totalSteps) step.value++
}
const prevStep = () => {
  if (step.value > 1) step.value--
}

const startCamera = async () => {
  try {
    stream.value = await navigator.mediaDevices.getUserMedia({ video: true })
    if (video.value) {
      video.value.srcObject = stream.value
    }
  } catch (err) {
    console.error('Camera access denied:', err)
  }
}

const stopCamera = () => {
  if (stream.value) {
    stream.value.getTracks().forEach((track) => track.stop())
    stream.value = null
  }
}

const capturePhoto = () => {
  const canvas = document.createElement('canvas')
  canvas.width = video.value.videoWidth
  canvas.height = video.value.videoHeight
  const ctx = canvas.getContext('2d')
  ctx.drawImage(video.value, 0, 0)
  preview.value = canvas.toDataURL('image/png')
  captured.value = true
  stopCamera() // stop live feed after capture
}

const retakePhoto = async () => {
  captured.value = false
  preview.value = null
  await startCamera()
}

watch(step, async (newStep, oldStep) => {
  if (newStep === 4) {
    await startCamera()
  } else {
    stopCamera()
    captured.value = false
    preview.value = null
  }
})

onBeforeUnmount(() => {
  stopCamera()
})
</script>

<style scoepd>
.v-btn {
  text-transform: none;
}
</style>
