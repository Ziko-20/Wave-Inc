import { NavLink, useNavigate } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'
import appLogo from '@/img/appLogo.jpeg'
import {
  LayoutDashboard, Users, BarChart2, ShieldCheck,
  Home, ShoppingBag, LogOut,
} from 'lucide-react'

const navItems = [
  { to: '/dashboard',  label: 'Dashboard',  icon: LayoutDashboard, roles: ['admin', 'manager'] },
  { to: '/clients',    label: 'Clients',    icon: Users,           roles: ['admin', 'manager'] },
  { to: '/chart',      label: 'Revenus',    icon: BarChart2,       roles: ['admin', 'manager'] },
  { to: '/managers',   label: 'Managers',   icon: ShieldCheck,     roles: ['admin'] },
  { to: '/mon-espace', label: 'Mon Espace', icon: Home,            roles: ['client'] },
  { to: '/boutique',   label: 'Boutique',   icon: ShoppingBag,     roles: ['client'] },
]

export default function Sidebar() {
  const { user, logout, hasRole } = useAuthStore()
  const navigate = useNavigate()

  const handleLogout = async () => {
    await logout()
    navigate('/login')
  }

  const visibleItems = navItems.filter(
    (item) => !item.roles || hasRole(item.roles)
  )

  return (
    <aside className="w-64 min-h-screen flex flex-col"
      style={{ background: '#ffffff', borderRight: '1px solid #e2e8f0' }}>

      {/* Logo */}
      <div className="px-5 py-5" style={{ borderBottom: '1px solid #e2e8f0' }}>
        <div className="flex items-center gap-3">
          <img src={appLogo} alt="Wavy" className="w-9 h-9 rounded-xl object-cover flex-shrink-0" />
          <div>
            <p className="font-bold text-sm" style={{ color: '#1e293b' }}>Wavy</p>
            <p className="text-xs" style={{ color: '#94a3b8' }}>Gestion clients</p>
          </div>
        </div>
      </div>

      {/* Nav */}
      <nav className="flex-1 px-3 py-4 space-y-0.5">
        {visibleItems.map((item) => (
          <NavLink
            key={item.to}
            to={item.to}
            className={({ isActive }) =>
              `flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 ${
                isActive ? 'text-white' : 'hover:bg-slate-50'
              }`
            }
            style={({ isActive }) =>
              isActive
                ? { background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)', color: '#ffffff' }
                : { color: '#475569' }
            }
          >
            {({ isActive }) => (
              <>
                <item.icon size={17} strokeWidth={2} color={isActive ? '#ffffff' : '#64748b'} />
                {item.label}
              </>
            )}
          </NavLink>
        ))}
      </nav>

      {/* User + logout */}
      <div className="px-4 py-4" style={{ borderTop: '1px solid #e2e8f0' }}>
        <div className="flex items-center gap-3 mb-3">
          <div className="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
            style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
            {user?.name?.charAt(0).toUpperCase()}
          </div>
          <div className="flex-1 min-w-0">
            <p className="text-sm font-medium truncate" style={{ color: '#1e293b' }}>{user?.name}</p>
            <p className="text-xs truncate capitalize" style={{ color: '#94a3b8' }}>{user?.roles?.[0]}</p>
          </div>
        </div>
        <button onClick={handleLogout}
          className="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm transition-all duration-150 hover:bg-red-50"
          style={{ color: '#ef4444' }}>
          <LogOut size={15} strokeWidth={2} />
          Déconnexion
        </button>
      </div>
    </aside>
  )
}
