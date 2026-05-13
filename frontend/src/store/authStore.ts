import { create } from 'zustand'
import api from '@/lib/axios'

export interface AuthUser {
  id: number
  name: string
  email: string
  roles: string[]
}

interface AuthState {
  token: string | null
  user: AuthUser | null
  isLoading: boolean
  isRestored: boolean   // true once restoreSession() has run

  // Derived helpers
  isAuthenticated: () => boolean
  hasRole: (role: string | string[]) => boolean

  // Actions
  login: (email: string, password: string) => Promise<void>
  logout: () => Promise<void>
  restoreSession: () => void
}

export const useAuthStore = create<AuthState>((set, get) => ({
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

      // Persist to sessionStorage (no localStorage — per spec)
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
      // ignore network errors on logout
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
        const user = JSON.parse(raw) as AuthUser
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
