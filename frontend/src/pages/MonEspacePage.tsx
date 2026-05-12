import { useEffect, useState } from 'react'
import api from '@/lib/axios'
import StatusBadge from '@/components/StatusBadge'
import type { Client, Payment, License } from '@/types'

const cardStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.05)',
  backdropFilter: 'blur(12px)',
}

export default function MonEspacePage() {
  const [client, setClient]     = useState<Client | null>(null)
  const [payments, setPayments] = useState<Payment[]>([])
  const [licenses, setLicenses] = useState<License[]>([])
  const [loading, setLoading]   = useState(true)
  const [tab, setTab]           = useState<'payments' | 'licenses'>('payments')

  useEffect(() => {
    api.get('/mon-espace')
      .then(({ data }) => {
        setClient(data.data.client)
        setPayments(data.data.payments)
        setLicenses(data.data.licenses)
      })
      .finally(() => setLoading(false))
  }, [])

  const exportPdf = () => {
    const token = sessionStorage.getItem('auth_token')
    window.open(`http://localhost:8000/api/mon-espace/export/pdf?token=${token}`, '_blank')
  }

  if (loading) return (
    <div className="flex items-center justify-center h-screen">
      <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
    </div>
  )

  if (!client) return (
    <div className="p-8 text-white/50">Profil client introuvable. Contactez l'administrateur.</div>
  )

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <div className="rounded-3xl p-6 mb-6 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
        <div>
          <h1 className="text-white text-2xl font-bold">Mon Espace</h1>
          <p className="text-white/70 text-sm mt-1">{client.nom} · {client.email}</p>
          <div className="mt-2">
            <StatusBadge status={client.statut_paiement} />
          </div>
        </div>
        <button onClick={exportPdf}
          className="px-4 py-2 rounded-xl text-white text-sm border border-white/30 hover:border-white/60 transition-all self-start sm:self-auto">
          📄 Exporter mes paiements (PDF)
        </button>
      </div>

      {/* Info */}
      <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        {[
          { label: 'Téléphone', value: client.telephone },
          { label: 'Date maintenance', value: new Date(client.date_maintenance).toLocaleDateString('fr-FR') },
          { label: 'Paiements', value: payments.length },
          { label: 'Licences', value: licenses.length },
        ].map((item) => (
          <div key={item.label} className="rounded-2xl p-4 border border-white/10" style={cardStyle}>
            <p className="text-white/50 text-xs">{item.label}</p>
            <p className="text-white font-bold text-lg mt-1">{item.value}</p>
          </div>
        ))}
      </div>

      {/* Tabs */}
      <div className="flex gap-2 mb-4">
        {(['payments', 'licenses'] as const).map((t) => (
          <button key={t} onClick={() => setTab(t)}
            className={`px-4 py-2 rounded-xl text-sm font-medium transition-all ${tab === t ? 'text-white' : 'text-white/50 hover:text-white'}`}
            style={tab === t ? { background: 'linear-gradient(135deg, rgba(34,65,154,0.5) 0%, rgba(67,150,112,0.5) 100%)', border: '1px solid rgba(255,255,255,0.1)' } : {}}>
            {t === 'payments' ? `💳 Paiements (${payments.length})` : `🔑 Licences (${licenses.length})`}
          </button>
        ))}
      </div>

      {tab === 'payments' && (
        <div className="rounded-3xl border border-white/10 overflow-hidden" style={cardStyle}>
          <table className="w-full text-sm">
            <thead>
              <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                {['Date', 'Montant', 'Statut'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-white/50 font-medium text-xs uppercase tracking-wide">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {payments.length === 0 && (
                <tr><td colSpan={3} className="px-4 py-8 text-center text-white/40">Aucun paiement.</td></tr>
              )}
              {payments.map((p) => (
                <tr key={p.id} className="hover:bg-white/5 transition-colors" style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td className="px-4 py-3 text-white/70">{new Date(p.date_payment).toLocaleDateString('fr-FR')}</td>
                  <td className="px-4 py-3 text-white font-medium">{Number(p.montant).toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €</td>
                  <td className="px-4 py-3"><StatusBadge status={p.status_payment} /></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {tab === 'licenses' && (
        <div className="rounded-3xl border border-white/10 overflow-hidden" style={cardStyle}>
          <table className="w-full text-sm">
            <thead>
              <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                {['Nom', 'Quantité', 'Date assignation'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-white/50 font-medium text-xs uppercase tracking-wide">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {licenses.length === 0 && (
                <tr><td colSpan={3} className="px-4 py-8 text-center text-white/40">Aucune licence.</td></tr>
              )}
              {licenses.map((l) => (
                <tr key={l.id} className="hover:bg-white/5 transition-colors" style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td className="px-4 py-3 text-white font-medium">{l.nom}</td>
                  <td className="px-4 py-3 text-white/70">{l.quantite_disponible}</td>
                  <td className="px-4 py-3 text-white/70">{new Date(l.date_assignation).toLocaleDateString('fr-FR')}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}
