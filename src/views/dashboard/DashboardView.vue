<script setup>
import { ref, onMounted, computed } from 'vue'
import Axios from 'axios'
import moment from 'moment'
import MainLayout from '@/layouts/full/MainLayout.vue'
import Upload from '@/components/Upload.vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
const router = useRouter()
const authStore = useAuthStore()

const statuses = ['All', 'Processed', 'Altered', 'Failed']
const selectedStatus = ref('All')
const searchQuery = ref('')

const statements = ref([])
const isLoading = ref(true)
const showModal = ref(false)

// Modal functions
const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

// Fetch statements
const fetchStatements = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

console.log(savedAuth);

const token = savedAuth
  ? savedAuth?.token
  : computed(() => authStore.token)?.value;

const tenantId = savedAuth
  ? savedAuth.user?.business_name
    ? savedAuth.user?.id
    : savedAuth.user?.tenant_id
  : computed(() =>
      authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id
    )?.value;

  const API_URL = `${import.meta.env.VITE_API_URL}
/api/${tenantId}/fetch-tenant-analyses`
  isLoading.value = true

  try {
    const response = await Axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    console.log('fetch statements data:', response)
    const rawAnalyses = response.data?.data?.analyses || []

    statements.value = rawAnalyses.map((item) => ({
      id: item.id,
      name: `${item.firstname} ${item.lastname}`,
      file_name: item.file_name,
      created_date: item.created_at, // raw date
      status: item.status
    }))
  } catch (error) {
    console.error('Error fetching statements:', error)
    statements.value = []
  } finally {
    isLoading.value = false
  }
}

const sortedStatements = computed(() => {
  return [...statements.value].sort((a, b) => new Date(b.created_date) - new Date(a.created_date))
})

const filteredStatements = computed(() => {
  return sortedStatements.value.filter((doc) => {
    const matchesStatus =
      selectedStatus.value === 'All' ||
      doc.status.toLowerCase() === selectedStatus.value.toLowerCase()

    const query = searchQuery.value.toLowerCase()
    const matchesSearch =
      doc.file_name.toLowerCase().includes(query) ||
      moment(doc.created_date).format('MMMM Do, YYYY').toLowerCase().includes(query) ||
      doc.name.toLowerCase().includes(query) ||
      doc.status.toLowerCase().includes(query)

    return matchesStatus && matchesSearch
  })
})

const fetchAnalysisResult = async (analysisId) => {
  const apiUrl = `${import.meta.env.VITE_API_URL}
/api/${tenantId.value}/get-analysis-result?analysis_id=${analysisId};`

  const raw = {
    analysis_id: analysisId
  }
  // Log the request details before sending
  console.log('Sending request with analysis_id:', analysisId)
  console.log('Request body:', raw)
  try {
    const response = await Axios({
      method: 'get',
      url: apiUrl,
      headers: {
        Authorization: `Bearer ${token.value}`,
        'Content-Type': 'application/json'
      },
      data: raw
    })

    console.log('Analysis result:', response.data)
    // Handle result here (e.g., show a modal, redirect, etc.)
  } catch (error) {
    console.error('Error fetching analysis result:', error)
  }
}
// Navigate to analysis page
const goToAnalysis = (analysisId) => {
  router.push({ name: 'StatementAnalysis', params: { id: analysisId } })
}

// Fetch on mounted
onMounted(() => {
  fetchStatements()
})

</script>

