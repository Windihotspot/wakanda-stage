<script setup>
import { ref, computed, onMounted } from 'vue'
import MainLayout from '@/layouts/full/MainLayout.vue'
import moment from 'moment'
import Axios from 'axios'

const statuses = ['All', 'Success', 'Failed']
const selectedStatus = ref('All')
const searchQuery = ref('')

const showModal = ref(false)

// Modal functions
const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const creditStatements = ref([
  //   {
  //     id: '224355667829',
  //     created_date: '2024-11-16',
  //     report_type: 'Individual',
  //     bureau_name: 'CRC',
  //     status: 'Success'
  //   },
  //   {
  //     id: '223564748855',
  //     created_date: '2024-11-16',
  //     report_type: 'Individual',
  //     bureau_name: 'CRC, FCB',
  //     status: 'Success'
  //   },
  //   {
  //     id: '223345667788',
  //     created_date: '2024-11-16',
  //     report_type: 'Individual',
  //     bureau_name: 'CRC, Credit Registry',
  //     status: 'Success'
  //   },
  //   {
  //     id: '345678877776',
  //     created_date: '2024-11-16',
  //     report_type: 'Individual',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Success'
  //   },
  //   {
  //     id: 'ESPORTA HOTEL AND SUITES',
  //     created_date: '2024-11-16',
  //     report_type: 'Commercial',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Failed'
  //   },
  //   {
  //     id: 'JUPITA BANK LIMITED',
  //     created_date: '2024-11-16',
  //     report_type: 'Commercial',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Success'
  //   },
  //   {
  //     id: 'SEAMLESSHR LIMITED',
  //     created_date: '2024-11-16',
  //     report_type: 'Commercial',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Success'
  //   },
  //   {
  //     id: 'LINKS MICROFINANCE BANK',
  //     created_date: '2024-11-16',
  //     report_type: 'Commercial',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Success'
  //   },
  //   {
  //     id: 'SHARA TECHNOLOGY LTD',
  //     created_date: '2024-11-16',
  //     report_type: 'Commercial',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Failed'
  //   },
  //   {
  //     id: '22334455667',
  //     created_date: '2024-11-16',
  //     report_type: 'Individual',
  //     bureau_name: 'CRC, Credit Registry, FCB',
  //     status: 'Success'
  //   }
])

const isLoading = ref(false)

// Computed filtered list based on selectedStatus and searchQuery
const filteredCreditStatements = computed(() => {
  return creditStatements.value.filter((credit) => {
    const matchesStatus = selectedStatus.value === 'All' || credit.status === selectedStatus.value
    const matchesSearch =
      searchQuery.value === '' ||
      credit.id.toString().toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      credit.report_type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      credit.bureau_name.toLowerCase().includes(searchQuery.value.toLowerCase())

    return matchesStatus && matchesSearch
  })
})

const activeTab = ref('individual')

// Individual
const enquiryReasonIndividual = ref('')
const bvnIndividual = ref('')
const consentIndividual = ref(false)
const reportOptionsIndividual = ref([
  { label: 'First Central', value: 'first_central', checked: true },
  { label: 'Credit Registry', value: 'credit_registry', checked: true },
  { label: 'CRC', value: 'crc', checked: true }
])

// Company
const enquiryReasonCompany = ref('')
const rcNumber = ref('')
const businessName = ref('')
const consentCompany = ref(false)
const reportOptionsCompany = ref([
  { label: 'First Central', value: 'first_central', checked: true },
  { label: 'Credit Registry', value: 'credit_registry', checked: true },
  { label: 'CRC', value: 'crc', checked: true }
])

const submitCompanyForm = () => {
  closeModal()
}

const fetchCreditChecks = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  console.log(savedAuth)

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const tenantId = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  const API_URL = `https://staging.getjupita.com/api/${tenantId}/fetch-existing-credit-checks`
  isLoading.value = true

  try {
    const response = await Axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    console.log('fetch credit checks response:', response)
    creditStatements.value = response.data.data.credit_checks.data
    console.log('fetch credit checks data:', creditStatements.value)
  } catch (error) {
    console.error('Error fetching credit checks:', error)
    statements.value = []
  } finally {
    isLoading.value = false
  }
}

