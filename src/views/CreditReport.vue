<script setup>
import MainLayout from '@/layouts/full/MainLayout.vue'
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const activeTab = ref('first_central')
const loading = ref(false)
import Axios from 'axios'
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
import moment from 'moment'

const tabs = [
  { key: 'first_central', label: 'First Central' },
  { key: 'credit_registry', label: 'Credit Registry' }
]

// FCBC report sections
const personal = ref({})
const summary = ref({})
const creditAgreementSummary = ref([])
const enquiryHistory = ref([])
const employmentHistory = ref([])
const addressHistory = ref([])

const loanAccounts = ref([])
const derogatoryAccounts = ref([])
const writtenOffAccounts = ref([])
const unknownAccounts = ref([])
const inquiryHistory = ref([])

// 🔧 Utility: merge FCBC array into single object
const mergeFcbcArray = (arr) => {
  const result = {}
  for (const item of arr) {
    const key = Object.keys(item)[0]
    result[key] = item[key]
  }
  return result
}

const fetchCreditReport = async (creditReportId) => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  console.log('🟢 Saved auth:', savedAuth)

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const tenantId = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  console.log('🟢 Tenant ID:', tenantId)

  const apiUrl = `https://staging.getjupita.com/api/${tenantId}/get-credit-check-key?unique_key=${creditReportId}`
  loading.value = true

  try {
    const response = await Axios.get(apiUrl, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    })

    console.log('📦 Raw API Response:', response.data.data)

    const report = response.data.data
    console.log('🟢 Full Report:', report)

    const creditHistory = report?.credit_history

    if (!creditHistory) {
      console.warn('❌ credit_history missing in response')
      return
    }

    const rawFcbc = creditHistory?.fcbc_credit_history
    const crnCreditHistory = creditHistory?.crn_credit_history
    const creditRegistryHistory = creditHistory?.credit_registry_history.AccountData

    console.log('🟢 FCBC (array):', rawFcbc)
    console.log('🟢 CRN:', crnCreditHistory)
    console.log('🟢 Credit Registry History Account Data:', creditRegistryHistory)

    if (Array.isArray(rawFcbc) && rawFcbc.length > 0) {
      const fcbcCreditHistory = mergeFcbcArray(rawFcbc)
      console.log('✅ Merged FCBC Object:', fcbcCreditHistory)

      // Personal Details
      const rawPersonal = fcbcCreditHistory?.PersonalDetailsSummary
      personal.value = Array.isArray(rawPersonal) && rawPersonal.length > 0 ? rawPersonal[0] : {}
      console.log('✅ Personal:', personal.value)

      // Summary
      const rawSummary = fcbcCreditHistory?.CreditAccountSummary
      summary.value = Array.isArray(rawSummary) && rawSummary.length > 0 ? rawSummary[0] : {}
      console.log('✅ Summary:', summary.value)

      // Credit Agreements
      // Credit Agreements
      const rawCreditAgreementSummary = fcbcCreditHistory?.CreditAgreementSummary ?? []
      creditAgreementSummary.value = rawCreditAgreementSummary.map((item) => ({
        lender: item.SubscriberName,
        date: moment(item.DateAccountOpened).format('DD/MM/YYYY'),
        rawDate: moment(item.LastUpdatedDate),
        amount: parseFloat(item.OpeningBalanceAmt ?? '0'),
        balance: parseFloat(item.CurrentBalanceAmt ?? '0'),
        status: item.AccountStatus,

        // Additional fields for dropdown
        accountNo: item.AccountNo,
        closedDate: item.ClosedDate,
        duration: item.LoanDuration,
        repaymentFrequency: item.RepaymentFrequency,
        overdue: item.AmountOverdue,
        instalment: item.InstalmentAmount,
        performanceStatus: item.PerformanceStatus,
        currency: item.Currency
      }))

      console.log('✅ Credit Agreement Summary:', creditAgreementSummary.value)

      // Enquiry history
      const rawEnquiryHistoryTop = fcbcCreditHistory?.EnquiryHistoryTop ?? []
      enquiryHistory.value = rawEnquiryHistoryTop.map((item) => ({
        lender: item.SubscriberName,
        date: moment(item.DateRequested).format('DD/MM/YYYY'),
        rawDate: moment(item.LastUpdatedDate)
      }))
      console.log('✅ Enquiry History:', enquiryHistory.value)

      // Employment history
      const rawEmploymentHistory = fcbcCreditHistory?.EmploymentHistory ?? []
      employmentHistory.value = rawEmploymentHistory.map((item) => ({
        employerName: item.EmployerDetail,
        date: moment(item.UpdateDate).format('DD/MM/YYYY'),
        rawDate: moment(item.UpdateDate)
      }))
      console.log('✅ Employment History:', employmentHistory.value)

      // Address history
      const rawAddressHistory = fcbcCreditHistory?.AddressHistory ?? []
      addressHistory.value = rawAddressHistory.map((item) => ({
        address: item.Address1,
        date: moment(item.UpdateDate).format('DD/MM/YYYY'),
        rawDate: moment(item.UpdateDate)
      }))
      console.log('✅ Address History:', addressHistory.value)

      // credit registry

      if (creditRegistryHistory && Array.isArray(creditRegistryHistory.PerformingAccounts)) {
        loanAccounts.value = creditRegistryHistory.PerformingAccounts.map((account) => ({
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Performing',
          raw: account // optional for future use
        }))

        console.log('✅ loanAccounts (Performing Only):', loanAccounts.value)
      } else {
        console.warn('⚠️ PerformingAccounts not found')
      }
      // Derogatory Accounts Mapping
      if (Array.isArray(creditRegistryHistory.DerogatoryAccounts)) {
        derogatoryAccounts.value = creditRegistryHistory.DerogatoryAccounts.map((account) => ({
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Derogatory',
          raw: account
        }))

        console.log('✅ Derogatory Accounts:', derogatoryAccounts.value)
      } else {
        console.warn('⚠️ DerogatoryAccounts not found')
      }

      if (creditRegistryHistory && Array.isArray(creditRegistryHistory.WrittenOffAccounts)) {
        writtenOffAccounts.value = creditRegistryHistory.WrittenOffAccounts.map((account) => ({
          lender: account.CreditorName || 'N/A',
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          raw: account // optional for future use
        }))

        console.log('✅ writtenOffAccounts:', writtenOffAccounts.value)
      } else {
        console.warn('⚠️ WrittenOffAccounts not found or invalid')
      }

      // Unknown Accounts (status not in any known category)
      if (creditRegistryHistory) {
        const knownStatuses = ['Performing', 'Derogatory', 'WrittenOff']
        const allAccounts = [
          ...(creditRegistryHistory.PerformingAccounts || []),
          ...(creditRegistryHistory.DerogatoryAccounts || []),
          ...(creditRegistryHistory.WrittenOffAccounts || [])
        ]

        const knownIds = new Set(allAccounts.map((acc) => acc.Account_No))

        const unknownRaw = Object.values(creditRegistryHistory)
          .flat()
          .filter((acc) => acc && acc.Account_No && !knownIds.has(acc.Account_No))

        unknownAccounts.value = unknownRaw.map((account) => ({
          lender: account.CreditorName || 'Unknown',
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          raw: account
        }))

        console.log('✅ Unknown Accounts:', unknownAccounts.value)
      }
      // Inquiry History
      if (creditRegistryHistory.InquiryHistory?.length) {
        inquiryHistory.value = creditRegistryHistory.InquiryHistory.map((entry) => ({
          subscriber: entry.Subscriber || 'Unknown',
          date: moment(entry.InquiryDate).format('DD/MM/YYYY'),
          phone: entry.ContactPhone || 'N/A',
          reason: entry.Reason || 'N/A',
          raw: entry
        }))
        console.log('✅ Inquiry History:', inquiryHistory.value)
      }
    } else {
      console.warn('⚠️ fcbc_credit_history is empty or invalid')
    }
  } catch (error) {
    console.error('❌ Error fetching credit report:', error)
  } finally {
    loading.value = false
  }
}