<template>
  <MainLayout>
    <div class="p-4 rounded shadow-sm bg-white m-4">
      <!-- Header with Title and Add Statement Button -->
      <div class="bg-white flex justify-between items-center border-b p-2">
        <div class="mb-2">
          <h1 class="text-2xl font-bold text-gray-900">Statement Analysis</h1>
          <p class="text-gray-500 text-sm mt-1">View and Manage your bank statement analysis</p>
        </div>

        <v-btn
          @click="openModal"
          size="large"
          class="normal-case custom-btn hover:bg-blue-700 text-white text-sm font-semibold px-6 py-3 rounded-md shadow-md"
        >
          <span
            class="bg-white text-blue-600 rounded-full p-1 flex items-center justify-center w-4 h-4 mr-2"
          >
            <i class="fa-solid fa-plus text-sm text-[#1f5aa3]"></i>
          </span>
          Add New
        </v-btn>
      </div>

      <div class="flex items-center justify-between p-2">
        <!-- Filter (Vuetify Select) -->
        <div class="flex items-center space-x-2 pt-2">
          <!-- Filter Icon -->
          <i class="fa-solid fa-filter"></i>

          <v-select
            color="#1f5aa3"
            v-model="selectedStatus"
            :items="statuses"
            label="Status"
            density="compact"
            hide-details
            variant="outlined"
            class="w-32"
          ></v-select>
        </div>

        <v-text-field
          rounded
          v-model="searchQuery"
          placeholder="Search for a statement"
          density="compact"
          hide-details
          variant="outlined"
          class="max-w-xs rounded-md"
          label="Search"
          color="#1f5aa3"
          append-inner-icon=""
        >
          <!-- FontAwesome Search Icon inside append-inner slot -->
          <template #append-inner>
            <i class="fas fa-search text-[#1f5aa3]"></i>
          </template>
        </v-text-field>
      </div>
    </div>

    <div class="p-4">
      <div v-if="isLoading" class="flex flex-col items-center justify-center min-h-[200px]">
        <v-progress-circular indeterminate color="#1f5aa3" size="80" width="8" />
        <span class="mt-2 text-gray-600 text-sm">Loading statements...</span>
      </div>

      <div v-else-if="statements.length > 0" class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="font-semibold uppercase text-sm leading-normal">
            <tr>
              <th class="py-3 px-6 text-left">S/N</th>

              <th class="py-3 px-6 text-left">File Name</th>
              <th class="py-3 px-6 text-left">Created Date</th>
              <th class="py-3 px-6 text-left">Uploaded by</th>
              <th class="py-3 px-6 text-left">Status</th>
              <th class="py-3 px-6 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 text-sm font-light bg-white rounded-xl shadow-lg">
            <tr
              v-for="(doc, index) in filteredStatements"
              :key="doc.id"
              class="border-b border-gray-200 font-normal"
            >
              <td class="py-3 px-6 text-left">{{ index + 1 }}</td>

              <td class="py-3 px-6">{{ doc.file_name }}</td>
              <td class="py-3 px-6">{{ moment(doc.created_date).format('MMMM Do, YYYY') }}</td>
              <td class="py-3 px-6">{{ doc.name }}</td>
              <td class="py-3 px-6">
                <span
                  :class="
                    doc.status === 'PROCESSED'
                      ? 'text-green-600 py-1 px-2 text-xs font-semibold rounded-full bg-green-100'
                      : 'text-red-600 py-1 px-2 text-xs font-semibold rounded-full bg-red-100'
                  "
                >
                  {{ doc.status }}
                </span>
              </td>
              <td class="py-3 px-6 text-center">
                <span
                  @click="goToAnalysis(doc.id)"
                  class="bg-[#1f5aa3] text-white px-4 py-1 rounded hover:bg-blue-600 cursor-pointer"
                >
                  View
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="fill-height align-center justify-center">
        <div class="mx-auto text-center align-center w-[200px] h-[200px]">
          <img src="/src/assets/images/no-data.png" alt="No Data" />
          <div class="empty-text font-semibold mt-8">No Statements</div>
        </div>
      </div>
    </div>

    <v-dialog v-model="showModal" persistent max-width="600px" class="pa-4">
      <template v-slot:default="{ close }">
        <div style="max-height: 80vh; overflow-y: auto">
          <Upload @close="closeModal" :on-success="fetchStatements" />
        </div>
      </template>
    </v-dialog>
  </MainLayout>
</template>

<style scoped>
.h-8 {
  height: 2rem;
}
.custom-btn {
  background-color: #1f5aa3;
  text-transform: none;
  text-transform: none;
}
v-btn {
  text-transform: none;
}
</style>
