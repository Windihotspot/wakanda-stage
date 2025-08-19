<template>
  <MainLayout>
    <v-container>
      <!-- Wallet Balance Card -->
      <div class="bg-[#1f5aa3] text-white rounded-md p-6 shadow-xl">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold tracking-wide">Current Wallet Balance</h2>
            <div class="flex gap-4">
              <p class="mt-3 text-3xl font-extrabold tracking-tight">
                {{ formatCurrency(balance) }}
              </p>
              <button
                @click="fetchWallet"
                class="text-white hover:text-gray-200 m-5"
                title="Refresh Balance"
              >
                <i :class="['fas fa-rotate-right text-2xl', isLoading && 'fa-spin']"></i>
              </button>
            </div>
          </div>
          <div class="flex flex-col items-end space-y-2">
            <button
              @click="openFundWallet"
              class="bg-white text-blue-600 font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-gray-300 transition-colors"
            >
              Fund Wallet
            </button>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="mt-6 pa-4">
        <v-tabs class="mb-6" v-model="activeTab" color="primary">
          <v-tab value="credit">Credit History</v-tab>
          <v-tab value="debit">Debit History</v-tab>
        </v-tabs>

        <v-window v-model="activeTab">
          <!-- Credit History Tab -->
          <v-window-item value="credit">
            <div v-if="isLoading" class="flex flex-col items-center justify-center min-h-[200px]">
              <v-progress-circular indeterminate color="blue" size="40" width="4" />
              <span class="mt-2 text-gray-600 text-sm">Loading wallet history...</span>
            </div>

            <div v-else-if="creditHistory.length > 0" class="overflow-x-auto">
              <table class="min-w-full">
                <thead class="font-semibold uppercase text-sm leading-normal">
                  <tr>
                    <th class="py-3 px-6 text-left">Date</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-left">Amount</th>
                  </tr>
                </thead>

                <tbody class="text-gray-700 text-sm font-light bg-white rounded-xl shadow-lg">
                  <tr
                    class="border-b border-gray-200 font-normal"
                    v-for="(credit, index) in creditHistory"
                    :key="index"
                  >
                    <td class="py-3 px-6 text-left">
                      {{ moment(credit.date).format('MMMM Do, YYYY') }}
                    </td>
                    <td class="py-3 px-6 text-left">{{ credit.description }}</td>
                    <td class="py-3 px-6 text-left">{{ formatCurrency(credit.amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-else class="text-center py-10">
              <p class="text-red-500">No credit history</p>
            </div>
          </v-window-item>

          <!-- Debit History Tab -->
          <v-window-item value="debit">
            <div v-if="isLoading" class="flex flex-col items-center justify-center min-h-[200px]">
              <v-progress-circular indeterminate color="blue" size="40" width="4" />
              <span class="mt-2 text-gray-600 text-sm">Loading wallet history...</span>
            </div>

            <div v-else-if="debitHistory.length > 0" class="overflow-x-auto">
              <table class="min-w-full">
                <thead class="font-semibold uppercase text-sm leading-normal">
                  <tr>
                    <th class="py-3 px-6 text-left">Date</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-left">Amount</th>
                  </tr>
                </thead>

                <tbody class="text-gray-700 text-sm font-light bg-white rounded-xl shadow-lg">
                  <tr
                    class="border-b border-gray-200 font-normal"
                    v-for="(debit, index) in debitHistory"
                    :key="index"
                  >
                    <td class="py-3 px-6 text-left">
                      {{ moment(debit.date).format('MMMM Do, YYYY') }}
                    </td>
                    <td class="py-3 px-6 text-left">{{ debit.description }}</td>
                    <td class="py-3 px-6 text-left">{{ formatCurrency(debit.amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-else class="text-center py-10">
              <p class="text-red-500">No debit history</p>
            </div>
          </v-window-item>
        </v-window>
      </div>

      <div
        v-if="loading"
        class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-10"
      >
        <v-progress-circular indeterminate color="green" class="mx-auto my-4" />
      </div>

      <!-- Fund Wallet Modal -->
      <v-dialog v-model="fundWalletDialog" max-width="400px">
        <v-card>
          <v-card-title class="text-h5 m-2 font-semibold">Fund Wallet</v-card-title>
          <v-card-text>
            <div class="mt-2">
              <v-text-field
                label="Amount"
                v-model="formattedAmount"
                variant="outlined"
                color="blue"
              >
              </v-text-field>
            </div>
          </v-card-text>
          <v-card-actions>
            <v-btn text @click="closeFundWallet">Cancel</v-btn>
            <v-btn color="primary" @click="fundWallet">Fund</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </MainLayout>

  <!-- Payment Widget Modal -->
  <v-dialog v-model="paymentModal" max-width="600px">
    <v-card>
      <v-card-title class="text-h6 font-weight-bold">Complete Payment</v-card-title>

      <!-- <div v-if="loadingPayment" class="d-flex justify-center align-center">
        <v-progress-circular indeterminate color="primary" />
      </div> -->
      <div>
        <v-card-text>
          <iframe
            :src="paymentLink"
            width="100%"
            height="400px"
            frameborder="0"
            allowfullscreen
          ></iframe>
        </v-card-text>
      </div>

      <v-card-actions>
        <v-spacer />
        <v-btn color="danger" @click="paymentModal = false">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { reactive } from 'vue'
import { ElNotification } from 'element-plus'
import moment from 'moment'
import { onMounted, ref, nextTick } from 'vue'
import MainLayout from '@/layouts/full/MainLayout.vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
const router = useRouter()
import axios from 'axios'
const loading = ref(false)
const creditHistory = ref([])
const activeTab = ref('credit') // Default tab to "Credit"
const debitHistory = ref([]) // For debit transactions
const authStore = useAuthStore()
import { useFormattedFields } from '@/composables/useFormattedFields'
// Make reactive target object
const form = reactive({
  amount: 0
})

// Use the composable for currency formatting
const formattedAmount = useFormattedFields(form, 'amount', { currency: true })

function formatCurrency(number) {
  return new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 2
  }).format(number || 0)
}
const isLoading = ref(false)

// Table Headers
const headers = [
  { title: 'Date', key: 'date', sortable: true },
  { title: 'Description', key: 'description', sortable: true },
  { title: 'Amount', key: 'amount', sortable: true },
  { title: 'Old Balance', key: 'old_balance', sortable: true },
  { title: 'New Balance', key: 'new_balance', sortable: true }
]
const loadingPayment = ref(true)

// Fund Wallet Modal
const fundWalletDialog = ref(false)
const paymentModal = ref(false)
const paymentLink = ref('')
const balance = ref('')

// Open Modal
const openFundWallet = () => {
  fundWalletDialog.value = true
}

const closeFundWallet = () => {
  fetchWallet()
  fundWalletDialog.value = false
}

const fetchWallet = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const tenantId = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  const API_URL = `https://staging.getjupita.com/api/${tenantId}/get-tenant-wallet`
  isLoading.value = true
  // console.log('fetchWallet - token:', token)
  // console.log('fetchWallet - tenantId:', tenantId)
  // console.log('fetchWallet - API_URL:', API_URL)
  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    console.log('fetch wallet data:', response)

    balance.value = response.data.data.wallet.balance
    isLoading.value = false
  } catch (error) {
    console.log('Error fetching wallet:', error.response.data)
  } finally {
    isLoading.value = false
  }
}

const fetchWalletTransactions = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  console.log(savedAuth)

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const tenantId = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  const API_URL = `https://staging.getjupita.com/api/${tenantId}/get-wallet-transactions`
  isLoading.value = true

  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    const transactions = response.data.data.transactions.data

    // Separate into credit and debit histories
    creditHistory.value = []
    debitHistory.value = []

    transactions.forEach((tx) => {
      const data = tx.transaction_data ? tx.transaction_data : {}

      const base = {
        date: tx.created_at,
        amount: tx.amount,
        description: data.description || (data.type === 'topup' ? 'Top Up' : 'Wallet Transaction')
      }

      if (data.transaction_type === 'debit') {
        debitHistory.value.push(base)
      } else if (data.type === 'topup') {
        creditHistory.value.push(base)
      }
    })
  } catch (error) {
    console.log('Error fetching wallet transactions:', error.response.data)
  } finally {
    isLoading.value = false
  }
}

