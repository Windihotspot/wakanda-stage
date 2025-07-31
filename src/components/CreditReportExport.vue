<template>
  <div ref="pdfContent" class="p-6 space-y-6 bg-white text-gray-800">
    <!-- First Central Section -->
    <h1 class="text-2xl font-bold">First Central</h1>
    <section>
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

      <!-- Summary -->
      <div class="bg-white p-6 rounded-md">
        <h2 class="text-md font-semibold mb-6">Summary</h2>

        <!-- Show data if summary object exists and is not empty -->
        <div
          v-if="summary && Object.keys(summary).length > 0"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8 text-sm text-gray-600"
        >
          <!-- Row 1 -->
          <div>
            <p class="mb-1">Total active monthly installment</p>
            <p class="font-bold text-gray-900">₦{{ summary.TotalMonthlyInstalment }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of credit facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalCreditFacilities }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of open facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalOpenFacilities }}</p>
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
            <p class="font-bold text-gray-900">{{ summary.TotalClosedFacilities }}</p>
          </div>

          <!-- Row 3 -->
          <div>
            <p class="mb-1">Total no of account in arrears</p>
            <p class="font-bold text-gray-900">{{ summary.TotalAccountarrear }}</p>
          </div>
          <div>
            <p class="mb-1">Total no of delinquent facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalDelinquentFacilities }}</p>
          </div>
          <div>
            <p class="mb-1">Total no written off facilities</p>
            <p class="font-bold text-gray-900">{{ summary.TotalWrittenOffFacilities }}</p>
          </div>
        </div>

        <!-- Fallback if no data -->
        <div v-else class="text-sm text-gray-500 italic">No summary data available.</div>
      </div>

      <!-- Loan Accounts Table -->
      <div class="bg-white p-6 rounded">
        <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>

        <div v-if="creditAgreementSummary && creditAgreementSummary.length > 0">
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
              <template v-for="(loan, index) in creditAgreementSummary" :key="index">
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
                      View
                    </button>
                  </td>
                </tr>

                <!-- Dropdown Details Row -->
                <tr class="bg-gray-50">
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
            <table class="w-full text-sm text-left">
              <thead class="bg-gray-100">
                <tr>
                  <th class="p-2">Employer Name</th>
                  <th class="p-2">Date Updated</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(employment, index) in employmentHistory" :key="index" class="border-b">
                  <td class="p-2">{{ employment.employerName }}</td>
                  <td class="p-2">{{ employment.date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-sm text-gray-500 italic">No employment history available.</div>
        </div>
      </div>

      <!-- Address History -->
      <div class="bg-white rounded p-4">
        <h2 class="font-semibold text-md mb-4">Address History</h2>
        <div v-if="addressHistory && addressHistory.length > 0">
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
        <div v-else class="text-sm text-gray-500 italic">No address history available.</div>
      </div>
    </section>

    <!-- Credit Registry Section -->
    <h1 class="text-2xl font-bold">Credit registry</h1>
    <section>
      <div class="bg-white p-6 rounded space-y-4">
        <h2 class="text-md font-semibold mb-4">Personal details summary</h2>
        <div class="text-gray-500 text-sm italic">No personal data available.</div>
      </div>

      <div class="bg-white p-6 rounded-md">
        <h2 class="text-md font-semibold mb-6">Summary</h2>

        <div class="text-gray-500 text-sm italic">No summary data available.</div>
      </div>

      <!-- Performaing Accounts Table -->
      <div class="bg-white p-6 rounded">
        <h2 class="text-md font-semibold mb-4">Performing Accounts</h2>

        <div v-if="loanAccounts && loanAccounts.length > 0">
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
              <template v-for="(loan, index) in loanAccounts" :key="index">
                <!-- Main Row -->
                <tr class="text-sm border-b">
                  <td class="p-2">{{ loan.lender }}</td>
                  <td class="p-2">{{ loan.date }}</td>
                  <td class="p-2">₦{{ loan.amount.toLocaleString() }}</td>
                  <td class="p-2">₦{{ loan.balance.toLocaleString() }}</td>
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
                      View
                    </button>
                  </td>
                </tr>

                <!-- Dropdown Details Row -->
                <tr class="bg-gray-50">
                  <td colspan="6" class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                      <p><strong>Account number:</strong> {{ loan.accountNo }}</p>
                      <p><strong>Loan Amount:</strong> ₦{{ loan.amount }}</p>
                      <p><strong>Current Balance:</strong> ₦{{ loan.balance }}</p>
                      <p><strong>Amount Overdue:</strong> ₦{{ loan.overdue }}</p>
                      <p><strong>Instalment Amount:</strong> ₦{{ loan.instalment }}</p>
                      <p><strong>Loan Duration:</strong> {{ loan.duration }} months</p>
                      <p><strong>Repayment Frequency:</strong> {{ loan.repaymentFrequency }}</p>
                      <p><strong>Date Account Opened:</strong> {{ loan.date }}</p>
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

        <div v-else class="text-sm text-gray-500 italic">No loan accounts available.</div>
      </div>

      <!-- Delinquents Accounts -->
      <div class="bg-white p-6 rounded-md shadow">
        <h2 class="text-md font-semibold mb-4">Deliquents Accounts</h2>
        <table class="min-w-full text-left text-sm">
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
            <tr v-if="delinquentAccounts.length === 0">
              <td colspan="5" class="p-4 text-center text-gray-500">
                No delinquents accounts available.
              </td>
            </tr>
            <tr
              v-for="(loan, index) in delinquentAccounts"
              :key="'delinquents-' + index"
              class="hover:bg-gray-50"
            >
              <td class="p-3 border-b">{{ loan.lender }}</td>
              <td class="p-3 border-b">{{ loan.date }}</td>
              <td class="p-3 border-b">₦{{ loan.amount.toLocaleString() }}</td>
              <td class="p-3 border-b">₦{{ loan.balance.toLocaleString() }}</td>
              <td class="p-3 border-b">
                <button class="bg-red-600 text-white text-xs px-3 py-1 rounded hover:bg-red-700">
                  View
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Closed Accounts -->
      <div class="bg-white p-6 rounded-md shadow">
        <h2 class="text-md font-semibold mb-4">Closed Accounts</h2>
        <table class="min-w-full text-left text-sm">
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
            <tr v-if="closedAccounts.length === 0">
              <td colspan="5" class="p-4 text-center text-gray-500">
                No closed accounts available.
              </td>
            </tr>
            <tr
              v-for="(loan, index) in closedAccounts"
              :key="'delinquents-' + index"
              class="hover:bg-gray-50"
            >
              <td class="p-3 border-b">{{ loan.lender }}</td>
              <td class="p-3 border-b">{{ loan.date }}</td>
              <td class="p-3 border-b">₦{{ loan.amount.toLocaleString() }}</td>
              <td class="p-3 border-b">₦{{ loan.balance.toLocaleString() }}</td>
              <td class="p-3 border-b">
                <button class="bg-red-600 text-white text-xs px-3 py-1 rounded hover:bg-red-700">
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
        <div v-if="writtenOffAccounts && writtenOffAccounts.length > 0">
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
        <div v-else class="text-sm text-gray-500 italic">No written-off accounts available.</div>
      </div>

      <!-- Unknown Account Status -->
      <div class="bg-white p-6 rounded mb-6">
        <h2 class="text-md font-semibold mb-4">Unknown Accounts Status</h2>
        <div v-if="unknownAccounts && unknownAccounts.length > 0">
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
        <div v-else class="text-sm text-gray-500 italic">No unknown accounts available.</div>
      </div>

      <!-- Inquiry History -->
      <div class="bg-white p-6 rounded mb-6">
        <h2 class="text-md font-semibold mb-4">Inquiry History</h2>
        <div v-if="inquiryHistory && inquiryHistory.length > 0">
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
        <div v-else class="text-sm text-gray-500 italic">No inquiries available.</div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, defineExpose } from 'vue'
import { ElNotification } from 'element-plus'
import html2pdf from 'html2pdf.js'

const props = defineProps({
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
  inquiryHistory: Array
})

const pdfContent = ref(null)

const exportPDF = () => {
  const firstName = props.personal?.FirstName || 'FirstName'
  const lastName = props.personal?.Surname || 'LastName'
  const now = new Date()
  const dateString = now.toISOString().slice(0, 16).replace('T', '_').replace(':', '-')
  const filename = `${firstName}_${lastName}_${dateString}.pdf`

  ElNotification({
    title: 'Preparing PDF',
    message: 'Downloading report...',
    type: 'warning',
    duration: 2000
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
        ElNotification({
          title: 'Download Complete',
          message: 'Report has been downloaded successfully!',
          type: 'success',
          duration: 3000
        })
      })
  }, 500) // slight delay to show the "Preparing" notification
}

// 👇 Expose to parent
defineExpose({ exportPDF })
</script>
