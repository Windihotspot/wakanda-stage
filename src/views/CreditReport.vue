<script setup>
import MainLayout from '@/layouts/full/MainLayout.vue'
import { ref } from 'vue'

const activeTab = ref('first_central')

const tabs = [
  { key: 'first_central', label: 'First Central' },
  { key: 'credit_registry', label: 'Credit Registry' },
  { key: 'crc', label: 'CRC' }
]

// Mock Data (for example)
const reportData = {
  personal: {
    lastName: 'Eze',
    firstName: 'Titus',
    otherNames: 'Doe',
    gender: 'Male',
    bvn: '223344556677',
    dob: '25-09-1922',
    phone: '09065515225',
    workPhone: '09065515225',
    homePhone: '09065515225',
    address: '15, Amazing Grace CDA, Oloko Road, Ota, Ogun State'
  },
  summary: {
    monthlyInstallment: '203,456,567.89',
    arrearAmount: '3,456,567.89',
    noOfAccountsInArrears: 1,
    noOfFacilities: 8,
    outstandingDebts: '3,456,567.89',
    noOfDelinquentFacilities: 1,
    openFacilities: 8,
    closedFacilities: 0,
    writtenOffFacilities: 0
  }
}

const loanAccounts = ref([
  {
    lender: 'Links Microfinance Bank',
    date: '16th Nov, 2024',
    amount: '₦32,500,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Links Microfinance Bank',
    date: '16th Nov, 2024',
    amount: '₦3,500,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Carbon Microfinance Bank',
    date: '16th Nov, 2024',
    amount: '₦2,500,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Carbon Microfinance Bank',
    date: '16th Nov, 2024',
    amount: '₦500,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Crednet Technology Limited',
    date: '16th Nov, 2024',
    amount: '₦350,000.00',
    balance: '₦350,000.00',
    status: 'Open'
  },
  {
    lender: 'AB Microfinance Bank',
    date: '16th Nov, 2024',
    amount: '₦32,500,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Addosser Microfinance Bank',
    date: '16th Nov, 2024',
    amount: '₦3,000,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Shara Technology Mgt. Nig. Ltd.',
    date: '16th Nov, 2024',
    amount: '₦7,000,000.00',
    balance: '₦0.00',
    status: 'Closed'
  },
  {
    lender: 'Shara Technology Mgt. Nig. Ltd.',
    date: '16th Nov, 2024',
    amount: '₦3,000,000.00',
    balance: '₦3,000,000.00',
    status: 'Open'
  },
  {
    lender: 'ESPORTA HOTEL AND SUITES',
    date: '16th Nov, 2024',
    amount: '₦73,000,000.00',
    balance: '₦0.00',
    status: 'Closed'
  }
])

const enquiryHistory = ref([
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024' }
])

