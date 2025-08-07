<template>
  <div ref="pdfContent" class="p-6 space-y-6 bg-white text-gray-800">
    <!-- First Central Section -->
    <h1 class="text-2xl font-bold">First Central</h1>
    <section>
      <div v-if="idType !== 'business'">
        <!-- // personal -->
        <div class="bg-white p-6 rounded space-y-4">
          <h2 class="text-md font-semibold mb-4">Personal details summary</h2>

          <!-- Check if `personal` has any data -->
          <div
            v-if="personal && Object.keys(personal).length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600"
          >
            <!-- All your data fields remain unchanged here -->
            <div>
              <p class="mb-1">Last Name</p>
              <p class="font-bold text-gray-900">{{ personal.Surname }}</p>
            </div>
            <div>
              <p class="mb-1">Gender</p>
              <p class="font-bold text-gray-900">{{ personal.Gender }}</p>
            </div>
            <div>
              <p class="mb-1">Phone Number</p>
              <p class="font-bold text-gray-900">{{ personal.CellularNo }}</p>
            </div>
            <div>
              <p class="mb-1">Latest Residential Address</p>
              <p class="font-bold text-gray-900 leading-snug">
                {{ personal.ResidentialAddress1 }}
              </p>
            </div>
            <div>
              <p class="mb-1">First Name</p>
              <p class="font-bold text-gray-900">{{ personal.FirstName }}</p>
            </div>
            <div>
              <p class="mb-1">Bank Verification Number</p>
              <p class="font-bold text-gray-900">{{ personal.BankVerificationNo }}</p>
            </div>
            <div>
              <p class="mb-1">Work Telephone</p>
              <p class="font-bold text-gray-900">{{ personal.WorkTelephoneNo }}</p>
            </div>
            <div>
              <p class="mb-1">Other Names</p>
              <p class="font-bold text-gray-900">{{ personal.OtherNames }}</p>
            </div>
            <div>
              <p class="mb-1">Date of Birth</p>
              <p class="font-bold text-gray-900">{{ personal.BirthDate }}</p>
            </div>
            <div>
              <p class="mb-1">Home Telephone</p>
              <p class="font-bold text-gray-900">{{ personal.HomeTelephoneNo }}</p>
            </div>
          </div>

          <!-- Fallback message if no data -->
          <div v-else class="text-gray-500 text-sm italic">No personal data available.</div>
        </div>
      </div>

      <div v-else>
        <!-- BUSINESS INFORMATION SECTION -->
        <div v-if="idType === 'business'" class="bg-white p-6 rounded space-y-4">
          <h2 class="text-md font-semibold">Business Information</h2>
          <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600"
          >
            <div>
              <p class="mb-1">Business Name</p>
              <p class="font-bold text-gray-900">{{ businessData.BusinessName }}</p>
            </div>
            <div>
              <p class="mb-1">Date of Incorporation</p>
              <p class="font-bold text-gray-900">{{ businessData.DateOfIncorporation }}</p>
            </div>
            <div>
              <p class="mb-1">Business Address</p>
              <p class="font-bold text-gray-900">
                {{ businessData.CommercialAddress1 }}, {{ businessData.CommercialAddress2 }},
                {{ businessData.CommercialAddress4 }}
              </p>
            </div>
          </div>
        </div>

        <!-- DIRECTOR INFORMATION TABLE -->
        <div v-if="idType === 'business'" class="bg-white p-6 rounded space-y-4 mt-4">
          <h2 class="text-md font-semibold">Director Information</h2>

          <div v-if="directors.length > 0">
            <table class="min-w-full text-sm text-left">
              <thead class="text-xs font-semibold text-gray-700">
                <tr>
                  <th class="p-2">First Name</th>
                  <th class="p-2">Other Names</th>
                  <th class="p-2">Surname</th>
                  <th class="p-2">Identification Number</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(director, index) in directors" :key="index" class="">
                  <td class="p-2 font-bold text-gray-900">{{ director.firstName }}</td>
                  <td class="p-2 font-bold text-gray-900">{{ director.othernames }}</td>
                  <td class="p-2 font-bold text-gray-900">{{ director.surname }}</td>
                  <td class="p-2 font-bold text-gray-900">
                    {{ director.Identificationnumber }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-sm text-gray-500 italic">No director information available.</div>
        </div>
      </div>

      <!-- Summary -->
      <div class="bg-white p-6 rounded-md mt-4">
        <h2 class="text-md font-semibold mb-6">Summary</h2>

        <!-- Show data if summary object exists and is not empty -->
        <div
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600"
        >
          <!-- Row 1 -->
          <div>
            <p class="mb-1">Total active monthly installment</p>
            <p class="font-bold text-gray-900">{{ summary.TotalMonthlyInstalment }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of credit facilities</p>
            <p class="font-bold text-gray-900">{{}}</p>
          </div>
          <div>
            <p class="mb-1">Total no of open facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalOpenFacilities }}</p>
          </div>

          <!-- Row 2 -->
          <div>
            <p class="mb-1">Total arrear amount</p>
            <p class="font-bold text-gray-900">{{ summary.Amountarrear }}</p>
          </div>
          <div>
            <p class="mb-1">Total outstanding debts</p>
            <p class="font-bold text-gray-900">{{ summary.TotalOutstandingdebt }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of closed credit facilities</p>
            <p class="font-bold text-gray-900"></p>
          </div>

          <!-- Row 3 -->
          <div>
            <p class="mb-1">Total no of account in arrears</p>
            <p class="font-bold text-gray-900">{{ summary.TotalAccountarrear }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of delinquent facilities</p>
            <p class="font-bold text-gray-900">{{}}</p>
          </div>
          <div>
            <p class="mb-1">Total no written off facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalWrittenOffFacilities }}</p>
          </div>
        </div>

        <!-- Fallback if no data -->
      </div>

      <!-- Loan Accounts Table -->
    </section>

    <!-- Credit Registry Section -->
  </div>
