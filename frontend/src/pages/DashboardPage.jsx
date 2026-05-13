import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { motion } from 'framer-motion'
import { TrendingUp, Users, CheckCircle, AlertTriangle, BarChart2, ShieldCheck } from 'lucide-react'
import api from '@/lib/axios'
import StatCard from '@/components/StatCard'

const container = {
  hidden: {},
  show: { transition: { staggerChildren: 0.07 } },
}
const item = {
  hidden: { opacity: 0, y: 16 },
  show:   { opacity: 1, y: 0, transition: { duration: 0.28 } },
}

export default function DashboardPage() {
  const [stats, setStats]     = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError]     = useState(null)

  useEffect(() => {
    api.get('/stats')
      .then(({ data }) => setStats(data.data))
      .catch(() => setError('Impossible de charger les statistiques.'))
      .finally(() => setLoading(false))
  }, [])

  return (
    <div className="p-6 lg:p-8">
      <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.3 }}
        className="rounded-2xl p-6 mb-8 text-white"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
        <h1 className="text-2xl font-bold">Tableau de bord</h1>
        <p className="text-white/70 text-sm mt-1">Vue d'ensemble de votre activité</p>
      </motion.div>

      {loading && (
        <div className="flex items-center justify-center h-40">
          <div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" />
        </div>
      )}

      {error && (
        <div className="px-4 py-3 rounded-xl text-sm flex items-center gap-2"
          style={{ background: '#fee2e2', color: '#dc2626', border: '1px solid #fecaca' }}>
          <AlertTriangle size={15} /> {error}
        </div>
      )}

      {stats && (
        <>
          <motion.div variants={container} initial="hidden" animate="show"
            className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <motion.div variants={item}>
              <StatCard icon={TrendingUp} label="MRR (ce mois)" accent
                value={`${stats.mrr.toLocaleString('fr-MA', { minimumFractionDigits: 2 })} DH`} />
            </motion.div>
            <motion.div variants={item}>
              <StatCard icon={Users} label="Total clients" value={stats.total_clients} />
            </motion.div>
            <motion.div variants={item}>
              <StatCard icon={CheckCircle} label="Clients payés" value={stats.paid}
                sub={`${stats.total_clients ? Math.round((stats.paid / stats.total_clients) * 100) : 0}%`} />
            </motion.div>
            <motion.div variants={item}>
              <StatCard icon={AlertTriangle} label="En attente / retard" value={stats.pending + stats.late}
                sub={`${stats.pending} en attente · ${stats.late} en retard`} />
            </motion.div>
          </motion.div>

          <motion.div variants={container} initial="hidden" animate="show"
            className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {[
              { to: '/clients',  icon: Users,       label: 'Gérer les clients', desc: 'Ajouter, modifier, exporter' },
              { to: '/chart',    icon: BarChart2,   label: 'Revenus mensuels',  desc: 'Graphique annuel' },
              { to: '/managers', icon: ShieldCheck, label: 'Managers',          desc: 'Gérer les accès' },
            ].map((a) => (
              <motion.div key={a.to} variants={item}>
                <motion.div whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}>
                  <Link to={a.to} className="rounded-2xl p-5 flex items-center gap-4 transition-all"
                    style={{ background: '#ffffff', border: '1px solid #e2e8f0', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' }}>
                    <div className="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: '#f0f4ff' }}>
                      <a.icon size={18} color="#22419A" strokeWidth={2} />
                    </div>
                    <div>
                      <p className="font-semibold text-sm" style={{ color: '#1e293b' }}>{a.label}</p>
                      <p className="text-xs" style={{ color: '#94a3b8' }}>{a.desc}</p>
                    </div>
                  </Link>
                </motion.div>
              </motion.div>
            ))}
          </motion.div>
        </>
      )}
    </div>
  )
}
