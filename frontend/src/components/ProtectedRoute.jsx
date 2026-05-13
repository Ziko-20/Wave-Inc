import { Navigate, Outlet } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'

export default function ProtectedRoute({ roles, redirectTo = '/login' }) {
  const { isAuthenticated, hasRole } = useAuthStore()

  if (!isAuthenticated()) {
    return <Navigate to={redirectTo} replace />
  }

  if (roles && !hasRole(roles)) {
    const user = useAuthStore.getState().user
    const isClient = user?.roles.includes('client')
    return <Navigate to={isClient ? '/mon-espace' : '/dashboard'} replace />
  }

  return <Outlet />
}
