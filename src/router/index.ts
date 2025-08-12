import { createRouter, createWebHistory } from 'vue-router'
import VerifiedEmail from '@/views/VerifiiedEmail.vue'
import DashboardView from '@/views/dashboard/DashboardView.vue'
import Login from '@/views/Login.vue'
import Wallet from '@/views/Wallet.vue'
import Settings from '@/views/Settings.vue'
import Analysis from '@/views/Analysis.vue'
import ResetPassword from '@/views/ResetPassword.vue'
import PasswordReset from '@/views/PasswordReset.vue'
import CreditSearch from '@/views/CreditSearch.vue'
import CreditReport from '@/views/CreditReport.vue'
import ApplicationsView from '@/views/ApplicationsView.vue'
import SiteDown from '@/views/SiteDown.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/verified',
      name: 'verified',
      component: VerifiedEmail
    },

    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView
    },

    {
      path: '/wallet',
      name: 'wallet',
      component: Wallet
    },
    {
      path: '/settings',
      name: 'settings',
      component: Settings
    },
    {
      path: '/analysis',
      name: 'analysis',
      component: Analysis
    },
    {
      path: '/resetpassword',
      name: 'resetpassword',
      component: ResetPassword
    },
    {
      path: '/passwordreset',
      name: 'passwordreset',
      component: PasswordReset
    },
    {
      path: '/credit-search',
      name: 'credit-search',
      component: CreditSearch
    },
    {
      path: '/applications',
      name: 'applications',
      component: ApplicationsView
    },
    {
      path: '/sitedown',
      name: 'sitedown',
      component: SiteDown
    },
    {
      path: '/credit-report/:unique_key',
      name: 'CreditReport',
      component: () => import('@/views/CreditReport.vue'),
      props: true
    },
    {
      path: '/acceptinvite',
      name: 'acceptinvite',
      component: () => import('@/views/AcceptInviteSuccess.vue')
    },
    {
      path: '/analysis/:id/:status?',
      name: 'StatementAnalysis',
      component: () => import('@/views/StatementAnalysis.vue'),
      props: true
    }
  ]
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('data')
  const publicPages = ['/', '/signup', '/resetpassword', '/passwordreset', '/acceptinvite']

  if (!isAuthenticated && !publicPages.includes(to.path)) {
    return next('/')
  }

  next()
})
export default router
