<script setup>
import MainLayout from '@/layouts/full/MainLayout.vue'
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const activeTab = ref('first_central')
const loading = ref(false)
import Axios from 'axios'
import html2pdf from 'html2pdf.js'
import { saveAs } from 'file-saver'
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
import moment from 'moment'
import { ElNotification, ElMessage } from 'element-plus'
import CreditReportExport from '@/components/CreditReportExport.vue'
const savedAuth = localStorage.getItem('data') ? JSON.parse(localStorage.getItem('data')) : null

const token = savedAuth ? savedAuth?.token : computed(() => authStore.token)?.value

const tenantId = savedAuth
  ? savedAuth.user?.business_name
    ? savedAuth.user?.id
    : savedAuth.user?.tenant_id
  : computed(() => (authStore.user?.business_name ? authStore.user.id : authStore.user.tenant_id))
      ?.value

const hitRecord = route.params.hitRecord === 'true'
console.log('hit record:', route.params.hitRecord)
const tabs = [
  { key: 'first_central', label: 'First Central' },
  { key: 'credit_registry', label: 'Credit Registry' }
]
const idType = ref('')
// FCBC report sections
const personal = ref({})
const summary = ref({})
const creditAgreementSummary = ref([])
const enquiryHistory = ref([])
const employmentHistory = ref([])
const addressHistory = ref([])

const loanAccounts = ref([])
const delinquentAccounts = ref([])
const closedAccounts = ref([])
const derogatoryAccounts = ref([])
const writtenOffAccounts = ref([])
const unknownAccounts = ref([])
const inquiryHistory = ref([])

const businessData = ref({})
const directors = ref([])

// credit registry
const creditPersonal = ref({
  Surname: '',
  FirstName: '',
  OtherNames: '',
  Gender: '',
  BirthDate: '',
  BankVerificationNo: '',
  WorkTelephoneNo: '',
  HomeTelephoneNo: '',
  CellularNo: '',
  ResidentialAddress1: ''
})
const creditRegistrybusinessName = ref('')
const creditDirectors = ref([])

const creditRegistryTotalOpen = ref(0)
const creditRegistryTotalClosed = ref(0)
const creditRegistryTotalWrittenOff = ref(0)

function formatNaira(value) {
  if (!value) return '₦0.00'

  // Strip commas or whitespace and parse to float
  const cleaned = parseFloat(String(value).replace(/,/g, '').trim())

  return new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 2
  }).format(isNaN(cleaned) ? 0 : cleaned)
}
function formatCurrency(amount) {

  // Ensure it's a number before formatting
  const numericAmount = Number(amount)

  if (isNaN(numericAmount)) {
    return amount // return original if invalid
  }

  const formatted = new Intl.NumberFormat('en-NG', {
    style: 'currency',
    currency: 'NGN',
    minimumFractionDigits: 2
  }).format(numericAmount)

  return formatted
}

function getStatusColor(status) {
  if (!status || status.trim() === '') return 'grey'

  const normalized = status.toLowerCase().trim()

  if (normalized === 'closed') return 'green'
  if (normalized === 'open') return 'red'
  if (normalized === 'paid up') return 'blue'

  return 'grey' // fallback for unknown values
}

// Normalize FCBC format for both individual (array) and business (object with numeric keys)
const normalizeFcbcInput = (input) => {
  if (Array.isArray(input)) return input

  const result = []
  for (const key in input) {
    if (!isNaN(Number(key))) {
      result.push(input[key])
    }
  }
  return result
}

// Merge keys from each item in the normalized array
const mergeFcbcArray = (arr) => {
  const result = {}
  for (const item of arr) {
    for (const key in item) {
      if (Object.hasOwn(item, key)) {
        result[key] = item[key]
      }
    }
  }
  return result
}

function displayValue(value) {
  return value && value.trim() !== '' ? value : 'N/A'
}