const expandedRow = ref(null)

const toggleRow = (index) => {
  expandedRow.value = expandedRow.value === index ? null : index
}

const loans = ref([
  {
    lender: 'Links Microfinance Bank',
    disbursementDate: '16th Nov, 2024',
    loanAmount: 32500000,
    loanBalance: 0,
    status: 'Closed',
    accountNo: '032SPLG212310004',
    amountOverdue: 937500.1,
    instalmentAmount: 937500.1,
    loanDuration: 7,
    repaymentFrequency: 'Monthly',
    dateOpened: '07/12/2018',
    closedDate: '01/05/2019',
    performanceStatus: 'Performing'
  },
  {
    lender: 'ABC Bank',
    disbursementDate: '10th Jan, 2022',
    loanAmount: 12500000,
    loanBalance: 2500000,
    status: 'Open',
    accountNo: '012XPLG784512005',
    amountOverdue: 200000,
    instalmentAmount: 1000000,
    loanDuration: 12,
    repaymentFrequency: 'Quarterly',
    dateOpened: '01/01/2022',
    closedDate: 'N/A',
    performanceStatus: 'Non-Performing'
  }
])

onMounted(() => {
  const unique_key = route.params.unique_key
  if (unique_key) {
    fetchCreditReport(unique_key)
  } else {
    console.warn('❌ Invalid unique key from route')
    loading.value = false
  }
})
</script>