const fundWallet = async () => {
  const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

  const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

  const tenantId = savedAuth
    ? savedAuth.user?.business_name
      ? savedAuth.user?.id
      : savedAuth.user?.tenant_id
    : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
        ?.value

  const loading = ref(false)

  if (!form.amount || form.amount < 500000) {
    ElNotification({
      title: 'Invalid Amount',
      message: 'Minimum funding amount is ₦500,000.',
      type: 'warning',
      duration: 4000
    })
    return
  }

  const API_URL = `https://staging.getjupita.com/api/${tenantId}/initialize-payment`
  console.log('fund wallet formatted amount:', formattedAmount.value)
  console.log('fund wallet amount:', form.amount)

  loading.value = true

  try {
    fundWalletDialog.value = false

    ElNotification({
      title: 'Payment Initialized',
      message: 'Redirecting to payment link...',
      type: 'success',
      duration: 4000
    })

    const response = await axios.post(
      API_URL,
      { amount: form.amount },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      }
    )

    console.log(response)
    form.amount = ''
    paymentLink.value = response.data.data.link
    paymentModal.value = true
    fetchWallet()
  } catch (error) {
    console.log('Funding error raw:', error?.response?.data)

    const rawError = error?.response?.data?.data?.error
    let cleanMessage = 'Failed to initiate payment. Please try again.'

    if (rawError) {
      // Find the JSON part by locating the first '{'
      const jsonStart = rawError.indexOf('{')
      if (jsonStart !== -1) {
        const jsonString = rawError.slice(jsonStart)
        try {
          const parsed = JSON.parse(jsonString)

          // Combine available error details
          const parts = [parsed?.message, parsed?.meta?.nextStep, parsed?.code].filter(Boolean)

          if (parts.length) {
            cleanMessage = parts.join(', ')
          }
        } catch (parseErr) {
          console.error('Error parsing Paystack error JSON:', parseErr)
        }
      }
    }

    ElNotification({
      title: 'Payment Failed',
      message: cleanMessage,
      type: 'error',
      duration: 5000
    })
  } finally {
    loading.value = false
  }
}

const onIframeLoad = () => {
  loadingPayment.value = false
}

onMounted(async () => {
  fetchWallet()
  fetchWalletTransactions()
})
</script>

<style scoped>
.v-card {
  border-radius: 12px;
}
.v-btn {
  text-transform: none;
}
</style>