const fetchCreditReport = async (creditReportId) => {
  const apiUrl = `${import.meta.env.VITE_API_BASE_URL}/api/${tenantId}/get-credit-check-key?unique_key=${creditReportId}`
  loading.value = true

  try {
    const response = await Axios.get(apiUrl, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    })

    const report = response.data.data
    const creditHistory = report?.credit_history
    idType.value = creditHistory?.id_type
    console.log('📌 idType', idType.value)
    console.log('🟢 Full Report:', report)

    // ---------------------- FCBC Handling ----------------------
    const rawFcbc = creditHistory?.fcbc_credit_history
    if (rawFcbc) {
      console.log('✅ FCBC (array)Data:', rawFcbc)
      console.log('🔍 typeof rawFcbc:', typeof rawFcbc)
      console.log('🔍 Fcbc isArray:', Array.isArray(rawFcbc))

      const normalizedFcbc = normalizeFcbcInput(rawFcbc)
      console.log('🔍 Normalized FCBC:', normalizedFcbc)

      // ---------------------- Fcbc Handling ----------------------
      const fcbcCreditHistory = mergeFcbcArray(normalizedFcbc)
      console.log('✅ Merged FCBC Object:', fcbcCreditHistory)

      if (idType.value === 'business') {
        // business and directors

        const businessDataArray = fcbcCreditHistory?.BusinessData || []
        const directorInfoArray = fcbcCreditHistory?.DirectorInformation || []

        businessData.value = businessDataArray[0] || {}
        directors.value = directorInfoArray

        console.log('✅FCBC BusinessData:', businessData.value)
        console.log('✅FCBC Directors:', directors.value)

        const rawCreditAgreementSummary = fcbcCreditHistory?.CreditAgreementSummary ?? []

        function safeValue(val) {
          return val === undefined || val === null || val === '' ? 'N/A' : val
        }

        creditAgreementSummary.value = rawCreditAgreementSummary.map((item, index) => ({
          uid: `${item.AccountNo || 'acc'}-${index}`,
          lender: safeValue(item.SubscriberName),
          date: item.DateAccountOpened
            ? moment(item.DateAccountOpened).format('DD/MM/YYYY')
            : 'N/A',
          amount: formatNaira(item.OpeningBalanceAmt ?? 0),
          balance: formatNaira(item.CurrentBalanceAmt ?? 0),
          status: safeValue(item.AccountStatus),
          accountNo: safeValue(item.AccountNo),
          closedDate: item.ClosedDate ? moment(item.ClosedDate).format('DD/MM/YYYY') : 'N/A',
          duration: safeValue(item.LoanDuration),
          repaymentFrequency: safeValue(item.RepaymentFrequency),
          overdue: formatNaira(item.AmountOverdue ?? 0),
          instalment: formatNaira(item.InstalmentAmount ?? 0),
          performanceStatus: safeValue(item.PerformanceStatus),
          currency: safeValue(item.Currency)
        }))

        console.log('✅FCBC Credit Agreement Summary:', creditAgreementSummary.value)

        // summary
        const rawSummary = fcbcCreditHistory?.FacilityPerformanceSummary
        summary.value = Array.isArray(rawSummary) && rawSummary.length > 0 ? rawSummary[0] : {}

        const totalOpen = creditAgreementSummary.value.filter(
          (item) => item.status?.toLowerCase() === 'open'
        ).length

        const totalClosed = creditAgreementSummary.value.filter(
          (item) => item.status?.toLowerCase() === 'closed'
        ).length

        const totalWrittenOff = creditAgreementSummary.value.filter(
          (item) => item.performanceStatus?.toLowerCase() === 'written-off'
        ).length

        // Merge into summary
        summary.value.TotalOpenFacilities = totalOpen
        summary.value.TotalClosedFacilities = totalClosed
        summary.value.TotalWrittenOffFacilities = totalWrittenOff

        console.log('✅FCBC Summary (Business):', summary.value)
        console.log('Open:', totalOpen, 'Closed:', totalClosed, 'Written-off:', totalWrittenOff)

        const rawEnquiryHistoryTop = fcbcCreditHistory?.EnquiryHistoryTop ?? []
        enquiryHistory.value = rawEnquiryHistoryTop
          .filter((item) => item.SubscriberName?.trim() && item.DateRequested?.trim())
          .map((item) => ({
            lender: item.SubscriberName.trim(),
            date: moment(item.DateRequested).format('DD/MM/YYYY'),
            rawDate: moment(item.LastUpdatedDate)
          }))
        console.log('✅ FCBC Enquiry History (cleaned):', enquiryHistory.value)

        const rawEmploymentHistory = fcbcCreditHistory?.EmploymentHistory ?? []

        employmentHistory.value = rawEmploymentHistory
          .filter((item) => item.EmployerDetail && item.EmployerDetail.trim() !== '')
          .map((item) => ({
            employerName: item.EmployerDetail,
            date:
              item.UpdateDate && item.UpdateDate.trim() !== ''
                ? moment(item.UpdateDate).format('DD/MM/YYYY')
                : 'N/A',
            rawDate:
              item.UpdateDate && item.UpdateDate.trim() !== '' ? moment(item.UpdateDate) : null
          }))
        console.log('✅FCBC Employment History:', employmentHistory.value)

        const rawAddressHistory = fcbcCreditHistory?.AddressHistory ?? []
        addressHistory.value = rawAddressHistory.map((item) => ({
          address: [
            item.CommercialAddress1,
            item.CommercialAddress2,
            item.CommercialAddress3,
            item.CommercialAddress4
          ]
            .map((part) => part?.trim())
            .filter((part) => part)
            .join(', '),
          date: item.UpDateOnDate || ''
        }))
        console.log('✅FCBC Address History:', addressHistory.value)
      } else if (idType.value === 'individual') {
        //  credit agreement summary which is loan accounts
        const rawCreditAgreementSummary = fcbcCreditHistory?.CreditAgreementSummary ?? []
        creditAgreementSummary.value = rawCreditAgreementSummary.map((item, index) => ({
          uid: `${item.AccountNo || 'acc'}-${index}`,
          lender: item.SubscriberName,
          date: moment(item.DateAccountOpened).format('DD/MM/YYYY'),
          amount: formatNaira(item.OpeningBalanceAmt ?? '0'),
          balance: formatNaira(item.CurrentBalanceAmt ?? '0'),
          status: item.AccountStatus,
          accountNo: item.AccountNo,
          closedDate: moment(item.ClosedDate).format('DD/MM/YYYY'),
          duration: item.LoanDuration,
          repaymentFrequency: item.RepaymentFrequency,
          overdue: formatNaira(item.AmountOverdue),
          instalment: formatNaira(item.InstalmentAmount),
          performanceStatus: item.PerformanceStatus,
          currency: item.Currency
        }))

        console.log('✅FCBC Credit Agreement Summary (Individual):', creditAgreementSummary.value)

        // summary
        const rawSummary = fcbcCreditHistory?.CreditAccountSummary
        summary.value = Array.isArray(rawSummary) && rawSummary.length > 0 ? rawSummary[0] : {}

        const totalOpen = creditAgreementSummary.value.filter(
          (item) => item.status?.toLowerCase() === 'open'
        ).length

        const totalClosed = creditAgreementSummary.value.filter(
          (item) => item.status?.toLowerCase() === 'closed'
        ).length

        const totalWrittenOff = creditAgreementSummary.value.filter(
          (item) => item.performanceStatus?.toLowerCase() === 'written-off'
        ).length

        // Merge into summary
        summary.value.TotalOpenFacilities = totalOpen
        summary.value.TotalClosedFacilities = totalClosed
        summary.value.TotalWrittenOffFacilities = totalWrittenOff

        console.log('✅FCBC Summary (Individual):', summary.value)
        console.log('Open:', totalOpen, 'Closed:', totalClosed, 'Written-off:', totalWrittenOff)

        // personal
        const rawPersonalDetails = fcbcCreditHistory?.PersonalDetailsSummary ?? []
        personal.value =
          Array.isArray(rawPersonalDetails) && rawPersonalDetails.length > 0
            ? rawPersonalDetails[0]
            : {}

        console.log('✅ FCBC Personal Details (Individual):', personal.value)

        const rawEnquiryHistoryTop = fcbcCreditHistory?.EnquiryHistoryTop ?? []
        enquiryHistory.value = rawEnquiryHistoryTop
          .filter((item) => item.SubscriberName?.trim() && item.DateRequested?.trim())
          .map((item) => ({
            lender: item.SubscriberName.trim(),
            date: moment(item.DateRequested).format('DD/MM/YYYY'),
            rawDate: moment(item.LastUpdatedDate)
          }))
        console.log('✅ FCBC Enquiry History (Individual):', enquiryHistory.value)

        const rawEmploymentHistory = fcbcCreditHistory?.EmploymentHistory ?? []

        employmentHistory.value = rawEmploymentHistory
          .filter((item) => item.EmployerDetail && item.EmployerDetail.trim() !== '')
          .map((item) => ({
            employerName: item.EmployerDetail,
            date:
              item.UpdateDate && item.UpdateDate.trim() !== ''
                ? moment(item.UpdateDate).format('DD/MM/YYYY')
                : 'N/A',
            rawDate:
              item.UpdateDate && item.UpdateDate.trim() !== '' ? moment(item.UpdateDate) : null
          }))

        console.log('✅FCBC Employment History (Individual):', employmentHistory.value)

        const rawAddressHistory = fcbcCreditHistory?.AddressHistory ?? []
        addressHistory.value = rawAddressHistory.map((item) => ({
          address: [
            item.CommercialAddress1,
            item.CommercialAddress2,
            item.CommercialAddress3,
            item.CommercialAddress4
          ]
            .map((part) => part?.trim())
            .filter((part) => part)
            .join(', '),
          date: item.UpDateOnDate || ''
        }))
        console.log('✅FCBC Address History (Individual):', addressHistory.value)
      }
    } else {
      console.warn('⚠️ No FCBC credit history found')
    }

    // ---------------------- Credit Registry Handling ----------------------
    const creditRegistryHistory = creditHistory?.credit_registry_history?.AccountData
    const creditRegistryPersonal = creditHistory?.credit_registry_history?.PersonalDetail
    console.log('🟠 Credit Registry AccountData:', creditRegistryHistory)
    console.log('🟠 Credit Registry PersonalDetail:', creditRegistryPersonal)

    if (!creditRegistryHistory) {
      console.warn('⚠️ No Credit Registry data found')
      return
    }

    if (idType.value === 'business') {
      // Directors
      const creditPersonalDirectors = creditRegistryPersonal

      if (
        creditPersonalDirectors &&
        creditPersonalDirectors.Name &&
        creditPersonalDirectors.IDs?.length
      ) {
        const fullNameParts = creditPersonalDirectors.Name.trim().split(' ')
        const surname = fullNameParts[fullNameParts.length - 1]
        const firstName = fullNameParts[0]
        const otherNames = fullNameParts.slice(1, fullNameParts.length - 1).join(' ')

        creditDirectors.value = [
          {
            firstName,
            otherNames,
            surname,
            identificationNumber: creditPersonalDirectors.IDs.join(' | ')
          }
        ]
        console.log('credit registry directors:', creditDirectors.value)
      } else {
        creditDirectors.value = []
      }

      // Performing Accounts
      const performingAccounts = creditRegistryHistory?.PerformingAccounts
      if (Array.isArray(creditRegistryHistory.PerformingAccounts)) {
        // Extract business name from first account's Account_Owners
        const firstOwners = performingAccounts[0]?.Account_Owners
        const businessName =
          Array.isArray(firstOwners) && firstOwners.length > 0 ? firstOwners[0].FullName : null

        creditRegistrybusinessName.value = firstOwners
        loanAccounts.value = creditRegistryHistory.PerformingAccounts.map((account, index) => ({
          uid: `${account.Account_No || 'acc'}-${index}`,
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Performing',
          raw: account,
          accountNo: account.Account_No,
          closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
          duration: account.Term,
          repaymentFrequency: account.Term_Frequency,
          overdue: account.AmountOverdue,
          instalment: account.Minimum_Installment,
          performanceStatus: account.Account_Status,
          currency: account.Currency
        }))
        console.log('✅ Credit Registry Performing Accounts:', loanAccounts.value)
      } else {
        console.warn('⚠️ No PerformingAccounts found')
      }

      // Summary

      if (Array.isArray(performingAccounts)) {
        creditRegistryTotalOpen.value = performingAccounts.filter(
          (item) => item.status?.toLowerCase() === 'open'
        ).length

        creditRegistryTotalClosed.value = performingAccounts.filter(
          (item) => item.status?.toLowerCase() === 'closed'
        ).length

        creditRegistryTotalWrittenOff.value = performingAccounts.filter(
          (item) => item.performanceStatus?.toLowerCase() === 'written-off'
        ).length
      } else {
        console.warn('⚠️ No PerformingAccounts found in Credit Registry')
      }

      console.log(
        'Open:',
        creditRegistryTotalOpen,
        'Closed:',
        creditRegistryTotalClosed,
        'Written-off:',
        creditRegistryTotalWrittenOff
      )

      // Delinquent Accounts
      if (Array.isArray(creditRegistryHistory.DelinquentAccounts)) {
        delinquentAccounts.value = creditRegistryHistory.DelinquentAccounts.map(
          (account, index) => ({
            uid: `${account.Account_No || 'acc'}-${index}`,
            lender: account.CreditorName,
            date: moment(account.Date_Opened).format('DD/MM/YYYY'),
            amount: account.Credit_Limit || 0,
            balance: account.Balance || 0,
            status: account.Account_Status || 'Delinquent',
            raw: account,
            accountNo: account.Account_No,
            closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
            duration: account.Term,
            repaymentFrequency: account.Term_Frequency,
            overdue: account.AmountOverdue,
            instalment: account.Minimum_Installment,
            performanceStatus: account.Account_Status,
            currency: account.Currency
          })
        )
        console.log('✅ Credit Registry Delinquent Accounts:', delinquentAccounts.value)
      } else {
        console.warn('⚠️ No DelinquentAccounts found')
      }

      // Closed Accounts
      if (Array.isArray(creditRegistryHistory.ClosedAccounts)) {
        closedAccounts.value = creditRegistryHistory.ClosedAccounts.map((account, index) => ({
          uid: `${account.Account_No || 'acc'}-${index}`,
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Closed',
          raw: account,
          accountNo: account.Account_No,
          closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
          duration: account.Term,
          repaymentFrequency: account.Term_Frequency,
          overdue: account.AmountOverdue,
          instalment: account.Minimum_Installment,
          performanceStatus: account.Account_Status,
          currency: account.Currency
        }))
        console.log('✅ Credit Registry Closed Accounts:', closedAccounts.value)
      } else {
        console.warn('⚠️ No ClosedAccounts found')
      }

      // Derogatory Accounts
      if (Array.isArray(creditRegistryHistory.DerogatoryAccounts)) {
        derogatoryAccounts.value = creditRegistryHistory.DerogatoryAccounts.map(
          (account, index) => ({
            uid: `${account.Account_No || 'acc'}-${index}`,
            lender: account.CreditorName,
            date: moment(account.Date_Opened).format('DD/MM/YYYY'),
            amount: account.Credit_Limit || 0,
            balance: account.Balance || 0,
            status: account.Account_Status || 'Derogatory',
            raw: account,
            accountNo: account.Account_No,
            closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
            duration: account.Term,
            repaymentFrequency: account.Term_Frequency,
            overdue: account.AmountOverdue,
            instalment: account.Minimum_Installment,
            performanceStatus: account.Account_Status,
            currency: account.Currency
          })
        )
        console.log('✅ Credit Registry Derogatory Accounts:', derogatoryAccounts.value)
      } else {
        console.warn('⚠️ No DerogatoryAccounts found')
      }

      // Written Off Accounts
      if (Array.isArray(creditRegistryHistory.WrittenOffAccounts)) {
        writtenOffAccounts.value = creditRegistryHistory.WrittenOffAccounts.map(
          (account, index) => ({
            uid: `${account.Account_No || 'acc'}-${index}`,
            lender: account.CreditorName,
            date: moment(account.Date_Opened).format('DD/MM/YYYY'),
            amount: account.Credit_Limit || 0,
            balance: account.Balance || 0,
            status: account.Account_Status || 'Written off',
            raw: account,
            accountNo: account.Account_No,
            closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
            duration: account.Term,
            repaymentFrequency: account.Term_Frequency,
            overdue: account.AmountOverdue,
            instalment: account.Minimum_Installment,
            performanceStatus: account.Account_Status,
            currency: account.Currency
          })
        )
        console.log('✅ Credit Registry Written Off Accounts:', writtenOffAccounts.value)
      } else {
        console.warn('⚠️ No WrittenOffAccounts found')
      }

      // Unknown Status Accounts
      if (Array.isArray(creditRegistryHistory.unknownAccounts)) {
        unknownAccounts.value = creditRegistryHistory.unknownAccounts.map((account, index) => ({
          uid: `${account.Account_No || 'acc'}-${index}`,
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Unknown',
          raw: account,
          accountNo: account.Account_No,
          closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
          duration: account.Term,
          repaymentFrequency: account.Term_Frequency,
          overdue: account.AmountOverdue,
          instalment: account.Minimum_Installment,
          performanceStatus: account.Account_Status,
          currency: account.Currency
        }))
        console.log('✅ Credit Registry Unknown Status Accounts:', unknownAccounts.value)
      } else {
        console.warn('⚠️ No unknownAccounts found')
      }

      // Inquiry History
      if (Array.isArray(creditRegistryHistory.InquiryHistory)) {
        inquiryHistory.value = creditRegistryHistory.InquiryHistory.map((entry, index) => ({
          uid: `inq-${index}`,
          subscriber: entry.Subscriber || 'Unknown',
          date: moment(entry.InquiryDate).format('DD/MM/YYYY'),
          phone: entry.ContactPhone || 'N/A',
          reason: entry.Reason || 'N/A',
          raw: entry
        }))
        console.log('✅ Credit Registry Inquiry History:', inquiryHistory.value)
      } else {
        console.warn('⚠️ No InquiryHistory found')
      }
    } else if (idType.value === 'individual') {
      // personal
      const crRaw = creditHistory?.credit_registry_history?.PersonalDetail

      if (crRaw) {
        const [surname = '', first = '', other = ''] = (crRaw.Name || '').trim().split(/\s+/)

        creditPersonal.value = {
          Surname: surname,
          FirstName: first,
          OtherNames: other,
          Gender: crRaw.Gender || '',
          BirthDate: crRaw.DOBI ? moment(crRaw.DOBI).format('DD/MM/YYYY') : '',
          BankVerificationNo: '',
          WorkTelephoneNo: Array.isArray(crRaw.PhoneNumbers) ? crRaw.PhoneNumbers[1] || '' : '',
          HomeTelephoneNo: Array.isArray(crRaw.PhoneNumbers) ? crRaw.PhoneNumbers[1] || '' : '',
          CellularNo: Array.isArray(crRaw.PhoneNumbers) ? crRaw.PhoneNumbers[0] || '' : '',
          ResidentialAddress1: crRaw?.Address
        }
        console.log('credit registry personal:', creditPersonal.value)
      }

      // Performing Accounts
      const performingAccounts = creditRegistryHistory?.PerformingAccounts
      if (Array.isArray(creditRegistryHistory.PerformingAccounts)) {
        loanAccounts.value = creditRegistryHistory.PerformingAccounts.map((account, index) => ({
          uid: `${account.Account_No || 'acc'}-${index}`,
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: formatCurrency(account.Credit_Limit || 0),
          balance: account.Balance || 0,
          status: account.Account_Status || 'Performing',
          raw: account,
          accountNo: account.Account_No,
          closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
          duration: account.Term,
          repaymentFrequency: account.RepaymentFrequency,
          overdue: account.AmountOverdue,
          instalment: account.Minimum_Installment,
          performanceStatus: account.Account_Status,
          currency: account.Currency
        }))
        console.log('✅ Credit Registry Performing Accounts:', loanAccounts.value)
      } else {
        console.warn('⚠️ No PerformingAccounts found')
      }

      // summary
      if (Array.isArray(performingAccounts)) {
        creditRegistryTotalOpen.value = performingAccounts.filter(
          (item) => item.status?.toLowerCase() === 'open'
        ).length

        creditRegistryTotalClosed.value = performingAccounts.filter(
          (item) => item.status?.toLowerCase() === 'closed'
        ).length

        creditRegistryTotalWrittenOff.value = performingAccounts.filter(
          (item) => item.performanceStatus?.toLowerCase() === 'written-off'
        ).length
      } else {
        console.warn('⚠️ No PerformingAccounts found in Credit Registry')
      }

      console.log(
        'Open:',
        creditRegistryTotalOpen,
        'Closed:',
        creditRegistryTotalClosed,
        'Written-off:',
        creditRegistryTotalWrittenOff
      )

      // Delinquent Accounts
      if (Array.isArray(creditRegistryHistory.DelinquentAccounts)) {
        delinquentAccounts.value = creditRegistryHistory.DelinquentAccounts.map(
          (account, index) => ({
            uid: `${account.Account_No || 'acc'}-${index}`,
            lender: account.CreditorName,
            date: moment(account.Date_Opened).format('DD/MM/YYYY'),
            amount: account.Credit_Limit || 0,
            balance: account.Balance || 0,
            status: account.Account_Status || 'Delinquent',
            raw: account,
            accountNo: account.Account_No,
            closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
            duration: account.Term,
            repaymentFrequency: account.Term_Frequency,
            overdue: account.AmountOverdue,
            instalment: account.Minimum_Installment,
            performanceStatus: account.Account_Status,
            currency: account.Currency
          })
        )
        console.log('✅ Credit Registry Delinquent Accounts:', delinquentAccounts.value)
      } else {
        console.warn('⚠️ No DelinquentAccounts found')
      }

      // Closed Accounts
      if (Array.isArray(creditRegistryHistory.ClosedAccounts)) {
        closedAccounts.value = creditRegistryHistory.ClosedAccounts.map((account, index) => ({
          uid: `${account.Account_No || 'acc'}-${index}`,
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Closed',
          raw: account,
          accountNo: account.Account_No,
          closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
          duration: account.Term,
          repaymentFrequency: account.Term_Frequency,
          overdue: account.AmountOverdue,
          instalment: account.Minimum_Installment,
          performanceStatus: account.Account_Status,
          currency: account.Currency
        }))
        console.log('✅ Credit Registry Closed Accounts:', closedAccounts.value)
      } else {
        console.warn('⚠️ No ClosedAccounts found')
      }

      // Derogatory Accounts
      if (Array.isArray(creditRegistryHistory.DerogatoryAccounts)) {
        derogatoryAccounts.value = creditRegistryHistory.DerogatoryAccounts.map(
          (account, index) => ({
            uid: `${account.Account_No || 'acc'}-${index}`,
            lender: account.CreditorName,
            date: moment(account.Date_Opened).format('DD/MM/YYYY'),
            amount: account.Credit_Limit || 0,
            balance: account.Balance || 0,
            status: account.Account_Status || 'Closed',
            raw: account,
            accountNo: account.Account_No,
            closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
            duration: account.Term,
            repaymentFrequency: account.Term_Frequency,
            overdue: account.AmountOverdue,
            instalment: account.Minimum_Installment,
            performanceStatus: account.Account_Status,
            currency: account.Currency
          })
        )
        console.log('✅ Credit Registry Derogatory Accounts:', derogatoryAccounts.value)
      } else {
        console.warn('⚠️ No DerogatoryAccounts found')
      }

      // Written Off Accounts
      if (Array.isArray(creditRegistryHistory.WrittenOffAccounts)) {
        writtenOffAccounts.value = creditRegistryHistory.WrittenOffAccounts.map(
          (account, index) => ({
            uid: `${account.Account_No || 'acc'}-${index}`,
            lender: account.CreditorName,
            date: moment(account.Date_Opened).format('DD/MM/YYYY'),
            amount: account.Credit_Limit || 0,
            balance: account.Balance || 0,
            status: account.Account_Status || 'Written off',
            raw: account,
            accountNo: account.Account_No,
            closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
            duration: account.Term,
            repaymentFrequency: account.Term_Frequency,
            overdue: account.AmountOverdue,
            instalment: account.Minimum_Installment,
            performanceStatus: account.Account_Status,
            currency: account.Currency
          })
        )
        console.log('✅ Credit Registry Written Off Accounts:', writtenOffAccounts.value)
      } else {
        console.warn('⚠️ No WrittenOffAccounts found')
      }

      // Unknown Status Accounts
      if (Array.isArray(creditRegistryHistory.unknownAccounts)) {
        unknownAccounts.value = creditRegistryHistory.unknownAccounts.map((account, index) => ({
          uid: `${account.Account_No || 'acc'}-${index}`,
          lender: account.CreditorName,
          date: moment(account.Date_Opened).format('DD/MM/YYYY'),
          amount: account.Credit_Limit || 0,
          balance: account.Balance || 0,
          status: account.Account_Status || 'Unknown',
          raw: account,
          accountNo: account.Account_No,
          closedDate: moment(account.Balance_Date).format('DD/MM/YYYY'),
          duration: account.Term,
          repaymentFrequency: account.Term_Frequency,
          overdue: account.AmountOverdue,
          instalment: account.Minimum_Installment,
          performanceStatus: account.Account_Status,
          currency: account.Currency
        }))
        console.log('✅ Credit Registry Unknown Status Accounts:', unknownAccounts.value)
      } else {
        console.warn('⚠️ No unknownAccounts found')
      }

      // Inquiry History
      if (Array.isArray(creditRegistryHistory.InquiryHistory)) {
        inquiryHistory.value = creditRegistryHistory.InquiryHistory.map((entry, index) => ({
          uid: `inq-${index}`,
          subscriber: entry.Subscriber || 'Unknown',
          date: moment(entry.InquiryDate).format('DD/MM/YYYY'),
          phone: entry.ContactPhone || 'N/A',
          reason: entry.Reason || 'N/A',
          raw: entry
        }))
        console.log('✅ Credit Registry Inquiry History:', inquiryHistory.value)
      } else {
        console.warn('⚠️ No InquiryHistory found')
      }
    }
  } catch (error) {
    console.log('❌ Error fetching credit report:', error)
  } finally {
    loading.value = false
  }
}

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
const delinquentHeaders = [
  { title: "Lender's Name", key: 'lender' },
  { title: 'Disbursement Date', key: 'date' },
  { title: 'Loan Amount', key: 'amount' },
  { title: 'Loan Balance', key: 'balance' },
  { title: 'Action', key: 'action', sortable: false }
]
const closedHeaders = [
  { title: "Lender's Name", key: 'lender' },
  { title: 'Disbursement Date', key: 'date' },
  { title: 'Loan Amount', key: 'amount' },
  { title: 'Loan Balance', key: 'balance' },
  { title: 'Action', key: 'action', sortable: false }
]

