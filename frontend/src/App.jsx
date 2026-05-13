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
  { path: '/login', element: <LoginPage /> },
  { path: '/', element: <Navigate to="/dashboard" replace /> },

  {
    element: <ProtectedRoute roles={['admin', 'manager']} />,
    children: [{
      element: <AppLayout />,
      children: [
        { path: '/dashboard',   element: <DashboardPage /> },
        { path: '/clients',     element: <ClientsPage /> },
        { path: '/clients/:id', element: <ClientDetailPage /> },
        { path: '/chart',       element: <RevenuePage /> },
      ],
    }],
  },

  {
    element: <ProtectedRoute roles={['admin']} />,
    children: [{
      element: <AppLayout />,
      children: [
        { path: '/managers', element: <ManagersPage /> },
      ],
    }],
  },

  {
    element: <ProtectedRoute roles={['client']} />,
    children: [{
      element: <AppLayout />,
      children: [
        { path: '/mon-espace', element: <MonEspacePage /> },
        { path: '/boutique',   element: <BoutiquePage /> },
      ],
    }],
  },

  { path: '*', element: <Navigate to="/login" replace /> },
])

export default function App() {
  const restoreSession = useAuthStore((s) => s.restoreSession)
  const isRestored     = useAuthStore((s) => s.isRestored)

  useEffect(() => {
    restoreSession()
  }, [restoreSession])

  if (!isRestored) {
    return (
      <div style={{ backgroundColor: '#f4f6fb' }} className="min-h-screen flex items-center justify-center">
        <div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" />
      </div>
    )
  }

  return <RouterProvider router={router} />
}
