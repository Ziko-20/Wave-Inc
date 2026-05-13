import { useEffect, useState } from 'react'
import { motion } from 'framer-motion'
import { TrendingUp, Calendar, DollarSign } from 'lucide-react'
import {
  Chart as ChartJS,
  CategoryScale, LinearScale, LineElement,
  PointElement, Title, Tooltip, Legend, Filler,
} from 'chart.js'
import { Line } from 'react-chartjs-2'
import api from '@/lib/axios'

ChartJS.register(CategoryScale, LinearScale, LineElement, PointElement, Title, Tooltip, Legend, Filler)

const MONTHS = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']

export default function RevenuePage() {
  const [data, setData]       = useState(null)
  const [year, setYear]       = useState(new Date().getFullYear())
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setLoading(true)
    api.get('/stats/revenue', { params: { year } })
      .then(({ data }) => setData(data.data))
      .finally(() => setLoading(false))
  }, [year])

  const monthly      = data?.monthly_revenue ?? Array(12).fill(0)
  const totalYear    = monthly.reduce((a, b) => a + b, 0)
  const maxMonth     = Math.max(...monthly)
  const bestMonthIdx = monthly.indexOf(maxMonth)

  const chartData = {
    labels: MONTHS,
    datasets: [{
      label: `Revenus ${year} (DH)`,
      data: monthly,
      fill: true,
      backgroundColor: 'rgba(34,65,154,0.08)',
      borderColor: '#22419A',
      borderWidth: 2.5,
      tension: 0.4,
      pointBackgroundColor: '#439670',
      pointBorderColor: '#ffffff',
      pointBorderWidth: 2,
      pointRadius: 5,
      pointHoverRadius: 7,
      pointHoverBackgroundColor: '#22419A',
    }],
  }

  const options = {
    responsive: true,
    maintainAspectRatio: false,
    animation: { duration: 700, easing: 'easeInOutQuart' },
    plugins: {
      legend: {
        display: true,
        position: 'top',
        labels: { color: '#475569', font: { size: 12, family: 'Inter' }, boxWidth: 12, padding: 16 },
      },
      tooltip: {
        backgroundColor: '#1e293b',
        titleColor: '#f8fafc',
        bodyColor: '#cbd5e1',
        borderColor: '#334155',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 10,
        callbacks: {
          label: (ctx) => ` ${Number(ctx.raw).toLocaleString('fr-MA', { minimumFractionDigits: 2 })} DH`,
          title: (items) => MONTHS[items[0].dataIndex],
        },
      },
      title: { display: false },
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { color: '#64748b', font: { size: 12 } },
        border: { display: false },
      },
      y: {
        grid: { color: '#f1f5f9' },
        ticks: {
          color: '#64748b',
          font: { size: 11 },
          callback: (v) => `${Number(v).toLocaleString('fr-MA')} DH`,
        },
        border: { display: false },
      },
    },
  }

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.3 }}
        className="rounded-2xl p-6 mb-6 text-white flex items-center justify-between flex-wrap gap-4"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
        <div>
          <h1 className="text-2xl font-bold">Revenus mensuels</h1>
          <p className="text-white/70 text-sm mt-1">Paiements encaissés par mois</p>
        </div>
        {data && (
          <select value={year} onChange={(e) => setYear(Number(e.target.value))}
            className="px-3 py-1.5 rounded-xl text-sm outline-none"
            style={{ background: 'rgba(255,255,255,0.2)', color: '#ffffff', border: '1px solid rgba(255,255,255,0.3)' }}>
            {data.available_years.map((y) => (
              <option key={y} value={y} style={{ color: '#1e293b', background: '#ffffff' }}>{y}</option>
            ))}
          </select>
        )}
      </motion.div>

      {/* Summary cards */}
      <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.1 }}
        className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        {[
          { icon: DollarSign, label: `Total ${year}`, value: `${totalYear.toLocaleString('fr-MA', { minimumFractionDigits: 2 })} DH` },
          { icon: TrendingUp, label: 'Meilleur mois', value: `${maxMonth.toLocaleString('fr-MA', { minimumFractionDigits: 2 })} DH` },
          { icon: Calendar,   label: 'Mois record',   value: bestMonthIdx >= 0 && maxMonth > 0 ? MONTHS[bestMonthIdx] : '—' },
        ].map((s) => (
          <div key={s.label} className="rounded-2xl p-5 flex items-center gap-4"
            style={{ background: '#ffffff', border: '1px solid #e2e8f0', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' }}>
            <div className="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: '#f0f4ff' }}>
              <s.icon size={18} color="#22419A" strokeWidth={2} />
            </div>
            <div>
              <p className="text-xs" style={{ color: '#64748b' }}>{s.label}</p>
              <p className="text-lg font-bold" style={{ color: '#1e293b' }}>{s.value}</p>
            </div>
          </div>
        ))}
      </motion.div>

      {/* Chart */}
      <motion.div initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.15 }}
        className="rounded-2xl p-6"
        style={{ background: '#ffffff', border: '1px solid #e2e8f0', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' }}>
        {loading ? (
          <div className="flex items-center justify-center h-72">
            <div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" />
          </div>
        ) : (
          <div style={{ height: 320 }}>
            <Line data={chartData} options={options} />
          </div>
        )}
      </motion.div>
    </div>
  )
}