const writtenOffHeaders = [
  { title: "Lender's Name", key: 'lender' },
  { title: 'Disbursement Date', key: 'date' },
  { title: 'Loan Amount', key: 'amount' },
  { title: 'Loan Balance', key: 'balance' },
  { title: 'Action', key: 'action', sortable: false }
]

const unknownHeaders = [
  { title: "Lender's Name", key: 'lender' },
  { title: 'Disbursement Date', key: 'date' },
  { title: 'Loan Amount', key: 'amount' },
  { title: 'Loan Balance', key: 'balance' },
  { title: 'Action', key: 'action', sortable: false }
]

onMounted(() => {
  const unique_key = route.params.unique_key
  if (unique_key) {
    fetchCreditReport(unique_key)
  } else {
    console.warn('❌ Invalid unique key from route')
    loading.value = false
  }
})

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
// let unique_key = null;
// const exportPDF = async () => {
//   try {
//     console.log("▶️ Export PDF button clicked");

//     if (!unique_key) {
//       console.error("❌ No credit report ID found (unique_key is missing)");
//       alert("No credit report ID found");
//       return;
//     }

//     const url = 'http://localhost:3000/export-pdf';
//     console.log("📡 Export endpoint URL:", url);
//     console.log("📦 Sending params:", { tenantId, creditReportId: unique_key });