</template>

<script setup>
import { ref, defineExpose } from 'vue'
import { ElNotification, ElMessage } from 'element-plus'
import html2pdf from 'html2pdf.js'

const props = defineProps({
  idType: String,
  businessData: Object,
  directors: Array,
  personal: Object,
  summary: Object,
  creditAgreementSummary: Array,
  enquiryHistory: Array,
  employmentHistory: Array,
  addressHistory: Array,
  loanAccounts: Array,
  delinquentAccounts: Array,
  closedAccounts: Array,
  derogatoryAccounts: Array,
  writtenOffAccounts: Array,
  unknownAccounts: Array,
  inquiryHistory: Array,
  creditPersonal: Object,
  creditRegistryBusinessName: String,
  creditDirectors: Array,
  creditRegistryTotalOpen: Number,
  creditRegistryTotalClosed: Number,
  creditRegistryTotalWrittenOff: Number
})
const expanded = ref([])

const toggleRow = (uid) => {
  expanded.value = expanded.value.includes(uid) ? [] : [uid]
}

const isExpanded = (uid) => expanded.value.includes(uid)

const loanHeaders = [
  { title: "Lender's Name", key: 'lender' },
  { title: 'Disbursement Date', key: 'date' },
  { title: 'Loan Amount', value: 'amount' },
  { title: 'Loan Balance', key: 'balance' },
  { title: 'Status', key: 'status' },
  { title: 'Action', key: 'action', sortable: false }
]

const employmentHeaders = [
  { title: 'Employer Name', key: 'employerName' },
  { title: 'Date Updated', key: 'date' }
]

function getStatusColor(status) {
  if (!status || status.trim() === '') return 'grey'

  const normalized = status.toLowerCase().trim()

  if (normalized === 'closed') return 'green'
  if (normalized === 'open') return 'red'
  if (normalized === 'paid up') return 'blue'

  return 'grey' // fallback for unknown values
}

const pdfContent = ref(null)

const exportPDF = () => {
  const now = new Date()
  const dateString = now.toISOString().slice(0, 16).replace('T', '_').replace(':', '-')

  let filename = ''

  if (props.idType === 'business' && props.businessData) {
    const businessName = props.businessData.BusinessName || 'Business'
    filename = `${businessName}_${dateString}.pdf`
  } else {
    // Determine which data source is available
    const personalData =
      props.personal && Object.keys(props.personal).length > 0
        ? props.personal
        : props.creditPersonal

    const firstName = personalData?.FirstName || 'FirstName'
    const lastName = personalData?.Surname || 'LastName'
    filename = `${firstName}_${lastName}_${dateString}.pdf`
  }

  // ✅ Show loading message
  const loadingMessage = ElMessage({
    message: 'Downloading report...',
    type: 'warning',
    duration: 3000, // stays until closed
    showClose: true
  })

  setTimeout(() => {
    html2pdf()
      .set({
        margin: 0.5,
        filename,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
      })
      .from(pdfContent.value)
      .save()
      .then(() => {
        // ✅ Close loading message
        loadingMessage.close()

        // ✅ Show success notification
        ElNotification({
          title: 'Download Complete',
          message: 'Report has been downloaded successfully!',
          type: 'success',
          duration: 3000
        })
      })
  }, 500)
}

// 👇 Expose to parent
defineExpose({ exportPDF })
</script>
