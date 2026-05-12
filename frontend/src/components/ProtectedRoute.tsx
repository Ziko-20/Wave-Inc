import { Navigate, Outlet } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'

interface ProtectedRouteProps {
  roles?: string[]
  redirectTo?: string
}

/**
 * Redirects to /login if not authenticated.
 * If `roles` is provided, also checks that the user has at least one of them.
 */
export default function ProtectedRoute({ roles, redirectTo = '/login' }: ProtectedRouteProps) {
  const { isAuthenticated, hasRole } = useAuthStore()

  if (!isAuthenticated()) {
    return <Navigate to={redirectTo} replace />
  }

  if (roles && !hasRole(roles)) {
    // Authenticated but wrong role — send to their home
    const user = useAuthStore.getState().user
    const isClient = user?.roles.includes('client')
    return <Navigate to={isClient ? '/mon-espace' : '/dashboard'} replace />
  }

  return <Outlet />
}