//     const response = await Axios.get(url, {
//       params: { tenantId, creditReportId: unique_key },
//       headers: {
//         Authorization: `Bearer ${token}` // 👈 pass token here
//       },
//       responseType: "blob", // critical for downloading PDF
//     });

//     console.log("✅ Raw response:", response);
//     console.log("📏 Response data size:", response.data.size || "unknown");

//     const blob = new Blob([response.data], { type: "application/pdf" });
//     console.log("📄 Blob created:", blob);

//     const fileName = `credit-report-${unique_key}.pdf`;
//     saveAs(blob, fileName);

//     console.log(`💾 PDF saved as: ${fileName}`);
//   } catch (err) {
//     console.error("❌ Export failed:", err);
//     if (err.response) {
//       console.error("🛑 Server responded with error:", err.response.status, err.response.data);
//     } else if (err.request) {
//       console.error("📭 No response received:", err.request);
//     } else {
//       console.error("⚠️ Error setting up request:", err.message);
//     }
//     alert("Could not export PDF");
//   }
// };
const pdfContent = ref(null)
const exportPDF = async () => {
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
  if (idType.value === 'business' && businessData) {
    const businessName = businessData.value.BusinessName || 'Business'
    filename = `${businessName}_${dateString}.pdf`
  } else {
    const personalData =
      personal.value && Object.keys(personal.value).length > 0
        ? personal.value
        : creditPersonal.value
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
    jsPDF: {
      unit: 'in',
      format: 'letter',
      orientation: 'landscape' // Changed from 'portrait' to 'landscape'
    }
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
</script>

<template>
  <MainLayout>
    <div class="mt-4 p-2 mx-auto space-y-6">
      <div v-if="loading" class="flex flex-col items-center justify-center min-h-[200px]">
        <v-progress-circular indeterminate color="#1f5aa3" size="80" width="8" />
        <span class="mt-2 text-gray-600 text-sm">Loading credit report</span>
      </div>

      <div
        v-else-if="hitRecord"
        class="relative flex flex-col items-center justify-center h-screen text-center"
      >
        <RouterLink to="/credit-search" class="absolute top-4 left-4">
          <button class="m-2 flex items-center text-black text-lg font-normal">
            <i class="fas fa-circle-arrow-left mr-2 text-xl" style="color: #2563eb"></i> Back
          </button>
        </RouterLink>

        <!-- Centered Content -->
        <p class="font-semibold">NO HIT RECORD</p>
      </div>

      <!-- Tab Content Transition -->
      <div v-else>
        <RouterLink to="/credit-search">
          <button class="m-2 flex items-center text-black text-lg font-normal">
            <i class="fas fa-circle-arrow-left mr-2 text-xl" style="color: #2563eb"></i> Back
          </button>
        </RouterLink>

        <div class="mt- 4 flex justify-between items-center p-2">
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
              @click="exportPDF"
              no-uppercase
              size="small"
              class="normal-case p-4 bg-blue-600 hover:bg-blue-700 text-white text-none custom-btn"
            >
              <i class="fas fa-download mr-2"></i>
              Export PDF
            </v-btn>
            <!-- <v-btn
              @click="reportRef?.exportPDF()"
              no-uppercase
              size="small"
              class="normal-case p-4 bg-blue-600 hover:bg-blue-700 text-white text-none custom-btn"
            >
              <i class="fas fa-download mr-2"></i>
              Export PDF
            </v-btn> -->
          </div>
        </div>
        <transition name="fade" mode="out-in">
          <div v-if="!loading" :key="activeTab" class="space-y-6">
            <template v-if="activeTab === 'first_central'">
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
                      <p class="font-bold text-gray-900">
                        {{ displayValue(businessData.BusinessName) }}
                      </p>
                    </div>
                    <div>
                      <p class="mb-1">Date of Incorporation</p>
                      <p class="font-bold text-gray-900">
                        {{ displayValue(businessData.DateOfIncorporation) }}
                      </p>
                    </div>
                    <div>
                      <p class="mb-1">Business Address</p>
                      <p class="font-bold text-gray-900">
                        {{ displayValue(businessData.CommercialAddress1) }},
                        {{ displayValue(businessData.CommercialAddress2) }},
                        {{ displayValue(businessData.CommercialAddress4) }}
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
                          <th class="">First Name</th>
                          <th class="">Other Names</th>
                          <th class="">Surname</th>
                          <th class="">Identification Number</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(director, index) in directors" :key="index">
                          <td class="pt-4 font-bold text-gray-900">
                            {{ displayValue(director.firstName) }}
                          </td>
                          <td class="font-bold text-gray-900">
                            {{ displayValue(director.othernames) }}
                          </td>
                          <td class="font-bold text-gray-900">
                            {{ displayValue(director.surname) }}
                          </td>
                          <td class="font-bold text-gray-900">
                            {{ displayValue(director.Identificationnumber) }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div v-else class="text-sm text-gray-500 italic">
                    No director information available.
                  </div>
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
                      {{ formatNaira(summary.TotalMonthlyInstalment || 0.0) }}
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
                      {{ formatNaira(summary.TotalOutstandingdebt) || 0.0 }}
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
                  <v-data-table
                    :headers="loanHeaders"
                    :items="creditAgreementSummary"
                    item-value="uid"
                    :expanded.sync="expanded"
                    class="elevation-1"
                    fixed-header
                    height="400"
                    hide-default-footer
                  >
                    <!-- Default item row -->
                    <template #item.status="{ item }">
                      <v-chip
                        :color="getStatusColor(item.status)"
                        variant="tonal"
                        size="small"
                        class="text-white"
                      >
                        {{ item.status?.trim() || 'Unknown' }}
                      </v-chip>
                    </template>
                    <template #item.action="{ item }">
                      <v-btn size="small" color="primary" @click="toggleRow(item.uid)">
                        {{ isExpanded(item.uid) ? 'Hide' : 'View' }}
                      </v-btn>
                    </template>

                    <!-- Expanded content -->
                    <template #expanded-row="{ item }">
                      <td :colspan="loanHeaders.length" class="px-4 py-2 bg-gray-90 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p>
                            Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                          </p>
                          <p>
                            Loan Amount: <strong>{{ item.amount || 0 }}</strong>
                          </p>
                          <p>
                            Current Balance: <strong>{{ item.balance || 0 }}</strong>
                          </p>
                          <p>
                            Amount Overdue: <strong>{{ item.overdue || 0 }}</strong>
                          </p>
                          <p>
                            Instalment Amount: <strong>{{ item.instalment || 0 }}</strong>
                          </p>
                          <p>
                            Loan Duration: <strong>{{ displayValue(item.duration) }}</strong>
                          </p>
                          <p>
                            Repayment Frequency:
                            <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                          </p>
                          <p>
                            Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                          </p>
                          <p>
                            Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                          </p>

                          <!-- Performance Status -->
                          <div>
                            Performance Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.performanceStatus)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.performanceStatus)"
                              ></span>
                              <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                            </div>
                          </div>

                          <!-- Account Status -->
                          <div>
                            Account Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.status)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.status)"
                              ></span>
                              <strong>{{ item.status || 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </td>
                    </template>
                  </v-data-table>
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
                        <tr
                          v-for="(enquiry, index) in enquiryHistory"
                          :key="index"
                          class="border-b"
                        >
                          <td class="p-2">{{ enquiry.lender }}</td>
                          <td class="p-2">{{ enquiry.date }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div v-else class="text-sm text-gray-500 italic">
                    No enquiry history available.
                  </div>
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
                  <div v-else class="text-sm text-gray-500 italic">
                    No employment history available.
                  </div>
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
            </template>

            <template v-else-if="activeTab === 'credit_registry'">
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

                  <div v-else class="text-sm text-gray-500 italic">
                    No director information available.
                  </div>
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

              <!-- Performing Accounts Table -->
              <div class="bg-white p-6 rounded">
                <h2 class="text-md font-semibold mb-4">Performing Accounts</h2>

                <div v-if="loanAccounts && loanAccounts.length > 0">
                  <v-data-table
                    :headers="loanHeaders"
                    :items="loanAccounts"
                    item-value="uid"
                    :expanded.sync="expanded"
                    show-expand
                    fixed-header
                    height="300"
                    hide-default-footer
                    class="elevation-1"
                  >
                    <!-- Status badge -->
                    <template #item.status="{ item }">
                      <v-chip
                        :color="item.status === 'Closed' ? 'green' : 'red'"
                        variant="tonal"
                        size="small"
                        class="text-white"
                      >
                        {{ item.status }}
                      </v-chip>
                    </template>

                    <!-- Action button -->
                    <template #item.action="{ item }">
                      <v-btn size="small" color="primary" @click="toggleRow(item.uid)">
                        {{ isExpanded(item.uid) ? 'Hide' : 'View' }}
                      </v-btn>
                    </template>

                    <!-- Expanded row content -->
                    <template #expanded-row="{ item }">
                      <td :colspan="loanHeaders.length" class="px-4 py-2 bg-gray-90 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p>
                            Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                          </p>
                          <p>
                            Loan Amount: <strong>{{ formatCurrency(item.amount || 0) }}</strong>
                          </p>
                          <p>
                            Current Balance: <strong>{{ formatNaira(item.balance) || 0 }}</strong>
                          </p>
                          <p>
                            Amount Overdue: <strong>{{ item.overdue || 0 }}</strong>
                          </p>
                          <p>
                            Instalment Amount: <strong>{{ item.instalment || 0 }}</strong>
                          </p>
                          <p>
                            Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                          </p>
                          <p>
                            Repayment Frequency:
                            <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                          </p>
                          <p>
                            Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                          </p>
                          <p>
                            Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                          </p>

                          <!-- Performance Status -->
                          <div>
                            Performance Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.performanceStatus)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.performanceStatus)"
                              ></span>
                              <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                            </div>
                          </div>

                          <!-- Account Status -->
                          <div>
                            Account Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.status)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.status)"
                              ></span>
                              <strong>{{ item.status || 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </td>
                    </template>
                  </v-data-table>
                </div>

                <div v-else class="text-sm text-gray-500 italic">No loan accounts available.</div>
              </div>

              <!-- Delinquents Accounts -->
              <div class="bg-white p-6 rounded-md shadow">
                <h2 class="text-md font-semibold mb-4">Deliquents Accounts</h2>
                <div v-if="delinquentAccounts && delinquentAccounts.length > 0">
                  <v-data-table
                    :headers="delinquentHeaders"
                    :items="delinquentAccounts"
                    item-value="uid"
                    :expanded.sync="expanded"
                    show-expand
                    fixed-header
                    height="300"
                    hide-default-footer
                    class="elevation-1"
                  >
                    <!-- Status badge -->
                    <template #item.status="{ item }">
                      <v-chip
                        :color="item.status === 'Closed' ? 'green' : 'red'"
                        variant="tonal"
                        size="small"
                        class="text-white"
                      >
                        {{ item.status }}
                      </v-chip>
                    </template>

                    <!-- Action button -->
                    <template #item.action="{ item }">
                      <v-btn size="small" color="primary" @click="toggleRow(item.uid)">
                        {{ isExpanded(item.uid) ? 'Hide' : 'View' }}
                      </v-btn>
                    </template>

                    <!-- Expanded content -->
                    <template #expanded-row="{ item }">
                      <td :colspan="delinquentHeaders.length" class="px-4 py-2 bg-gray-90 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p>
                            Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                          </p>
                          <p>
                            Loan Amount:
                            <strong>₦{{ item.amount?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Current Balance:
                            <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Amount Overdue:
                            <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Instalment Amount:
                            <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                          </p>
                          <p>
                            Repayment Frequency:
                            <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                          </p>
                          <p>
                            Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                          </p>
                          <p>
                            Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                          </p>

                          <!-- Performance Status -->
                          <div>
                            Performance Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.performanceStatus)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.performanceStatus)"
                              ></span>
                              <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                            </div>
                          </div>

                          <!-- Account Status -->
                          <div>
                            Account Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.status)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.status)"
                              ></span>
                              <strong>{{ item.status || 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </td>
                    </template>
                  </v-data-table>
                </div>

                <div v-else class="text-sm text-gray-500 italic">
                  No delinquent accounts available.
                </div>
              </div>

              <!-- Closed Accounts -->
              <div class="bg-white p-6 rounded-md shadow">
                <h2 class="text-md font-semibold mb-4">Closed Accounts</h2>
                <div v-if="closedAccounts && closedAccounts.length > 0">
                  <v-data-table
                    :headers="closedHeaders"
                    :items="closedAccounts"
                    item-value="uid"
                    :expanded.sync="expanded"
                    show-expand
                    fixed-header
                    height="300"
                    hide-default-footer
                    class="elevation-1"
                  >
                    <!-- Status badge -->
                    <template #item.status="{ item }">
                      <v-chip
                        :color="item.status === 'Closed' ? 'green' : 'red'"
                        variant="tonal"
                        size="small"
                        class="text-white"
                      >
                        {{ item.status }}
                      </v-chip>
                    </template>

                    <!-- Action button -->
                    <template #item.action="{ item }">
                      <v-btn size="small" color="primary" @click="toggleRow(item.uid)">
                        {{ isExpanded(item.uid) ? 'Hide' : 'View' }}
                      </v-btn>
                    </template>

                    <!-- Expanded content -->
                    <template #expanded-row="{ item }">
                      <td :colspan="closedHeaders.length" class="px-4 py-2 bg-gray-90 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p>
                            Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                          </p>
                          <p>
                            Loan Amount: <strong>{{ formatCurrency(item.amount || '0') }}</strong>
                          </p>
                          <p>
                            Current Balance:
                            <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Amount Overdue:
                            <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Instalment Amount:
                            <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                          </p>
                          <p>
                            Repayment Frequency:
                            <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                          </p>
                          <p>
                            Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                          </p>
                          <p>
                            Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                          </p>

                          <!-- Performance Status -->
                          <div>
                            Performance Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.performanceStatus)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.performanceStatus)"
                              ></span>
                              <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                            </div>
                          </div>

                          <!-- Account Status -->
                          <div>
                            Account Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.status)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.status)"
                              ></span>
                              <strong>{{ item.status || 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </td>
                    </template>
                  </v-data-table>
                </div>

                <div v-else class="text-sm text-gray-500 italic">No Closed accounts available.</div>
              </div>

              <!-- Written Off Accounts -->
              <div class="bg-white p-6 rounded mb-6">
                <h2 class="text-md font-semibold mb-4">Written Off Accounts</h2>
                <div v-if="writtenOffAccounts && writtenOffAccounts.length > 0">
                  <v-data-table
                    :headers="writtenOffHeaders"
                    :items="writtenOffAccounts"
                    item-value="uid"
                    :expanded.sync="expanded"
                    show-expand
                    fixed-header
                    height="300"
                    hide-default-footer
                    class="elevation-1"
                  >
                    <!-- Status badge -->
                    <template #item.status="{ item }">
                      <v-chip
                        :color="item.status === 'Closed' ? 'green' : 'red'"
                        variant="tonal"
                        size="small"
                        class="text-white"
                      >
                        {{ item.status }}
                      </v-chip>
                    </template>

                    <!-- Action button -->
                    <template #item.action="{ item }">
                      <v-btn size="small" color="primary" @click="toggleRow(item.uid)">
                        {{ isExpanded(item.uid) ? 'Hide' : 'View' }}
                      </v-btn>
                    </template>

                    <!-- Expanded content -->
                    <template #expanded-row="{ item }">
                      <td :colspan="writtenOffHeaders.length" class="px-4 py-2 bg-gray-90 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p>
                            Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                          </p>
                          <p>
                            Loan Amount:
                            <strong>₦{{ item.amount?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Current Balance:
                            <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Amount Overdue:
                            <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Instalment Amount:
                            <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                          </p>
                          <p>
                            Repayment Frequency:
                            <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                          </p>
                          <p>
                            Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                          </p>
                          <p>
                            Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                          </p>

                          <!-- Performance Status -->
                          <div>
                            Performance Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.performanceStatus)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.performanceStatus)"
                              ></span>
                              <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                            </div>
                          </div>

                          <!-- Account Status -->
                          <div>
                            Account Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.status)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.status)"
                              ></span>
                              <strong>{{ item.status || 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </td>
                    </template>
                  </v-data-table>
                </div>
                <div v-else class="text-sm text-gray-500 italic">
                  No written-off accounts available.
                </div>
              </div>

              <!-- Unknown Account Status -->
              <div class="bg-white p-6 rounded mb-6">
                <h2 class="text-md font-semibold mb-4">Unknown Accounts Status</h2>
                <div v-if="unknownAccounts && unknownAccounts.length > 0">
                  <v-data-table
                    :headers="unknownHeaders"
                    :items="unknownAccounts"
                    item-value="uid"
                    :expanded.sync="expanded"
                    show-expand
                    fixed-header
                    height="300"
                    hide-default-footer
                    class="elevation-1"
                  >
                    <!-- Status badge -->
                    <template #item.status="{ item }">
                      <v-chip
                        :color="item.status === 'Closed' ? 'green' : 'red'"
                        variant="tonal"
                        size="small"
                        class="text-white"
                      >
                        {{ item.status }}
                      </v-chip>
                    </template>

                    <!-- Action button -->
                    <template #item.action="{ item }">
                      <v-btn size="small" color="primary" @click="toggleRow(item.uid)">
                        {{ isExpanded(item.uid) ? 'Hide' : 'View' }}
                      </v-btn>
                    </template>

                    <!-- Expanded content -->
                    <template #expanded-row="{ item }">
                      <td :colspan="unknownHeaders.length" class="px-4 py-2 bg-gray-90 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                          <p>
                            Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                          </p>
                          <p>
                            Loan Amount:
                            <strong>₦{{ item.amount?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Current Balance:
                            <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Amount Overdue:
                            <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Instalment Amount:
                            <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                          </p>
                          <p>
                            Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                          </p>
                          <p>
                            Repayment Frequency:
                            <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                          </p>
                          <p>
                            Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                          </p>
                          <p>
                            Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                          </p>

                          <!-- Performance Status -->
                          <div>
                            Performance Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.performanceStatus)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.performanceStatus)"
                              ></span>
                              <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                            </div>
                          </div>

                          <!-- Account Status -->
                          <div>
                            Account Status <br />
                            <div
                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                              :class="getChipStyle(item.status)"
                            >
                              <span
                                class="h-1.5 w-1.5 rounded-full mr-1.5"
                                :class="getDotColor(item.status)"
                              ></span>
                              <strong>{{ item.status || 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </td>
                    </template>
                  </v-data-table>
                </div>
                <div v-else class="text-sm text-gray-500 italic">
                  No unknown accounts available.
                </div>
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
            </template>
          </div>
        </transition>
      </div>
    </div>

    <div class="hidden">
      <div ref="pdfContent" class="pdf-content">
        <div class="mt-4 p-2 mx-auto space-y-6">
          <!-- No hit record -->
          <div
            v-if="hitRecord"
            class="relative flex flex-col items-center justify-center h-screen text-center"
          >
            <!-- Centered Content -->
            <p class="font-semibold">NO HIT RECORD</p>
          </div>

          <!-- report -->
          <div v-else>
            <div class="space-y-6">
              <!-- FCBC -->
              <div class="flex ml-8">
                <img src="../assets/first central logo.png" alt="" />
              </div>

              <div class="p-2">
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
                    <div v-else class="text-gray-500 text-sm italic">
                      No personal data available.
                    </div>
                  </div>
                </div>

                <div v-else>
                  <!-- BUSINESS INFORMATION SECTION -->
                  <div v-if="idType === 'business'" class="bg-white p-6 rounded space-y-4">
                    <h2 class="text-md font-semibold">Business Information</h2>
                    <div
                      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 text-sm text-gray-600"
                    >
                      <div>
                        <p class="mb-1">Business Name</p>
                        <p class="font-bold text-gray-900">
                          {{ displayValue(businessData.BusinessName) }}
                        </p>
                      </div>
                      <div>
                        <p class="mb-1">Date of Incorporation</p>
                        <p class="font-bold text-gray-900">
                          {{ displayValue(businessData.DateOfIncorporation) }}
                        </p>
                      </div>
                      <div>
                        <p class="mb-1">Business Address</p>
                        <p class="font-bold text-gray-900">
                          {{ displayValue(businessData.CommercialAddress1) }},
                          {{ displayValue(businessData.CommercialAddress2) }},
                          {{ displayValue(businessData.CommercialAddress4) }}
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
                            <th class="">First Name</th>
                            <th class="">Other Names</th>
                            <th class="">Surname</th>
                            <th class="">Identification Number</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(director, index) in directors" :key="index">
                            <td class="pt-4 font-bold text-gray-900">
                              {{ displayValue(director.firstName) }}
                            </td>
                            <td class="font-bold text-gray-900">
                              {{ displayValue(director.othernames) }}
                            </td>
                            <td class="font-bold text-gray-900">
                              {{ displayValue(director.surname) }}
                            </td>
                            <td class="font-bold text-gray-900">
                              {{ displayValue(director.Identificationnumber) }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div v-else class="text-sm text-gray-500 italic">
                      No director information available.
                    </div>
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
                        {{ formatNaira(summary.TotalMonthlyInstalment || 0.0) }}
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
                        {{ formatNaira(summary.TotalOutstandingdebt) || 0.0 }}
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
                <div class="p-6">
                  <h2 class="text-md font-semibold mb-4">Loan Accounts</h2>

                  <div
                    v-if="creditAgreementSummary && creditAgreementSummary.length > 0"
                    class="space-y-6"
                  >
                    <div
                      v-for="(item, index) in creditAgreementSummary"
                      :key="item.uid"
                      class="border rounded-lg p-6 bg-blue-50 shadow-sm"
                    >
                      <h3 class="font-semibold mb-4">{{ index + 1 }}. {{ item.lender }}</h3>

                      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <p>
                          Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                        </p>
                        <p>
                          Loan Amount: <strong>{{ item.amount || 0 }}</strong>
                        </p>
                        <p>
                          Current Balance: <strong>{{ item.balance || 0 }}</strong>
                        </p>
                        <p>
                          Amount Overdue: <strong>{{ item.overdue || 0 }}</strong>
                        </p>
                        <p>
                          Instalment Amount: <strong>{{ item.instalment || 0 }}</strong>
                        </p>
                        <p>
                          Loan Duration: <strong>{{ displayValue(item.duration) }}</strong>
                        </p>
                        <p>
                          Repayment Frequency:
                          <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                        </p>
                        <p>
                          Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                        </p>
                        <p>
                          Last Updated Date: <strong>{{ displayValue(item.lastUpdated) }}</strong>
                        </p>
                        <p>
                          Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                        </p>

                        <!-- Performance Status -->
                        <div>
                          Performance Status <br />
                          <div
                            class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium mt-1"
                          >
                            <span
                              class="h-1.5 w-1.5 rounded-full mr-1.5"
                              :class="getDotColor(item.performanceStatus)"
                            ></span>
                            <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                          </div>
                        </div>

                        <!-- Account Status -->
                        <div>
                          Account Status <br />
                          <div
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                            :class="getChipStyle(item.status)"
                          >
                            <span
                              class="h-1.5 w-1.5 rounded-full mr-1.5"
                              :class="getDotColor(item.status)"
                            ></span>
                            <strong>{{ item.status || 'N/A' }}</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-else class="text-sm text-gray-500 italic">No loan accounts available.</div>
                </div>

                <!-- Enquiry & Employment History -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Enquiry History -->
                  <div class="p-4">
                    <h2 class="font-semibold text-md mb-4">Enquiry History</h2>
                    <div v-if="enquiryHistory && enquiryHistory.length > 0">
                      <table class="w-full text-sm text-left">
                        <thead class="font-semibold bg-blue-50">
                          <tr>
                            <th class="p-2">Lender's Name</th>
                            <th class="p-2">Date Requested</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(enquiry, index) in enquiryHistory"
                            :key="index"
                            class="border-b"
                          >
                            <td class="p-2">{{ enquiry.lender }}</td>
                            <td class="p-2">{{ enquiry.date }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div v-else class="text-sm text-gray-500 italic">
                      No enquiry history available.
                    </div>
                  </div>

                  <!-- Employment History -->
                  <div class="p-4">
                    <h2 class="font-semibold text-md mb-4">Employment History</h2>

                    <div v-if="employmentHistory && employmentHistory.length > 0" class="">
                      <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-blue-50">
                          <tr>
                            <th scope="col" class="px-4 py-2 text-left font-medium text-gray-600">
                              Employer Name
                            </th>
                            <th scope="col" class="px-4 py-2 text-left font-medium text-gray-600">
                              Date Updated
                            </th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                          <tr
                            v-for="(item, index) in employmentHistory"
                            :key="index"
                            class="hover:bg-gray-50"
                          >
                            <td class="px-4 py-2 text-gray-700">{{ item.employerName }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ item.date }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div v-else class="text-sm text-gray-500 italic">
                      No employment history available.
                    </div>
                  </div>
                </div>

                <!-- Address History -->
                <div class="bg-white p-4">
                  <h2 class="font-semibold text-md mb-4">Address History</h2>
                  <div v-if="addressHistory && addressHistory.length > 0">
                    <div class="">
                      <table class="min-w-full text-sm text-left">
                        <thead class="bg-blue-50">
                          <tr>
                            <th class="p-3 font-semibold">Address</th>
                            <th class="p-3 font-semibold w-40">Date Updated</th>
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
                  <div v-else class="text-sm text-gray-500 italic">
                    No address history available.
                  </div>
                </div>
              </div>

              <!-- Credit registry -->
              <div class="flex ml-4">
                <img src="../assets/credit registry logo.png" alt="" />
              </div>
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

                  <div v-else class="text-sm text-gray-500 italic">
                    No director information available.
                  </div>
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

              <!-- Performing Accounts Table -->
              <div class="p-6">
                <h2 class="text-md font-semibold mb-4">Performing Accounts</h2>

                <div v-if="loanAccounts && loanAccounts.length > 0" class="space-y-6">
                  <div
                    v-for="(item, index) in loanAccounts"
                    :key="item.uid"
                    class="border rounded-lg p-6 bg-blue-50 shadow-sm"
                  >
                    <h3 class="font-semibold mb-4">{{ index + 1 }}. {{ item.lender }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                      <p>
                        Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                      </p>
                      <p>
                        Loan Amount: <strong>{{ formatCurrency(item.amount || 0) }}</strong>
                      </p>
                      <p>
                        Current Balance: <strong>{{ formatNaira(item.balance) || 0 }}</strong>
                      </p>
                      <p>
                        Amount Overdue: <strong>{{ item.overdue || 0 }}</strong>
                      </p>
                      <p>
                        Instalment Amount: <strong>{{ item.instalment || 0 }}</strong>
                      </p>
                      <p>
                        Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                      </p>
                      <p>
                        Repayment Frequency:
                        <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                      </p>
                      <p>
                        Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                      </p>
                      <p>
                        Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                      </p>

                      <!-- Performance Status -->
                      <div>
                        Performance Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.performanceStatus)"
                          ></span>
                          <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                        </div>
                      </div>

                      <!-- Account Status -->
                      <div>
                        Account Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.status)"
                          ></span>
                          <strong>{{ item.status || 'N/A' }}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-else class="text-sm text-gray-500 italic">No loan accounts available.</div>
              </div>

              <!-- Delinquents Accounts -->
              <div class="p-6">
                <h2 class="text-md font-semibold mb-4">Delinquent Accounts</h2>

                <div v-if="delinquentAccounts && delinquentAccounts.length > 0" class="space-y-6">
                  <div
                    v-for="(item, index) in delinquentAccounts"
                    :key="item.uid"
                    class="border rounded-lg p-6 bg-blue-50 shadow-sm"
                  >
                    <h3 class="font-semibold mb-4">{{ index + 1 }}. {{ item.lender }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                      <p>
                        Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                      </p>
                      <p>
                        Loan Amount: <strong>₦{{ item.amount?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Current Balance:
                        <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Amount Overdue:
                        <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Instalment Amount:
                        <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                      </p>
                      <p>
                        Repayment Frequency:
                        <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                      </p>
                      <p>
                        Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                      </p>
                      <p>
                        Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                      </p>

                      <!-- Performance Status -->
                      <div>
                        Performance Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.performanceStatus)"
                          ></span>
                          <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                        </div>
                      </div>

                      <!-- Account Status -->
                      <div>
                        Account Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.status)"
                          ></span>
                          <strong>{{ item.status || 'N/A' }}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-else class="text-sm text-gray-500 italic">
                  No delinquent accounts available.
                </div>
              </div>

              <!-- Closed Accounts -->
              <div class="p-6">
                <h2 class="text-md font-semibold mb-4">Closed Accounts</h2>

                <div v-if="closedAccounts && closedAccounts.length > 0" class="space-y-6">
                  <div
                    v-for="(item, index) in closedAccounts"
                    :key="item.uid"
                    class="border rounded-lg p-6 bg-blue-50 shadow-sm"
                  >
                    <h3 class="font-semibold mb-4">{{ index + 1 }}. {{ item.lender }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                      <p>
                        Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                      </p>
                      <p>
                        Loan Amount: <strong>{{ formatCurrency(item.amount || '0') }}</strong>
                      </p>
                      <p>
                        Current Balance:
                        <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Amount Overdue:
                        <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Instalment Amount:
                        <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                      </p>
                      <p>
                        Repayment Frequency:
                        <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                      </p>
                      <p>
                        Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                      </p>
                      <p>
                        Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                      </p>

                      <!-- Performance Status -->
                      <div>
                        Performance Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.performanceStatus)"
                          ></span>
                          <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                        </div>
                      </div>

                      <!-- Account Status -->
                      <div>
                        Account Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.status)"
                          ></span>
                          <strong>{{ item.status || 'N/A' }}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-else class="text-sm text-gray-500 italic">No Closed accounts available.</div>
              </div>

              <!-- Written Off Accounts -->
              <div class="p-6">
                <h2 class="text-md font-semibold mb-4">Written Off Accounts</h2>

                <div v-if="writtenOffAccounts && writtenOffAccounts.length > 0" class="space-y-6">
                  <div
                    v-for="(item, index) in writtenOffAccounts"
                    :key="item.uid"
                    class="border rounded-lg p-6 bg-blue-50 shadow-sm"
                  >
                    <h3 class="font-semibold mb-4">{{ index + 1 }}. {{ item.lender }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                      <p>
                        Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                      </p>
                      <p>
                        Loan Amount: <strong>₦{{ item.amount?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Current Balance:
                        <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Amount Overdue:
                        <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Instalment Amount:
                        <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                      </p>
                      <p>
                        Repayment Frequency:
                        <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                      </p>
                      <p>
                        Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                      </p>
                      <p>
                        Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                      </p>

                      <!-- Performance Status -->
                      <div>
                        Performance Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.performanceStatus)"
                          ></span>
                          <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                        </div>
                      </div>

                      <!-- Account Status -->
                      <div>
                        Account Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.status)"
                          ></span>
                          <strong>{{ item.status || 'N/A' }}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-else class="text-sm text-gray-500 italic">
                  No written-off accounts available.
                </div>
              </div>

              <!-- Unknown Accounts -->
              <div class="p-6">
                <h2 class="text-md font-semibold mb-4">Unknown Accounts Status</h2>

                <div v-if="unknownAccounts && unknownAccounts.length > 0" class="space-y-6">
                  <div
                    v-for="(item, index) in unknownAccounts"
                    :key="item.uid"
                    class="border rounded-lg p-6 bg-blue-50 shadow-sm"
                  >
                    <h3 class="font-semibold mb-4">{{ index + 1 }}. {{ item.lender }}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                      <p>
                        Account number: <strong>{{ displayValue(item.accountNo) }}</strong>
                      </p>
                      <p>
                        Loan Amount: <strong>₦{{ item.amount?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Current Balance:
                        <strong>₦{{ item.balance?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Amount Overdue:
                        <strong>₦{{ item.overdue?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Instalment Amount:
                        <strong>₦{{ item.instalment?.toLocaleString() || '0' }}</strong>
                      </p>
                      <p>
                        Loan Duration: <strong>{{ displayValue(item.duration) }} months</strong>
                      </p>
                      <p>
                        Repayment Frequency:
                        <strong>{{ displayValue(item.repaymentFrequency) }}</strong>
                      </p>
                      <p>
                        Date Account Opened: <strong>{{ displayValue(item.date) }}</strong>
                      </p>
                      <p>
                        Closed Date: <strong>{{ displayValue(item.closedDate) }}</strong>
                      </p>

                      <!-- Performance Status -->
                      <div>
                        Performance Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.performanceStatus)"
                          ></span>
                          <strong>{{ item.performanceStatus || 'N/A' }}</strong>
                        </div>
                      </div>

                      <!-- Account Status -->
                      <div>
                        Account Status <br />
                        <div
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                        >
                          <span
                            class="h-1.5 w-1.5 rounded-full mr-1.5"
                            :class="getDotColor(item.status)"
                          ></span>
                          <strong>{{ item.status || 'N/A' }}</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-else class="text-sm text-gray-500 italic">
                  No unknown accounts available.
                </div>
              </div>

              <!-- Inquiry History -->
              <div class="bg-white p-6 rounded mb-6">
                <h2 class="text-md font-semibold mb-4">Inquiry History</h2>
                <div v-if="inquiryHistory && inquiryHistory.length > 0">
                  <table class="min-w-full text-left">
                    <thead>
                      <tr class="bg-blue-50 text-sm">
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
            </div>
          </div>
        </div>
      </div>
      <!-- <CreditReportExport
        :id-type="idType"
        :hit-record="hitRecord"
        :business-data="businessData"
        :directors="directors"
        ref="reportRef"
        :personal="personal"
        :summary="summary"
        :credit-agreement-summary="creditAgreementSummary"
        :enquiry-history="enquiryHistory"
        :employment-history="employmentHistory"
        :address-history="addressHistory"
        :loan-accounts="loanAccounts"
        :delinquent-accounts="delinquentAccounts"
        :closed-accounts="closedAccounts"
        :derogatory-accounts="derogatoryAccounts"
        :written-off-accounts="writtenOffAccounts"
        :unknown-accounts="unknownAccounts"
        :inquiry-history="inquiryHistory"
        :credit-personal="creditPersonal"
        :credit-registry-business-name="creditRegistrybusinessName"
        :credit-directors="creditDirectors"
        :credit-registry-total-open="creditRegistryTotalOpen"
        :credit-registry-total-closed="creditRegistryTotalClosed"
        :credit-registry-total-written-off="creditRegistryTotalWrittenOff"
      /> -->
    </div>
  </MainLayout>
</template>

<style scoped>
.pdf-content {
  width: 100% !important;
  height: auto !important;
  overflow: visible !important;
  position: static !important;
}

.pdf-content * {
  box-sizing: border-box !important;
  page-break-inside: avoid !important;
}
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
.v-btn {
  text-transform: none;
}
</style>
