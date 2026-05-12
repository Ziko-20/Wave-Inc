import { useEffect, useState } from 'react'
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Cell,
} from 'recharts'
import api from '@/lib/axios'
import type { RevenueData } from '@/types'

const MONTHS = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']

const cardStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.05)',
  backdropFilter: 'blur(12px)',
}

export default function RevenuePage() {
  const [data, setData]       = useState<RevenueData | null>(null)
  const [year, setYear]       = useState(new Date().getFullYear())
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    setLoading(true)
    api.get('/stats/revenue', { params: { year } })
      .then(({ data }) => setData(data.data))
      .finally(() => setLoading(false))
  }, [year])

  const chartData = data?.monthly_revenue.map((total, i) => ({
    month: MONTHS[i],
    total,
  })) ?? []

  const totalYear = data?.monthly_revenue.reduce((a, b) => a + b, 0) ?? 0
  const maxMonth  = data ? Math.max(...data.monthly_revenue) : 0

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <div className="rounded-3xl p-6 mb-8 border border-white/10"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-white text-2xl font-bold">Revenus mensuels</h1>
            <p className="text-white/70 text-sm mt-1">Paiements encaissés par mois</p>
          </div>
          {data && (
            <select
              value={year}
              onChange={(e) => setYear(Number(e.target.value))}
              className="px-4 py-2 rounded-xl text-white text-sm outline-none"
              style={{ background: 'rgba(255,255,255,0.15)', border: '1px solid rgba(255,255,255,0.2)' }}
            >
              {data.available_years.map((y) => (
                <option key={y} value={y}>{y}</option>
              ))}
            </select>
          )}
        </div>
      </div>

      {/* Summary cards */}
      <div className="grid grid-cols-2 gap-4 mb-6">
        <div className="rounded-3xl p-5 border border-white/10" style={cardStyle}>
          <p className="text-white/50 text-sm">Total {year}</p>
          <p className="text-white text-2xl font-bold mt-1">
            {totalYear.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €
          </p>
        </div>
        <div className="rounded-3xl p-5 border border-white/10" style={cardStyle}>
          <p className="text-white/50 text-sm">Meilleur mois</p>
          <p className="text-white text-2xl font-bold mt-1">
            {maxMonth.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €
          </p>
        </div>
      </div>

      {/* Chart */}
      <div className="rounded-3xl p-6 border border-white/10" style={cardStyle}>
        {loading ? (
          <div className="flex items-center justify-center h-64">
            <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
          </div>
        ) : (
          <ResponsiveContainer width="100%" height={320}>
            <BarChart data={chartData} margin={{ top: 10, right: 10, left: 0, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(255,255,255,0.06)" />
              <XAxis dataKey="month" tick={{ fill: 'rgba(255,255,255,0.5)', fontSize: 12 }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fill: 'rgba(255,255,255,0.5)', fontSize: 12 }} axisLine={false} tickLine={false}
                tickFormatter={(v) => `${v.toLocaleString('fr-FR')} €`} />
              <Tooltip
                contentStyle={{ background: '#0d1b2a', border: '1px solid rgba(255,255,255,0.1)', borderRadius: '12px', color: '#fff' }}
                formatter={(value) => [`${Number(value).toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €`, 'Revenus']}
              />
              <Bar dataKey="total" radius={[6, 6, 0, 0]}>
                {chartData.map((_, i) => (
                  <Cell key={i} fill={`url(#barGrad)`} />
                ))}
              </Bar>
              <defs>
                <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#439670" />
                  <stop offset="100%" stopColor="#22419A" />
                </linearGradient>
              </defs>
            </BarChart>
          </ResponsiveContainer>
        )}
      </div>
    </div>
  )
}
