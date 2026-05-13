import { NavLink, useNavigate } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'
import appLogo from '@/img/appLogo.jpeg'

interface NavItem {
  to: string
  label: string
  icon: string
  roles?: string[]
}

const navItems: NavItem[] = [
  { to: '/dashboard', label: 'Dashboard',  icon: '📊', roles: ['admin', 'manager'] },
  { to: '/clients',   label: 'Clients',    icon: '👥', roles: ['admin', 'manager'] },
  { to: '/chart',     label: 'Revenus',    icon: '📈', roles: ['admin', 'manager'] },
  { to: '/managers',  label: 'Managers',   icon: '🛡️', roles: ['admin'] },
  { to: '/mon-espace',label: 'Mon Espace', icon: '🏠', roles: ['client'] },
  { to: '/boutique',  label: 'Boutique',   icon: '🛒', roles: ['client'] },
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
      style={{ background: 'rgba(255,255,255,0.03)', borderRight: '1px solid rgba(255,255,255,0.08)' }}>

      {/* Logo */}
      <div className="px-6 py-6 border-b border-white/10">
        <div className="flex items-center gap-3">
          <img
            src={appLogo}
            alt="Wavy"
            className="w-9 h-9 rounded-xl object-cover flex-shrink-0"
          />
          <div>
            <p className="text-white font-semibold text-sm leading-tight">Wavy</p>
            <p className="text-white/40 text-xs">v2.0</p>
          </div>
        </div>
      </div>

      {/* Nav */}
      <nav className="flex-1 px-3 py-4 space-y-1">
        {visibleItems.map((item) => (
          <NavLink
            key={item.to}
            to={item.to}
            className={({ isActive }) =>
              `flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 ${
                isActive
                  ? 'text-white'
                  : 'text-white/60 hover:text-white hover:bg-white/5'
              }`
            }
            style={({ isActive }) =>
              isActive
                ? { background: 'linear-gradient(135deg, rgba(34,65,154,0.4) 0%, rgba(67,150,112,0.4) 100%)', border: '1px solid rgba(255,255,255,0.1)' }
                : {}
            }
          >
            <span className="text-base">{item.icon}</span>
            {item.label}
          </NavLink>
        ))}
      </nav>

      {/* User info + logout */}
      <div className="px-4 py-4 border-t border-white/10">
        <div className="flex items-center gap-3 mb-3">
          <div className="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
            style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
            {user?.name?.charAt(0).toUpperCase()}
          </div>
          <div className="flex-1 min-w-0">
            <p className="text-white text-xs font-medium truncate">{user?.name}</p>
            <p className="text-white/40 text-xs truncate">{user?.roles?.[0]}</p>
          </div>
        </div>
        <button
          onClick={handleLogout}
          className="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-white/60 hover:text-white hover:bg-white/5 text-sm transition-all duration-200"
        >
          <span>🚪</span> Déconnexion
        </button>
      </div>
    </aside>
  )
}
