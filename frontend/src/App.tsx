import { useEffect } from 'react'
import { createBrowserRouter, RouterProvider, Navigate } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'

import LoginPage        from '@/pages/LoginPage'
import DashboardPage    from '@/pages/DashboardPage'
import ClientsPage      from '@/pages/ClientsPage'
import ClientDetailPage from '@/pages/ClientDetailPage'
import ManagersPage     from '@/pages/ManagersPage'
import RevenuePage      from '@/pages/RevenuePage'
import MonEspacePage    from '@/pages/MonEspacePage'
import BoutiquePage     from '@/pages/BoutiquePage'

import AppLayout      from '@/components/AppLayout'
import ProtectedRoute from '@/components/ProtectedRoute'

const router = createBrowserRouter([
  // Public
  { path: '/login', element: <LoginPage /> },

  // Redirect root
  { path: '/', element: <Navigate to="/dashboard" replace /> },

  // Admin + Manager routes
  {
    element: <ProtectedRoute roles={['admin', 'manager']} />,
    children: [
      {
        element: <AppLayout />,
        children: [
          { path: '/dashboard',   element: <DashboardPage /> },
          { path: '/clients',     element: <ClientsPage /> },
          { path: '/clients/:id', element: <ClientDetailPage /> },
          { path: '/chart',       element: <RevenuePage /> },
        ],
      },
    ],
  },

  // Admin-only routes
  {
    element: <ProtectedRoute roles={['admin']} />,
    children: [
      {
        element: <AppLayout />,
        children: [
          { path: '/managers', element: <ManagersPage /> },
        ],
      },
    ],
  },

  // Client routes
  {
    element: <ProtectedRoute roles={['client']} />,
    children: [
      {
        element: <AppLayout />,
        children: [
          { path: '/mon-espace', element: <MonEspacePage /> },
          { path: '/boutique',   element: <BoutiquePage /> },
        ],
      },
    ],
  },

  // Catch-all
  { path: '*', element: <Navigate to="/login" replace /> },
])

export default function App() {
  const restoreSession = useAuthStore((s) => s.restoreSession)
  const isRestored     = useAuthStore((s) => s.isRestored)

  useEffect(() => {
    restoreSession()
  }, [restoreSession])

  // Wait for session restore before rendering routes
  // Prevents flash-redirect to /login on page refresh
  if (!isRestored) {
    return (
      <div
        style={{ backgroundColor: '#0d1b2a' }}
        className="min-h-screen flex items-center justify-center"
      >
        <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
      </div>
    )
  }

  return <RouterProvider router={router} />
}