const employmentHistory = ref([
  { employer: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { employer: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { employer: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { employer: 'Links Microfinance Bank', date: '16th Nov, 2024' },
  { employer: 'Links Microfinance Bank', date: '16th Nov, 2024' }
])

const addressHistory = ref([
  { address: '39, JAKES ESTATE, ABRAHAM ADESANYA, AJAH, ETI OSA, LAGOS', date: '16th Nov, 2024' },
  { address: '39, JAKES ESTATE, ABRAHAM ADESANYA, AJAH, ETI OSA, LAGOS', date: '16th Nov, 2024' }
])

const personal = ref({
  lastName: 'Eze',
  firstName: 'Titus',
  otherNames: 'Doe',
  gender: 'Male',
  phone: '09056518285',
  workPhone: '09056518285',
  homePhone: '09056518285',
  address: '15, Amazing Grace CDA, Oloko Road, Ota, Ogun State',
  bvn: '223344566677',
  dob: '25-09-1982'
})

const summary = ref({
  totalMonthlyInstallment: 203456567.89,
  totalCreditFacilities: 8,
  totalOpenFacilities: 8,
  totalCreditAmount: 3546567.89,
  outstandingDebts: 3465567.89,
  closedFacilities: 0,
  accountsInArrears: 1,
  delinquentFacilities: 1,
  writtenOffFacilities: 0
})

const performingAccounts = [
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024', amount: 32500000, balance: 0 },
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024', amount: 3500000, balance: 0 },
  { lender: 'Carbon Microfinance Bank', date: '16th Nov, 2024', amount: 2500000, balance: 0 },
  { lender: 'Carbon Microfinance Bank', date: '16th Nov, 2024', amount: 500000, balance: 0 }
]

const delinquentAccounts = [
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024', amount: 32500000, balance: 0 }
]

const closedAccounts = [...performingAccounts]

const accountSections = {
  performing: {
    title: 'Performing Accounts',
    data: performingAccounts
  },
  delinquent: {
    title: 'Delinquent Accounts',
    data: delinquentAccounts
  },
  closed: {
    title: 'Closed Accounts',
    data: closedAccounts
  }
}

const derogatoryAccounts = [
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024', amount: 32500000, balance: 0 },
  { lender: 'Links Microfinance Bank', date: '16th Nov, 2024', amount: 3500000, balance: 0 },
  { lender: 'Carbon Microfinance Bank', date: '16th Nov, 2024', amount: 2500000, balance: 0 },
  { lender: 'Carbon Microfinance Bank', date: '16th Nov, 2024', amount: 500000, balance: 0 }
]

const writtenOffAccounts = [...derogatoryAccounts]

const unknownAccounts = [...derogatoryAccounts]

const inquiryHistory = [
  {
    subscriber: 'Links Microfinance Bank',
    date: '16th Nov, 2024',
    phone: '09056512345',
    reason: 'Other'
  },
  {
    subscriber: 'Links Microfinance Bank',
    date: '16th Nov, 2024',
    phone: '09056512345',
    reason: 'Other'
  },
  {
    subscriber: 'Links Microfinance Bank',
    date: '16th Nov, 2024',
    phone: '09056512345',
    reason: 'Other'
  },
  {
    subscriber: 'Carbon Microfinance Bank',
    date: '16th Nov, 2024',
    phone: '09056512345',
    reason: 'Other'
  }
]
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

    <div class="mt-4 p-2 max-w-5xl mx-auto space-y-6">
      <!-- Tab Content Transition -->
      <transition name="fade" mode="out-in">
        <div :key="activeTab" class="space-y-6">
          <template v-if="activeTab === 'first_central'">
            <!-- Only showing the same data for all tabs, you can customize per tab if needed -->
            <div class="bg-white p-6 rounded space-y-4">
              <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8 text-sm text-gray-600"
              >
                <!-- Column 1 -->
                <div>
                  <p class="mb-1">Last Name</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.lastName }}</p>
                </div>
                <div>
                  <p class="mb-1">Gender</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.gender }}</p>
                </div>
                <div>
                  <p class="mb-1">Phone Number</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.phone }}</p>
                </div>
                <div>
                  <p class="mb-1">Latest Residential Address</p>
                  <p class="font-bold text-gray-900 leading-snug">
                    {{ reportData.personal.address }}
                  </p>
                </div>

                <!-- Column 2 -->
                <div>
                  <p class="mb-1">First Name</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.firstName }}</p>
                </div>
                <div>
                  <p class="mb-1">Bank Verification Number</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.bvn }}</p>
                </div>
                <div>
                  <p class="mb-1">Work Telephone</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.workPhone }}</p>
                </div>

                <!-- Column 3 -->
                <div>
                  <p class="mb-1">Other Names</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.otherNames }}</p>
                </div>
                <div>
                  <p class="mb-1">Date of Birth</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.dob }}</p>
                </div>
                <div>
                  <p class="mb-1">Home Telephone</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.homePhone }}</p>
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
                  <p class="font-bold text-gray-900">
                    ₦{{ reportData.summary.monthlyInstallment }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no of credit facilities</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.noOfFacilities }}</p>
                </div>
                <div>
                  <p class="mb-1">Total no of open facilities</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.openFacilities }}</p>
                </div>

                <!-- Row 2 -->
                <div>
                  <p class="mb-1">Total arrear amount</p>
                  <p class="font-bold text-gray-900">₦{{ reportData.summary.arrearAmount }}</p>
                </div>
                <div>
                  <p class="mb-1">Total outstanding debts</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.outstandingDebts }}</p>
                </div>
                <div>
                  <p class="mb-1">Total no of closed credit facilities</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.closedFacilities }}</p>
                </div>

                <!-- Row 3 -->
                <div>
                  <p class="mb-1">Total no of account in arrears</p>
                  <p class="font-bold text-gray-900">
                    {{ reportData.summary.noOfAccountsInArrears }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no of delinquent facilities</p>
                  <p class="font-bold text-gray-900">
                    {{ reportData.summary.noOfDelinquentFacilities }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no written off facilities</p>
                  <p class="font-bold text-gray-900">
                    {{ reportData.summary.writtenOffFacilities }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Loan Accounts Table -->
            <div class="bg-white rounded p-4">
              <h2 class="font-semibold text-md mb-4">Loan Accounts</h2>
              <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 text-gray-700">
                  <tr>
                    <th class="p-2">Lender's Name</th>
                    <th class="p-2">Disbursement Date</th>
                    <th class="p-2">Loan Amount</th>
                    <th class="p-2">Loan Balance</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(loan, index) in loanAccounts" :key="index" class="border-b">
                    <td class="p-2">{{ loan.lender }}</td>
                    <td class="p-2">{{ loan.date }}</td>
                    <td class="p-2">{{ loan.amount }}</td>
                    <td class="p-2">{{ loan.balance }}</td>
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
                      <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs">View</button>
                    </td>
                  </tr>
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
                      <td class="p-2">{{ employment.employer }}</td>
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
                  <p class="font-bold text-gray-900">{{ reportData.personal.lastName }}</p>
                </div>
                <div>
                  <p class="mb-1">Gender</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.gender }}</p>
                </div>
                <div>
                  <p class="mb-1">Phone Number</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.phone }}</p>
                </div>
                <div>
                  <p class="mb-1">Latest Residential Address</p>
                  <p class="font-bold text-gray-900 leading-snug">
                    {{ reportData.personal.address }}
                  </p>
                </div>

                <!-- Column 2 -->
                <div>
                  <p class="mb-1">First Name</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.firstName }}</p>
                </div>
                <div>
                  <p class="mb-1">Bank Verification Number</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.bvn }}</p>
                </div>
                <div>
                  <p class="mb-1">Work Telephone</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.workPhone }}</p>
                </div>

                <!-- Column 3 -->
                <div>
                  <p class="mb-1">Other Names</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.otherNames }}</p>
                </div>
                <div>
                  <p class="mb-1">Date of Birth</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.dob }}</p>
                </div>
                <div>
                  <p class="mb-1">Home Telephone</p>
                  <p class="font-bold text-gray-900">{{ reportData.personal.homePhone }}</p>
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
                  <p class="font-bold text-gray-900">
                    ₦{{ reportData.summary.monthlyInstallment }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no of credit facilities</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.noOfFacilities }}</p>
                </div>
                <div>
                  <p class="mb-1">Total no of open facilities</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.openFacilities }}</p>
                </div>

                <!-- Row 2 -->
                <div>
                  <p class="mb-1">Total arrear amount</p>
                  <p class="font-bold text-gray-900">₦{{ reportData.summary.arrearAmount }}</p>
                </div>
                <div>
                  <p class="mb-1">Total outstanding debts</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.outstandingDebts }}</p>
                </div>
                <div>
                  <p class="mb-1">Total no of closed credit facilities</p>
                  <p class="font-bold text-gray-900">{{ reportData.summary.closedFacilities }}</p>
                </div>

                <!-- Row 3 -->
                <div>
                  <p class="mb-1">Total no of account in arrears</p>
                  <p class="font-bold text-gray-900">
                    {{ reportData.summary.noOfAccountsInArrears }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no of delinquent facilities</p>
                  <p class="font-bold text-gray-900">
                    {{ reportData.summary.noOfDelinquentFacilities }}
                  </p>
                </div>
                <div>
                  <p class="mb-1">Total no written off facilities</p>
                  <p class="font-bold text-gray-900">
                    {{ reportData.summary.writtenOffFacilities }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Accounts Table Section -->
            <div v-for="(section, key) in accountSections" :key="key" class="bg-white p-6 rounded">
              <h2 class="text-md font-semibold mb-4">{{ section.title }}</h2>
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
                  <tr v-for="(item, i) in section.data" :key="i" class="text-sm">
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

            <!-- Derogatory Accounts -->
            <div class="bg-white p-6 rounded mb-6">
              <h2 class="text-md font-semibold mb-4">Derogatory Accounts</h2>
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
                  <tr v-for="(item, i) in derogatoryAccounts" :key="i" class="text-sm">
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
                  <tr v-for="(item, i) in inquiryHistory" :key="i" class="text-sm">
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