<template>
  <MainLayout>
    <div class="flex justify-between items-center p-2">
      <!-- Tabs -->
      <div class="flex space-x-4">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          :class="activeTab === tab.key ? 'bg-blue-600 text-white' : 'bg-white text-black'"
          class="px-4 py-2 rounded transition text-sm"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Export Button aligned to the right -->
      <div>
        <v-btn
          no-uppercase
          size="small"
          class="normal-case p-4 bg-blue-600 hover:bg-blue-700 text-white text-none custom-btn"
        >
          <i class="fas fa-download mr-2"></i>
          Export PDF
        </v-btn>
      </div>
    </div>

    <div class="mt-4 p-2 mx-auto space-y-6">
      <!-- Tab Content Transition -->
      <transition name="fade" mode="out-in">
        <div :key="activeTab" class="space-y-6">
          <template v-if="activeTab === 'first_central'">
            <!-- // personal -->
            <div class="bg-white p-6 rounded space-y-4">
              <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600"
              >
                <!-- Column 1 -->
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

                <!-- Column 2 -->
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

                <!-- Column 3 -->
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
            </div>

            <!-- Summary -->
            <div class="bg-white p-6 rounded-md">
              <h2 class="text-md font-semibold mb-6">Summary</h2>

              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600"
              >
                <!-- Row 1 -->
                <div>
                  <p class="mb-1">Total active monthly installment</p>
                  <p class="font-bold text-gray-900">₦{{ summary.TotalMonthlyInstalment }}</p>
                </div>
                <div>
                  <p class="mb-1">Total no of credit facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no of open facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>

                <!-- Row 2 -->
                <div>
                  <p class="mb-1">Total arrear amount</p>
                  <p class="font-bold text-gray-900">₦{{ summary.Amountarrear }}</p>
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
                  <p class="font-bold text-gray-900">
                    {{ summary.TotalAccountarrear }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no of delinquent facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no written off facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
              </div>
            </div>

            <!-- Loan Accounts Table -->
            <div class="bg-white p-6 rounded">
              <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>
              <table class="min-w-full text-left">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2">Lender's Name</th>
                    <th class="p-2">Disbursement Date</th>
                    <th class="p-2">Loan Amount</th>
                    <th class="p-2">Loan Balance</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(loan, index) in loans" :key="index">
                    <!-- Main Row -->
                    <tr class="text-sm border-b">
                      <td class="p-2">{{ loan.lender }}</td>
                      <td class="p-2">{{ loan.disbursementDate }}</td>
                      <td class="p-2">₦{{ loan.loanAmount.toLocaleString() }}</td>
                      <td class="p-2">₦{{ loan.loanBalance.toLocaleString() }}</td>
                      <td class="p-2">
                        <span
                          :class="
                            loan.status === 'Closed'
                              ? 'bg-green-100 text-green-800'
                              : 'bg-red-100 text-red-800'
                          "
                          class="px-2 py-1 rounded text-xs"
                        >
                          {{ loan.status }}
                        </span>
                      </td>
                      <td class="p-2">
                        <button
                          @click="toggleRow(index)"
                          class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700"
                        >
                          {{ expandedRow === index ? 'Hide' : 'View' }}
                        </button>
                      </td>
                    </tr>

                    <!-- Dropdown Details Row -->
                    <tr v-if="expandedRow === index" class="bg-gray-50">
                      <td colspan="6" class="p-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p><strong>Account number:</strong> {{ loan.accountNo }}</p>
                          <p><strong>Loan Amount:</strong> ₦{{ loan.loanAmount }}</p>
                          <p><strong>Current Balance:</strong> ₦{{ loan.loanBalance }}</p>
                          <p><strong>Amount Overdue:</strong> ₦{{ loan.amountOverdue }}</p>
                          <p><strong>Instalment Amount:</strong> ₦{{ loan.instalmentAmount }}</p>
                          <p><strong>Loan Duration:</strong> {{ loan.loanDuration }} months</p>
                          <p><strong>Repayment Frequency:</strong> {{ loan.repaymentFrequency }}</p>
                          <p><strong>Date Account Opened:</strong> {{ loan.dateOpened }}</p>
                          <p><strong>Closed Date:</strong> {{ loan.closedDate }}</p>
                          <p><strong>Performance Status:</strong> {{ loan.performanceStatus }}</p>
                          <p><strong>Account Status:</strong> {{ loan.status }}</p>
                        </div>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>

            <!-- Enquiry & Employment History -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-white rounded p-4">
                <h2 class="font-semibold text-md mb-4">Enquiry History</h2>
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

              <div class="bg-white rounded p-4">
                <h2 class="font-semibold text-md mb-4">Employment History</h2>
                <table class="w-full text-sm text-left">
                  <thead class="bg-gray-100">
                    <tr>
                      <th class="p-2">Employer Name</th>
                      <th class="p-2">Date Updated</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(employment, index) in employmentHistory"
                      :key="index"
                      class="border-b"
                    >
                      <td class="p-2">{{ employment.employerName }}</td>
                      <td class="p-2">{{ employment.date }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Address History -->
            <div class="bg-white rounded p-4">
              <h2 class="font-semibold text-md mb-4">Address History</h2>
              <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="p-2">Address</th>
                    <th class="p-2">Date Updated</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(address, index) in addressHistory" :key="index" class="border-b">
                    <td class="p-2">{{ address.address }}</td>
                    <td class="p-2">{{ address.date }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </template>

          <template v-else-if="activeTab === 'credit_registry'">
            <!-- Personal Details Summary -->
            <div class="bg-white p-6 rounded space-y-4">
              <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600"
              >
                <!-- Column 1 -->
                <div>
                  <p class="mb-1">Last Name</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Gender</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Phone Number</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Latest Residential Address</p>
                  <p class="font-bold text-gray-900 leading-snug"></p>
                </div>

                <!-- Column 2 -->
                <div>
                  <p class="mb-1">First Name</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Bank Verification Number</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Work Telephone</p>
                  <p class="font-bold text-gray-900"></p>
                </div>

                <!-- Column 3 -->
                <div>
                  <p class="mb-1">Other Names</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Date of Birth</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Home Telephone</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
              </div>
            </div>

            <div class="bg-white p-6 rounded-md">
              <h2 class="text-md font-semibold mb-6">Summary</h2>

              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600"
              >
                <!-- Row 1 -->
                <div>
                  <p class="mb-1">Total active monthly installment</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no of credit facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no of open facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>

                <!-- Row 2 -->
                <div>
                  <p class="mb-1">Total arrear amount</p>
                  <p class="font-bold text-gray-900">₦</p>
                </div>
                <div>
                  <p class="mb-1">Total outstanding debts</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no of closed credit facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>

                <!-- Row 3 -->
                <div>
                  <p class="mb-1">Total no of account in arrears</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no of delinquent facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
                <div>
                  <p class="mb-1">Total no written off facilities</p>
                  <p class="font-bold text-gray-900"></p>
                </div>
              </div>
            </div>

            <div class="bg-white p-6 rounded">
              <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>
              <table class="min-w-full text-left">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2">Lender's Name</th>
                    <th class="p-2">Disbursement Date</th>
                    <th class="p-2">Loan Amount</th>
                    <th class="p-2">Loan Balance</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(loan, index) in loans" :key="index" class="text-sm border-b">
                    <td class="p-2">{{ loan.lender }}</td>
                    <td class="p-2">{{ loan.disbursementDate }}</td>
                    <td class="p-2">₦{{ loan.loanAmount.toLocaleString() }}</td>
                    <td class="p-2">₦{{ loan.loanBalance.toLocaleString() }}</td>
                    <td class="p-2">
                      <span
                        :class="
                          loan.status === 'Closed'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800'
                        "
                        class="px-2 py-1 rounded text-xs"
                      >
                        {{ loan.status }}
                      </span>
                    </td>
                    <td class="p-2">
                      <button
                        @click="toggleRow(index)"
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700"
                      >
                        {{ expandedRow === index ? 'Hide' : 'View' }}
                      </button>
                    </td>
                  </tr>

                  <!-- Dropdown Section -->
                  <tr v-if="expandedRow === index" class="bg-gray-50">
                    <td colspan="6" class="p-4">
                      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <p><strong>Account number:</strong> {{ loan.accountNo }}</p>
                        <p><strong>Loan Amount:</strong> ₦{{ loan.loanAmount }}</p>
                        <p><strong>Current Balance:</strong> ₦{{ loan.loanBalance }}</p>
                        <p><strong>Amount Overdue:</strong> ₦{{ loan.amountOverdue }}</p>
                        <p><strong>Instalment Amount:</strong> ₦{{ loan.instalmentAmount }}</p>
                        <p><strong>Loan Duration:</strong> {{ loan.loanDuration }} months</p>
                        <p><strong>Repayment Frequency:</strong> {{ loan.repaymentFrequency }}</p>
                        <p><strong>Date Account Opened:</strong> {{ loan.dateOpened }}</p>
                        <p><strong>Closed Date:</strong> {{ loan.closedDate }}</p>
                        <p><strong>Performance Status:</strong> {{ loan.performanceStatus }}</p>
                        <p><strong>Account Status:</strong> {{ loan.status }}</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Derogatory Loan Accounts -->
            <div class="bg-white p-6 rounded-md shadow">
              <h2 class="text-md font-semibold mb-4 text-red-700">Derogatory Loan Accounts</h2>
              <table class="min-w-full text-left border text-sm">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="p-3 border-b">Lender's Name</th>
                    <th class="p-3 border-b">Disbursement Date</th>
                    <th class="p-3 border-b">Loan Amount</th>
                    <th class="p-3 border-b">Loan Balance</th>
                    <th class="p-3 border-b">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="derogatoryAccounts.length === 0">
                    <td colspan="5" class="p-4 text-center text-gray-500">
                      No derogatory accounts found.
                    </td>
                  </tr>
                  <tr
                    v-for="(loan, index) in derogatoryAccounts"
                    :key="'derogatory-' + index"
                    class="hover:bg-gray-50"
                  >
                    <td class="p-3 border-b">{{ loan.lender }}</td>
                    <td class="p-3 border-b">{{ loan.date }}</td>
                    <td class="p-3 border-b">₦{{ loan.amount.toLocaleString() }}</td>
                    <td class="p-3 border-b">₦{{ loan.balance.toLocaleString() }}</td>
                    <td class="p-3 border-b">
                      <button
                        class="bg-red-600 text-white text-xs px-3 py-1 rounded hover:bg-red-700"
                      >
                        View
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Written Off Accounts -->
            <div class="bg-white p-6 rounded mb-6">
              <h2 class="text-md font-semibold mb-4">Written Off Accounts</h2>
              <table class="min-w-full text-left">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2">Lender's Name</th>
                    <th class="p-2">Disbursement Date</th>
                    <th class="p-2">Loan Amount</th>
                    <th class="p-2">Loan Balance</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in writtenOffAccounts" :key="i" class="text-sm">
                    <td class="p-2">{{ item.lender }}</td>
                    <td class="p-2">{{ item.date }}</td>
                    <td class="p-2">₦{{ item.amount.toLocaleString() }}</td>
                    <td class="p-2">₦{{ item.balance.toLocaleString() }}</td>
                    <td class="p-2">
                      <button
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700"
                      >
                        View
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Unknown Account Status -->
            <div class="bg-white p-6 rounded mb-6">
              <h2 class="text-md font-semibold mb-4">Unknown Account Status</h2>
              <table class="min-w-full text-left">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2">Lender's Name</th>
                    <th class="p-2">Disbursement Date</th>
                    <th class="p-2">Loan Amount</th>
                    <th class="p-2">Loan Balance</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in unknownAccounts" :key="i" class="text-sm">
                    <td class="p-2">{{ item.lender }}</td>
                    <td class="p-2">{{ item.date }}</td>
                    <td class="p-2">₦{{ item.amount.toLocaleString() }}</td>
                    <td class="p-2">₦{{ item.balance.toLocaleString() }}</td>
                    <td class="p-2">
                      <button
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700"
                      >
                        View
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Inquiry History -->
            <div class="bg-white p-6 rounded mb-6">
              <h2 class="text-md font-semibold mb-4">Inquiry History</h2>
              <table class="min-w-full text-left">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2">Subscriber Name</th>
                    <th class="p-2">Inquiry Date</th>
                    <th class="p-2">Contact Phone</th>
                    <th class="p-2">Inquiry Reason</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(item, i) in inquiryHistory"
                    :key="i"
                    class="text-sm hover:bg-gray-50 transition-colors"
                  >
                    <td class="p-2">{{ item.subscriber }}</td>
                    <td class="p-2">{{ item.date }}</td>
                    <td class="p-2">{{ item.phone }}</td>
                    <td class="p-2">{{ item.reason }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </template>
        </div>
      </transition>
    </div>
  </MainLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.custom-btn {
  text-transform: none;
  background-color: #1f5aa3;
}
</style>