const submitIndividualForm = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  console.log(savedAuth)

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const borrower_id = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  // Create query parameters
  const params = new URLSearchParams({
    id_type: 'individual',
    id_string: bvnIndividual.value,
    purpose: enquiryReasonIndividual.value,
    refresh: false,
    'services[]': 'fcbc'
  })

  console.log('run credit checks request:', params)

  const API_URL = `https://staging.getjupita.com/api/${borrower_id}/check-credit-history?${params.toString()}`

  try {
    isLoading.value = true
    const response = await Axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    console.log('Credit check response:', response.data)
    closeModal()
    fetchCreditChecks() // Refresh credit checks
  } catch (error) {
    console.error('Error running individual credit check:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchCreditChecks()
})
</script>

<template>
  <MainLayout>
    <div class="p-4 rounded shadow-sm bg-white m-4">
      <!-- Header with Title and New credit search Button -->
      <div class="bg-white flex justify-between items-center border-b p-2">
        <div class="mb-2">
          <h1 class="text-xl font-bold">Credit Search</h1>
          <p class="text-gray-500 text-sm mt-1">View and Manage your credit search</p>
        </div>

        <v-btn
          @click="openModal"
          size="medium"
          class="normal-case custom-btn hover:bg-blue-700 text-white text-sm font-semibold px-6 py-3 rounded-md shadow-md"
        >
          <span
            class="bg-white text-blue-600 rounded-full p-1 flex items-center justify-center w-4 h-4 mr-2"
          >
            <i class="fa-solid fa-plus text-sm text-[#1f5aa3]"></i>
          </span>
          New Credit Search
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
          placeholder="Search for a credit statement"
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
        <span class="mt-2 text-gray-600 text-sm">Loading credit statements...</span>
      </div>

      <div v-else-if="creditStatements.length > 0" class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="font-semibold uppercase text-sm leading-normal">
            <tr>
              <th class="py-3 px-6 text-left">S/N</th>
              <th class="py-3 px-6 text-left">ID</th>
              <th class="py-3 px-6 text-left">Created Date</th>
              <th class="py-3 px-6 text-left">Report Type</th>
              <th class="py-3 px-6 text-left">Bureau Name</th>
              <th class="py-3 px-6 text-left">Status</th>
              <th class="py-3 px-6 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 text-sm font-light bg-white rounded-xl shadow-lg">
            <tr
              v-for="(credit, index) in filteredCreditStatements"
              :key="credit.id"
              class="border-b border-gray-200 font-normal"
            >
              <td class="py-3 px-6 text-left">{{ index + 1 }}</td>

              <td class="py-3 px-6">{{ credit.id }}</td>
              <td class="py-3 px-6">{{ moment(credit.created_date).format('MMMM Do, YYYY') }}</td>
              <td class="py-3 px-6">{{ credit.report_type }}</td>
              <td class="py-3 px-6">{{ credit.bureau_name }}</td>
              <td class="py-3 px-6">
                <span
                  :class="
                    credit.status === 'Success'
                      ? 'text-green-600 py-1 px-2 text-xs font-semibold rounded-full bg-green-100'
                      : 'text-red-600 py-1 px-2 text-xs font-semibold rounded-full bg-red-100'
                  "
                >
                  {{ credit.status }}
                </span>
              </td>
              <td class="py-3 px-6 text-center">
                <span
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
          <div class="empty-text font-normal mt-8">No credit checks</div>
        </div>
      </div>
    </div>

    <!-- Credit Search Modal -->
    <v-dialog v-model="showModal" persistent max-width="800px">
      <template #default>
        <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">
          <!-- Close icon positioned absolutely -->
          <button
            @click="showModal = false"
            class="absolute top-4 right-4 text-gray-600 hover:text-red-500 mr-6"
          >
            <i class="fas fa-times cursor-pointer fa-lg"></i>
          </button>
          <div class="p-6 space-y-6">
            <!-- Tabs -->
            <div class="flex space-x-4">
              <button
                @click="activeTab = 'individual'"
                :class="
                  activeTab === 'individual'
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-200 text-gray-700'
                "
                class="px-4 py-2 rounded transition"
              >
                Individual
              </button>
              <button
                @click="activeTab = 'company'"
                :class="
                  activeTab === 'company' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                "
                class="px-4 py-2 rounded transition"
              >
                Company
              </button>
            </div>

            <!-- Tab content transition -->
            <transition name="fade" mode="out-in">
              <div :key="activeTab">
                <!-- Individual Form -->
                <div v-if="activeTab === 'individual'" class="space-y-4">
                  <h2 class="text-lg font-semibold">Individual Credit Search</h2>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <v-select
                      variant="outlined"
                      color="#1f5aa3"
                      v-model="enquiryReasonIndividual"
                      :items="['Employment', 'Loan Application', 'Background Check']"
                      label="Enquiry Reason"
                      placeholder="Choose reason for enquiry"
                      required
                      outlined
                      dense
                      return-object
                      :menu-props="{ maxHeight: '200' }"
                    ></v-select>

                    <v-text-field
                      variant="outlined"
                      color="#1f5aa3"
                      v-model="bvnIndividual"
                      label="Bank Verification Number"
                      placeholder="Enter BVN"
                      required
                      outlined
                      dense
                    ></v-text-field>
                  </div>

                  <div class="flex flex-wrap gap-6">
                    <el-checkbox
                      v-for="(option, index) in reportOptionsIndividual"
                      :key="index"
                      v-model="option.checked"
                      :label="option.label"
                      class="custom-checkbox"
                    />
                  </div>

                  <!-- Consent Checkbox -->
                  <div class="bg-red-100 text-red-800 p-4 rounded flex items-start space-x-2">
                    <el-checkbox v-model="consentIndividual" class="custom-checkbox" />
                    <p class="text-md mt-1">
                      By clicking on “Search Report”, you acknowledge that you have gotten consent
                      from the data subject to use their data for verification purpose.
                    </p>
                  </div>

                  <button
                    @click="submitIndividualForm"
                    class="w-full bg-blue-600 text-white py-3 rounded disabled:opacity-50"
                    :disabled="!consentIndividual"
                  >
                    Generate Report
                  </button>
                </div>

                <!-- Company Form -->
                <div v-else-if="activeTab === 'company'" class="space-y-4">
                  <h2 class="text-lg font-semibold">Company Credit Search</h2>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Enquiry Reason -->
    <v-select
      v-model="enquiryReasonCompany"
      :items="[
        'Supplier Verification',
        'Partnership Review',
        'Credit Assessment'
      ]"
      label="Enquiry Reason"
      placeholder="Select enquiry reason"
      variant="outlined"
      color="#1f5aa3"
      density="comfortable"
      required
    ></v-select>

    <!-- Business Name -->
    <v-text-field
      v-model="businessName"
      label="Business Name"
      placeholder="Enter Business name"
      variant="outlined"
      color="#1f5aa3"
      density="comfortable"
      required
    ></v-text-field>

    <!-- Business Registration Number -->
    <v-text-field
      v-model="rcNumber"
      label="Business Registration Number"
      placeholder="Enter business number"
      variant="outlined"
      color="#1f5aa3"
      density="comfortable"
      required
    ></v-text-field>
  </div>
                  <div class="flex flex-wrap gap-6">
                    <el-checkbox
                      v-for="(option, index) in reportOptionsCompany"
                      :key="index"
                      v-model="option.checked"
                      :label="option.label"
                      class="custom-checkbox"
                    />
                  </div>

                  <!-- Consent Checkbox -->
                  <div class="bg-red-100 text-red-800 p-4 rounded flex items-start space-x-2">
                    <el-checkbox v-model="consentCompany" class="custom-checkbox" />
                    <p class="text-md mt-1">
                      By clicking on “Search Report”, you acknowledge that you have gotten consent
                      from the data subject to use their data for verification purpose.
                    </p>
                  </div>

                  <button
                    @click="submitCompanyForm"
                    class="w-full bg-blue-600 text-white py-3 rounded disabled:opacity-50"
                    :disabled="!consentCompany"
                  >
                    Generate Report
                  </button>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </template>
    </v-dialog>
  </MainLayout>
</template>

<style scoped>
.custom-btn {
  text-transform: none;
  background-color: #1f5aa3;
}
/* Customize tick/checkmark color for Element Plus checkboxes */
.custom-checkbox >>> .el-checkbox__input.is-checked .el-checkbox__inner {
  background-color: #22c55e !important; /* Tailwind's green-500 */
  border-color: #22c55e !important;
}


</style>
