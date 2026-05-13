import { create } from 'zustand'
import api from '@/lib/axios'

export const useAuthStore = create((set, get) => ({
  token: null,
  user: null,
  isLoading: false,
  isRestored: false,

  isAuthenticated: () => !!get().token && !!get().user,

  hasRole: (role) => {
    const user = get().user
    if (!user) return false
    const roles = Array.isArray(role) ? role : [role]
    return roles.some((r) => user.roles.includes(r))
  },

  login: async (email, password) => {
    set({ isLoading: true })
    try {
      const { data } = await api.post('/login', { email, password })
      const { token, user } = data.data
      sessionStorage.setItem('auth_token', token)
      sessionStorage.setItem('auth_user', JSON.stringify(user))
      set({ token, user, isLoading: false })
    } catch (err) {
      set({ isLoading: false })
      throw err
    }
  },

  logout: async () => {
    try {
      await api.post('/logout')
    } catch {
      // ignore
    } finally {
      sessionStorage.removeItem('auth_token')
      sessionStorage.removeItem('auth_user')
      set({ token: null, user: null })
    }
  },

  restoreSession: () => {
    const token = sessionStorage.getItem('auth_token')
    const raw   = sessionStorage.getItem('auth_user')
    if (token && raw) {
      try {
        const user = JSON.parse(raw)
        set({ token, user, isRestored: true })
      } catch {
        sessionStorage.clear()
        set({ isRestored: true })
      }
    } else {
      set({ isRestored: true })
    }
  },
}))
