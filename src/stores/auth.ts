import { defineStore } from 'pinia'
import axios from 'axios'

interface AuthState {
  id: string | null
  token: string | null
  verification_status: string | null
  user: any | null,
  wallet: any | null,

}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    id: null,
    token: null,
    verification_status: null,
    user: null,
    wallet: null
  }),
  actions: {
    setAuthData(data: any) {
      console.log(localStorage.getItem('token'))

      this.id = data.user?.id || null
      this.token = data.token || null
      this.verification_status = data.user?.verification_status || null
      this.user = data.user || {}
      this.wallet = data.wallet || {}

      if (data.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
        axios.defaults.headers.common['Accept'] = 'application/json'
      }
    },

    init() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        axios.defaults.headers.common['Accept'] = 'application/json'
      }
    },

    clearAuthData() {
      this.id = null
      this.token = null
      this.verification_status = null
      this.user = null
      this.wallet = null

      // Clear axios default auth headers
      delete axios.defaults.headers.common['Authorization']

      // Optional: Clear anything manually added to localStorage (if any)
      localStorage.removeItem('token')
      localStorage.removeItem('data') // already done in useAutoLogout, but safe to repeat
    }
  },

  persist: true
})
