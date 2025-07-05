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
    <div class="p-6 bg-white rounded shadow-sm max-w-5xl mx-auto space-y-6">
      <!-- Tabs -->
      <div class="flex space-x-4">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          :class="activeTab === tab.key ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
          class="px-4 py-2 rounded transition"
        >
          {{ tab.label }}
        </button>
        <button class="ml-auto bg-blue-600 text-white px-4 py-2 rounded">Export PDF</button>
      </div>

      <!-- Tab Content Transition -->
      <transition name="fade" mode="out-in">
        <div :key="activeTab" class="space-y-6">
          <template v-if="activeTab === 'first_central'">
            <!-- Only showing the same data for all tabs, you can customize per tab if needed -->
            <div class="bg-gray-50 p-6 rounded space-y-4">
              <h2 class="text-lg font-semibold mb-4">Personal details summary</h2>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                <div><strong>Last Name:</strong> {{ reportData.personal.lastName }}</div>
                <div><strong>Gender:</strong> {{ reportData.personal.gender }}</div>
                <div><strong>Phone Number:</strong> {{ reportData.personal.phone }}</div>

                <div><strong>First Name:</strong> {{ reportData.personal.firstName }}</div>
                <div><strong>Bank Verification Number:</strong> {{ reportData.personal.bvn }}</div>
                <div><strong>Work Telephone:</strong> {{ reportData.personal.workPhone }}</div>

                <div><strong>Other Names:</strong> {{ reportData.personal.otherNames }}</div>
                <div><strong>Date of Birth:</strong> {{ reportData.personal.dob }}</div>
                <div><strong>Home Telephone:</strong> {{ reportData.personal.homePhone }}</div>

                <div class="md:col-span-3">
                  <strong>Latest Residential Address:</strong> {{ reportData.personal.address }}
                </div>
              </div>
            </div>

            <div class="bg-gray-50 p-6 rounded space-y-4">
              <h2 class="text-lg font-semibold mb-4">Summary</h2>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                <div>
                  <strong>Total active monthly installment:</strong><br />
                  ₦{{ reportData.summary.monthlyInstallment }}
                </div>
                <div>
                  <strong>Total no of credit facilities:</strong><br />
                  {{ reportData.summary.noOfFacilities }}
                </div>
                <div>
                  <strong>Total no of open facilities:</strong><br />
                  {{ reportData.summary.openFacilities }}
                </div>

                <div>
                  <strong>Total arrear amount:</strong><br />
                  ₦{{ reportData.summary.arrearAmount }}
                </div>
                <div>
                  <strong>Total outstanding debts:</strong><br />
                  {{ reportData.summary.outstandingDebts }}
                </div>
                <div>
                  <strong>Total no of closed credit facilities:</strong><br />
                  {{ reportData.summary.closedFacilities }}
                </div>

                <div>
                  <strong>Total no of account in arrears:</strong><br />
                  {{ reportData.summary.noOfAccountsInArrears }}
                </div>
                <div>
                  <strong>Total no of delinquent facilities:</strong><br />
                  {{ reportData.summary.noOfDelinquentFacilities }}
                </div>
                <div>
                  <strong>Total no written off facilities:</strong><br />
                  {{ reportData.summary.writtenOffFacilities }}
                </div>
              </div>
            </div>

            <!-- Loan Accounts Table -->
            <div class="bg-white shadow rounded p-4">
              <h2 class="font-semibold text-lg mb-4">Loan Accounts</h2>
              <table class="w-full text-left text-sm border">
                <thead class="bg-gray-100 text-gray-700">
                  <tr>
                    <th class="p-2 border">Lender's Name</th>
                    <th class="p-2 border">Disbursement Date</th>
                    <th class="p-2 border">Loan Amount</th>
                    <th class="p-2 border">Loan Balance</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Action</th>
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
              <div class="bg-white shadow rounded p-4">
                <h2 class="font-semibold text-lg mb-4">Enquiry History</h2>
                <table class="w-full text-sm text-left border">
                  <thead class="bg-gray-100 text-gray-700">
                    <tr>
                      <th class="p-2 border">Lender's Name</th>
                      <th class="p-2 border">Date Requested</th>
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

              <div class="bg-white shadow rounded p-4">
                <h2 class="font-semibold text-lg mb-4">Employment History</h2>
                <table class="w-full text-sm text-left border">
                  <thead class="bg-gray-100 text-gray-700">
                    <tr>
                      <th class="p-2 border">Employer Name</th>
                      <th class="p-2 border">Date Updated</th>
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
            <div class="bg-white shadow rounded p-4">
              <h2 class="font-semibold text-lg mb-4">Address History</h2>
              <table class="w-full text-sm text-left border">
                <thead class="bg-gray-100 text-gray-700">
                  <tr>
                    <th class="p-2 border">Address</th>
                    <th class="p-2 border">Date Updated</th>
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
            <div class="bg-white p-6 rounded-lg shadow-md">
              <h2 class="text-xl font-semibold mb-4">Personal details summary</h2>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div><strong>Last Name:</strong> {{ personal.lastName }}</div>
                <div><strong>Gender:</strong> {{ personal.gender }}</div>
                <div><strong>Phone Number:</strong> {{ personal.phone }}</div>
                <div><strong>First Name:</strong> {{ personal.firstName }}</div>
                <div><strong>BVN:</strong> {{ personal.bvn }}</div>
                <div><strong>Work Telephone:</strong> {{ personal.workPhone }}</div>
                <div><strong>Other Names:</strong> {{ personal.otherNames }}</div>
                <div><strong>Date of Birth:</strong> {{ personal.dob }}</div>
                <div><strong>Home Telephone:</strong> {{ personal.homePhone }}</div>
                <div class="col-span-full"><strong>Address:</strong> {{ personal.address }}</div>
              </div>
            </div>

            <!-- Summary -->
            <div class="bg-white p-6 rounded-lg shadow-md">
              <h2 class="text-xl font-semibold mb-4">Summary</h2>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                  <strong>Total active monthly installment:</strong> ₦{{
                    summary.totalMonthlyInstallment.toLocaleString()
                  }}
                </div>
                <div>
                  <strong>Total no of credit facilities:</strong>
                  {{ summary.totalCreditFacilities }}
                </div>
                <div>
                  <strong>Total no of open facilities:</strong> {{ summary.totalOpenFacilities }}
                </div>
                <div>
                  <strong>Total credit amount:</strong> ₦{{
                    summary.totalCreditAmount.toLocaleString()
                  }}
                </div>
                <div>
                  <strong>Total outstanding debts:</strong> ₦{{
                    summary.outstandingDebts.toLocaleString()
                  }}
                </div>
                <div>
                  <strong>Total no of closed credit facilities:</strong>
                  {{ summary.closedFacilities }}
                </div>
                <div>
                  <strong>Total no of account in arrears:</strong> {{ summary.accountsInArrears }}
                </div>
                <div>
                  <strong>Total no of delinquent facilities:</strong>
                  {{ summary.delinquentFacilities }}
                </div>
                <div>
                  <strong>Total no of written off facilities:</strong>
                  {{ summary.writtenOffFacilities }}
                </div>
              </div>
            </div>

            <!-- Accounts Table Section -->
            <div
              v-for="(section, key) in accountSections"
              :key="key"
              class="bg-white p-6 rounded-lg shadow-md"
            >
              <h2 class="text-xl font-semibold mb-4">{{ section.title }}</h2>
              <table class="min-w-full text-left border">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2 border">Lender's Name</th>
                    <th class="p-2 border">Disbursement Date</th>
                    <th class="p-2 border">Loan Amount</th>
                    <th class="p-2 border">Loan Balance</th>
                    <th class="p-2 border">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in section.data" :key="i" class="text-sm">
                    <td class="p-2 border">{{ item.lender }}</td>
                    <td class="p-2 border">{{ item.date }}</td>
                    <td class="p-2 border">₦{{ item.amount.toLocaleString() }}</td>
                    <td class="p-2 border">₦{{ item.balance.toLocaleString() }}</td>
                    <td class="p-2 border">
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
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
              <h2 class="text-xl font-semibold mb-4">Derogatory Accounts</h2>
              <table class="min-w-full text-left border">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2 border">Lender's Name</th>
                    <th class="p-2 border">Disbursement Date</th>
                    <th class="p-2 border">Loan Amount</th>
                    <th class="p-2 border">Loan Balance</th>
                    <th class="p-2 border">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in derogatoryAccounts" :key="i" class="text-sm">
                    <td class="p-2 border">{{ item.lender }}</td>
                    <td class="p-2 border">{{ item.date }}</td>
                    <td class="p-2 border">₦{{ item.amount.toLocaleString() }}</td>
                    <td class="p-2 border">₦{{ item.balance.toLocaleString() }}</td>
                    <td class="p-2 border">
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
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
              <h2 class="text-xl font-semibold mb-4">Written Off Accounts</h2>
              <table class="min-w-full text-left border">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2 border">Lender's Name</th>
                    <th class="p-2 border">Disbursement Date</th>
                    <th class="p-2 border">Loan Amount</th>
                    <th class="p-2 border">Loan Balance</th>
                    <th class="p-2 border">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in writtenOffAccounts" :key="i" class="text-sm">
                    <td class="p-2 border">{{ item.lender }}</td>
                    <td class="p-2 border">{{ item.date }}</td>
                    <td class="p-2 border">₦{{ item.amount.toLocaleString() }}</td>
                    <td class="p-2 border">₦{{ item.balance.toLocaleString() }}</td>
                    <td class="p-2 border">
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
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
              <h2 class="text-xl font-semibold mb-4">Unknown Account Status</h2>
              <table class="min-w-full text-left border">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2 border">Lender's Name</th>
                    <th class="p-2 border">Disbursement Date</th>
                    <th class="p-2 border">Loan Amount</th>
                    <th class="p-2 border">Loan Balance</th>
                    <th class="p-2 border">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in unknownAccounts" :key="i" class="text-sm">
                    <td class="p-2 border">{{ item.lender }}</td>
                    <td class="p-2 border">{{ item.date }}</td>
                    <td class="p-2 border">₦{{ item.amount.toLocaleString() }}</td>
                    <td class="p-2 border">₦{{ item.balance.toLocaleString() }}</td>
                    <td class="p-2 border">
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
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
              <h2 class="text-xl font-semibold mb-4">Inquiry History</h2>
              <table class="min-w-full text-left border">
                <thead>
                  <tr class="bg-gray-100 text-sm">
                    <th class="p-2 border">Subscriber Name</th>
                    <th class="p-2 border">Inquiry Date</th>
                    <th class="p-2 border">Contact Phone</th>
                    <th class="p-2 border">Inquiry Reason</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in inquiryHistory" :key="i" class="text-sm">
                    <td class="p-2 border">{{ item.subscriber }}</td>
                    <td class="p-2 border">{{ item.date }}</td>
                    <td class="p-2 border">{{ item.phone }}</td>
                    <td class="p-2 border">{{ item.reason }}</td>
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
</style>
