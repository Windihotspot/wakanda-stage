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
            <p class="font-bold text-gray-900">
              {{ summary.TotalMonthlyInstalment || 0.0 }}
            </p>
          </div>
          <div>
            <p class="mb-1">Total no of credit facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalAccounts || 0 }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of open facilities</p>
            <p class="font-bold text-gray-900">{{ summary.totalOpen || 0 }}</p>
          </div>

          <!-- Row 2 -->
          <div>
            <p class="mb-1">Total arrear amount</p>
            <p class="font-bold text-gray-900">{{ summary.Amountarrear || 0.0 }}</p>
          </div>
          <div>
            <p class="mb-1">Total outstanding debts</p>
            <p class="font-bold text-gray-900">
              {{ summary.TotalOutstandingdebt || 0.0 }}
            </p>
          </div>
          <div>
            <p class="mb-1">Total no of closed credit facilities</p>
            <p class="font-bold text-gray-900">{{ summary.totalClosed || 0 }}</p>
          </div>

          <!-- Row 3 -->
          <div>
            <p class="mb-1">Total no of account in arrears</p>
            <p class="font-bold text-gray-900">
              {{ summary.TotalAccountarrear || 0 }}
            </p>
          </div>
          <div>
            <p class="mb-1">Total no of delinquent facilities</p>
            <p class="font-bold text-gray-900">
              {{ summary.TotalaccountinBadcondition || 0 }}
            </p>
          </div>
          <div>
            <p class="mb-1">Total no written off facilities</p>
            <p class="font-bold text-gray-900">{{ summary.totalWrittenOff || 0 }}</p>
          </div>
        </div>

        <!-- Fallback if no data -->
      </div>

      <!-- Loan Accounts Table -->
      <div class="bg-white p-6 rounded">
        <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>

        <div v-if="creditAgreementSummary && creditAgreementSummary.length > 0">
          <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-left">
              <tr>
                <th class="px-4 py-2 border">Account No</th>
                <th class="px-4 py-2 border">Loan Amount</th>
                <th class="px-4 py-2 border">Balance</th>
                <th class="px-4 py-2 border">Overdue</th>
                <th class="px-4 py-2 border">Instalment</th>
                <th class="px-4 py-2 border">Duration</th>
                <th class="px-4 py-2 border">Repayment Freq.</th>
                <th class="px-4 py-2 border">Opened</th>
                <th class="px-4 py-2 border">Closed</th>
                <th class="px-4 py-2 border">Performance</th>
                <th class="px-4 py-2 border">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in creditAgreementSummary" :key="item.uid" class="hover:bg-gray-50">
                <td class="px-4 py-2 border">{{ displayValue(item.accountNo) }}</td>
                <td class="px-4 py-2 border">{{ item.amount || 0 }}</td>
                <td class="px-4 py-2 border">{{ item.balance || 0 }}</td>
                <td class="px-4 py-2 border">{{ item.overdue || 0 }}</td>
                <td class="px-4 py-2 border">{{ item.instalment || 0 }}</td>
                <td class="px-4 py-2 border">{{ displayValue(item.duration) }}</td>
                <td class="px-4 py-2 border">
                  {{ displayValue(item.repaymentFrequency) }}
                </td>
                <td class="px-4 py-2 border">{{ displayValue(item.date) }}</td>
                <td class="px-4 py-2 border">{{ displayValue(item.closedDate) }}</td>
                <td class="px-4 py-2 border">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                    :class="getChipStyle(item.performanceStatus)"
                  >
                    <span
                      class="h-1.5 w-1.5 rounded-full mr-1.5"
                      :class="getDotColor(item.performanceStatus)"
                    ></span>
                    {{ item.performanceStatus || 'N/A' }}
                  </span>
                </td>
                <td class="px-4 py-2 border">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                    :class="getChipStyle(item.status)"
                  >
                    <span
                      class="h-1.5 w-1.5 rounded-full mr-1.5"
                      :class="getDotColor(item.status)"
                    ></span>
                    {{ item.status || 'N/A' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="text-sm text-gray-500 italic">No loan accounts available.</div>
      </div>

      <!-- Enquiry & Employment History -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Enquiry History -->
        <div class="bg-white rounded p-4">
          <h2 class="font-semibold text-md mb-4">Enquiry History</h2>
          <div v-if="enquiryHistory && enquiryHistory.length > 0">
            <table class="w-full text-sm text-left">
              <thead class="bg-gray-100">
                <tr>
                  <th class="p-2">Lender's Name</th>
                  <th class="p-2">Date Requested</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(enquiry, index) in enquiryHistory" :key="index" class="border-b">
                  <td class="p-2">{{ enquiry.lender }}</td>
                  <td class="p-2">{{ enquiry.date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-sm text-gray-500 italic">No enquiry history available.</div>
        </div>

        <!-- Employment History -->
        <div class="bg-white rounded p-4">
          <h2 class="font-semibold text-md mb-4">Employment History</h2>
          <div v-if="employmentHistory && employmentHistory.length > 0">
            <v-data-table
              :headers="employmentHeaders"
              :items="employmentHistory"
              class="elevation-1"
              fixed-header
              height="200"
            >
              <template #item.date="{ item }">
                {{ item.date }}
              </template>
            </v-data-table>
          </div>
          <div v-else class="text-sm text-gray-500 italic">No employment history available.</div>
        </div>
      </div>

      <!-- Address History -->
      <div class="bg-white rounded p-4">
        <h2 class="font-semibold text-md mb-4">Address History</h2>
        <div v-if="addressHistory && addressHistory.length > 0">
          <div class="overflow-auto max-h-72 border rounded">
            <table class="min-w-full text-sm text-left">
              <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                  <th class="p-3 font-medium text-gray-700">Address</th>
                  <th class="p-3 font-medium text-gray-700 w-40">Date Updated</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="(address, index) in addressHistory" :key="index">
                  <td class="p-3 text-gray-800">{{ address.address }}</td>
                  <td class="p-3 text-gray-800 w-40">{{ address.date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else class="text-sm text-gray-500 italic">No address history available.</div>
      </div>
    </section>

    <!-- Credit Registry Section -->


    <!-- Personal Details Summary -->
    <div v-if="idType !== 'business'" class="bg-white p-6 rounded space-y-4">
      <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
      <!-- Check if `personal` has any data -->
      <div
        v-if="
          creditPersonal &&
          Object.values(creditPersonal).some((val) => val && val.toString().trim() !== '')
        "
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600"
      >
        <div>
          <p class="mb-1">Last Name</p>
          <p class="font-bold text-gray-900">{{ creditPersonal.Surname || 'N/A' }}</p>
        </div>
        <div>
          <p class="mb-1">Gender</p>
          <p class="font-bold text-gray-900">{{ creditPersonal.Gender || 'N/A' }}</p>
        </div>
        <div>
          <p class="mb-1">Phone Number</p>
          <p class="font-bold text-gray-900">{{ creditPersonal.CellularNo || 'N/A' }}</p>
        </div>
        <div>
          <p class="mb-1">Latest Residential Address</p>
          <p class="font-bold text-gray-900 leading-snug">
            {{ creditPersonal?.address || 'N/A' }}
          </p>
        </div>
        <div>
          <p class="mb-1">First Name</p>
          <p class="font-bold text-gray-900">{{ creditPersonal.FirstName || 'N/A' }}</p>
        </div>
        <div>
          <p class="mb-1">Bank Verification Number</p>
          <p class="font-bold text-gray-900">
            {{ creditPersonal.BankVerificationNo || 'N/A' }}
          </p>
        </div>
        <div>
          <p class="mb-1">Work Telephone</p>
          <p class="font-bold text-gray-900">
            {{ creditPersonal.WorkTelephoneNo || 'N/A' }}
          </p>
        </div>
        <div>
          <p class="mb-1">Other Names</p>
          <p class="font-bold text-gray-900">{{ creditPersonal.OtherNames || 'N/A' }}</p>
        </div>
        <div>
          <p class="mb-1">Date of Birth</p>
          <p class="font-bold text-gray-900">{{ creditPersonal.BirthDate || 'N/A' }}</p>
        </div>
        <div>
          <p class="mb-1">Home Telephone</p>
          <p class="font-bold text-gray-900">
            {{ creditPersonal.HomeTelephoneNo || 'N/A' }}
          </p>
        </div>
      </div>

      <div v-else class="text-gray-500 text-sm italic">No personal data available.</div>
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
            <p class="font-bold text-gray-900">
              {{ displayValue(creditRegistrybusinessName) }}
            </p>
          </div>
          <div>
            <p class="mb-1">Date of Incorporation</p>
            <p class="font-bold text-gray-900">N/A</p>
          </div>
          <div>
            <p class="mb-1">Business Address</p>
            <p class="font-bold text-gray-900">N/A</p>
          </div>
        </div>
      </div>

      <!-- DIRECTOR INFORMATION TABLE -->
      <div v-if="idType === 'business'" class="bg-white p-6 rounded space-y-4 mt-4">
        <h2 class="text-md font-semibold">Director Information</h2>

        <div v-if="creditDirectors.length > 0">
          <table class="min-w-full text-sm text-left">
            <thead class="text-xs font-semibold text-gray-700">
              <tr>
                <th class="">First Name</th>
                <th class="">Other Names</th>
                <th class="">Surname</th>
                <th class="">Identification Number</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(director, index) in creditDirectors" :key="index">
                <td class="py-2">{{ director.firstName }}</td>
                <td class="py-2">{{ director.otherNames }}</td>
                <td class="py-2">{{ director.surname }}</td>
                <td class="py-2">{{ director.identificationNumber }}</td>
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
          <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
          <p class="mb-1">Total no of credit facilities</p>
          <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
          <p class="mb-1">Total no of open facilities</p>
          <p class="font-bold text-gray-900">{{ creditRegistryTotalOpen || 0 }}</p>
        </div>

        <!-- Row 2 -->
        <div>
          <p class="mb-1">Total arrear amount</p>
          <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
          <p class="mb-1">Total outstanding debts</p>
          <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
          <p class="mb-1">Total no of closed credit facilities</p>
          <p class="font-bold text-gray-900">{{ creditRegistryTotalClosed || 0 }}</p>
        </div>

        <!-- Row 3 -->
        <div>
          <p class="mb-1">Total no of account in arrears</p>
          <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
          <p class="mb-1">Total no of delinquent facilities</p>
          <p class="font-bold text-gray-900">N/A</p>
        </div>
        <div>
          <p class="mb-1">Total no written off facilities</p>
          <p class="font-bold text-gray-900">{{ creditRegistryTotalWrittenOff || 0 }}</p>
        </div>
      </div>

      <!-- Fallback if no data -->
    </div>

    <section></section>
  </div>
</template>

<script setup>
import { ref, defineExpose, nextTick, watchEffect } from 'vue'
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
  creditRegistrybusinessName: String,
  creditDirectors: Array,
  creditRegistryTotalOpen: Number,
  creditRegistryTotalClosed: Number,
  creditRegistryTotalWrittenOff: Number
})

function displayValue(value) {
  return value && value.trim() !== '' ? value : 'N/A'
}

function getChipStyle(status) {
  if (!status) return 'bg-gray-200 text-gray-700'

  const normalized = status.toLowerCase()
  if (normalized === 'closed' || normalized === 'performing') {
    return 'bg-green-100 text-green-800'
  }
  return 'bg-red-100 text-red-800'
}

function getDotColor(status) {
  if (!status) return 'bg-gray-500'

  const normalized = status.toLowerCase()
  if (normalized === 'closed' || normalized === 'performing') {
    return 'bg-green-600'
  }
  return 'bg-red-600'
}

const expanded = ref([])

watchEffect(() => {
  if (props.creditAgreementSummary.value?.length) {
    expanded.value = props.creditAgreementSummary.value.map((item) => item.uid)
  }
})

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

const exportPDF = async () => {
  await nextTick()

  // extra delay to let v-for sections (loan accounts) render
  await new Promise((resolve) => setTimeout(resolve, 500))

  if (!pdfContent.value) {
    return ElNotification({
      title: 'Error',
      message: 'No content available for PDF export',
      type: 'error'
    })
  }

  const now = new Date()
  const dateString = now.toISOString().slice(0, 16).replace('T', '_').replace(':', '-')

  let filename = ''
  if (props.idType === 'business' && props.businessData) {
    const businessName = props.businessData.BusinessName || 'Business'
    filename = `${businessName}_${dateString}.pdf`
  } else {
    const personalData =
      props.personal && Object.keys(props.personal).length > 0
        ? props.personal
        : props.creditPersonal
    const firstName = personalData?.FirstName || 'FirstName'
    const lastName = personalData?.Surname || 'LastName'
    filename = `${firstName}_${lastName}_${dateString}.pdf`
  }

  const loadingMessage = ElMessage({
    message: 'Generating PDF...',
    type: 'warning',
    duration: 0,
    showClose: true
  })

  const pdfOptions = {
    margin: 0.5,
    filename,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: {
      scale: 2,
      useCORS: true,
      logging: false,
      windowWidth: document.documentElement.offsetWidth // ensure full width capture
    },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  }

  try {
    const blob = await html2pdf()
      .set(pdfOptions)
      .from(pdfContent.value)
      .toPdf()
      .get('pdf')
      .then((pdf) => pdf.output('blob'))

    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = filename
    document.body.appendChild(a)
    a.click()
    a.remove()
    URL.revokeObjectURL(url)

    loadingMessage.close()
    ElNotification({
      title: 'Download Complete',
      message: 'Report has been downloaded successfully!',
      type: 'success',
      duration: 3000
    })
  } catch (err) {
    loadingMessage.close()
    console.error('Blob export failed:', err)
    ElNotification({
      title: 'Error',
      message: 'PDF export failed. Please try again.',
      type: 'error',
      duration: 4000
    })
  }
}

// 👇 Expose to parent
defineExpose({ exportPDF })
</script>
