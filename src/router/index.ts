import { createRouter, createWebHistory } from 'vue-router'
import VerifiedEmail from '@/views/VerifiiedEmail.vue'
import DashboardView from '@/views/dashboard/DashboardView.vue'
import Login from '@/views/Login.vue'
import SignUp from '@/views/SignUp.vue'
import Wallet from '@/views/Wallet.vue'
import Settings from '@/views/Settings.vue'
import Analysis from '@/views/Analysis.vue'
import ResetPassword from '@/views/ResetPassword.vue'
import PasswordReset from '@/views/PasswordReset.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/signup',
      name: 'signup',
      component: SignUp
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
    }
  ,
    {
      path: '/credit-report',
      name: 'credit-report',
      component: CreditReport
    }
    {
      path: '/statement-analysis/:id',
      name: 'StatementAnalysis',
      component: () => import('@/views/StatementAnalysis.vue'),
      props: true
    },
    {
      path: '/acceptinvite',
      name: 'acceptinvite',
      component: () => import('@/views/AcceptInviteSuccess.vue')
    }
  ]
})

export default router
