import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '@/lib/axios'
import StatCard from '@/components/StatCard'
import type { Stats } from '@/types'

export default function DashboardPage() {
  const [stats, setStats]     = useState<Stats | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError]     = useState<string | null>(null)

  useEffect(() => {
    api.get('/stats')
      .then(({ data }) => setStats(data.data))
      .catch(() => setError('Impossible de charger les statistiques.'))
      .finally(() => setLoading(false))
  }, [])

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <div
        className="rounded-3xl p-6 mb-8 border border-white/10"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}
      >
        <h1 className="text-white text-2xl font-bold">Tableau de bord</h1>
        <p className="text-white/70 text-sm mt-1">Vue d'ensemble de votre activité</p>
      </div>

      {loading && (
        <div className="flex items-center justify-center h-40">
          <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
        </div>
      )}

      {error && (
        <div className="px-4 py-3 rounded-xl text-red-300 text-sm"
          style={{ background: 'rgba(220,38,38,0.15)', border: '1px solid rgba(220,38,38,0.3)' }}>
          {error}
        </div>
      )}

      {stats && (
        <>
          {/* Stats grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <StatCard
              icon="💰"
              label="MRR (ce mois)"
              value={`${stats.mrr.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €`}
              gradient
            />
            <StatCard
              icon="👥"
              label="Total clients"
              value={stats.total_clients}
            />
            <StatCard
              icon="✅"
              label="Clients payés"
              value={stats.paid}
              sub={`${stats.total_clients ? Math.round((stats.paid / stats.total_clients) * 100) : 0}%`}
            />
            <StatCard
              icon="⚠️"
              label="En attente / retard"
              value={stats.pending + stats.late}
              sub={`${stats.pending} en attente · ${stats.late} en retard`}
            />
          </div>

          {/* Quick actions */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {[
              { to: '/clients', icon: '👥', label: 'Gérer les clients', desc: 'Ajouter, modifier, exporter' },
              { to: '/chart',   icon: '📈', label: 'Revenus mensuels',  desc: 'Graphique annuel' },
              { to: '/managers',icon: '🛡️', label: 'Managers',          desc: 'Gérer les accès' },
            ].map((item) => (
              <Link
                key={item.to}
                to={item.to}
                className="rounded-3xl p-5 border border-white/10 flex items-center gap-4 transition-all duration-200 hover:border-white/20 hover:scale-[1.02]"
                style={{ background: 'rgba(255,255,255,0.05)', backdropFilter: 'blur(12px)' }}
              >
                <span className="text-3xl">{item.icon}</span>
                <div>
                  <p className="text-white font-semibold text-sm">{item.label}</p>
                  <p className="text-white/50 text-xs">{item.desc}</p>
                </div>
              </Link>
            ))}
          </div>
        </>
      )}
    </div>
  )
}
