import { create } from 'zustand'
import { persist } from 'zustand/middleware'

interface User {
  id: number
  email: string
  name: string
  avatar: string | null
  role: string
  role_level: number
  is_active: boolean
  person: {
    id: number
    first_name: string
    last_name: string
    email: string
    phone: string
    photo_url: string
    status: string
    status_label: string
    status_color: string
  }
}

interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
  setAuth: (user: User, token: string) => void
  logout: () => void
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set) => ({
      user: null,
      token: null,
      isAuthenticated: false,
      setAuth: (user, token) => {
        set({ user, token, isAuthenticated: true })
      },
      logout: () => {
        set({ user: null, token: null, isAuthenticated: false })
        localStorage.removeItem('auth-storage')
      },
    }),
    {
      name: 'auth-storage',
    }
  )
)
