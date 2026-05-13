import { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { CreditCard, Key, Phone, Mail, Calendar, FileText } from 'lucide-react'
import api from '@/lib/axios'
import StatusBadge from '@/components/StatusBadge'

const S = {
  card: { background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '1rem', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' },
}

export default function MonEspacePage() {
  const [client, setClient]     = useState(null)
  const [payments, setPayments] = useState([])
  const [licenses, setLicenses] = useState([])
  const [loading, setLoading]   = useState(true)
  const [tab, setTab]           = useState('payments')

  useEffect(() => {
    api.get('/mon-espace').then(({ data }) => {
      setClient(data.data.client)
      setPayments(data.data.payments)
      setLicenses(data.data.licenses)
    }).finally(() => setLoading(false))
  }, [])

  if (loading) return <div className="flex items-center justify-center h-screen"><div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" /></div>
  if (!client) return <div className="p-8 text-sm" style={{ color: '#94a3b8' }}>Profil client introuvable. Contactez l'administrateur.</div>

  return (
    <div className="p-6 lg:p-8">
      <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }}
        className="rounded-2xl p-6 mb-6 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
        <div>
          <h1 className="text-2xl font-bold">Mon Espace</h1>
          <div className="flex flex-wrap items-center gap-3 mt-2 text-white/80 text-sm">
            <span className="flex items-center gap-1"><Mail size={13} />{client.email}</span>
            <span className="flex items-center gap-1"><Phone size={13} />{client.telephone}</span>
          </div>
          <div className="mt-3"><StatusBadge status={client.statut_paiement} /></div>
        </div>
        <motion.button whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}
          onClick={() => window.open('http://localhost:8000/api/mon-espace/export/pdf', '_blank')}
          className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium self-start sm:self-auto"
          style={{ background: 'rgba(255,255,255,0.2)', color: '#ffffff' }}>
          <FileText size={15} /> Exporter PDF
        </motion.button>
      </motion.div>

      <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.1 }}
        className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        {[
          { icon: Phone,     label: 'Téléphone',        value: client.telephone },
          { icon: Calendar,  label: 'Date maintenance', value: new Date(client.date_maintenance).toLocaleDateString('fr-FR') },
          { icon: CreditCard,label: 'Paiements',        value: payments.length },
          { icon: Key,       label: 'Licences',         value: licenses.length },
        ].map((item) => (
          <div key={item.label} className="rounded-2xl p-4 flex items-center gap-3" style={S.card}>
            <div className="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: '#f0f4ff' }}>
              <item.icon size={16} color="#22419A" strokeWidth={2} />
            </div>
            <div>
              <p className="text-xs" style={{ color: '#64748b' }}>{item.label}</p>
              <p className="font-bold text-base" style={{ color: '#1e293b' }}>{item.value}</p>
            </div>
          </div>
        ))}
      </motion.div>

      <div className="flex gap-2 mb-4">
        {['payments', 'licenses'].map((t) => (
          <motion.button key={t} onClick={() => setTab(t)} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all"
            style={tab === t
              ? { background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)', color: '#ffffff' }
              : { background: '#ffffff', border: '1px solid #e2e8f0', color: '#475569' }}>
            {t === 'payments' ? <><CreditCard size={14} /> Paiements ({payments.length})</> : <><Key size={14} /> Licences ({licenses.length})</>}
          </motion.button>
        ))}
      </div>

      <AnimatePresence mode="wait">
        {tab === 'payments' && (
          <motion.div key="p" initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}
            className="rounded-2xl overflow-hidden" style={S.card}>
            <table className="w-full text-sm">
              <thead>
                <tr style={{ borderBottom: '1px solid #f1f5f9', background: '#f8fafc' }}>
                  {['Date','Montant','Statut'].map((h) => (
                    <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style={{ color: '#64748b' }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {payments.length === 0 && <tr><td colSpan={3} className="px-4 py-8 text-center text-sm" style={{ color: '#94a3b8' }}>Aucun paiement.</td></tr>}
                {payments.map((p) => (
                  <tr key={p.id} className="hover:bg-slate-50/60 transition-colors" style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td className="px-4 py-3" style={{ color: '#475569' }}>{new Date(p.date_payment).toLocaleDateString('fr-FR')}</td>
                    <td className="px-4 py-3 font-semibold" style={{ color: '#1e293b' }}>{Number(p.montant).toLocaleString('fr-MA', { minimumFractionDigits: 2 })} DH</td>
                    <td className="px-4 py-3"><StatusBadge status={p.status_payment} /></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </motion.div>
        )}
        {tab === 'licenses' && (
          <motion.div key="l" initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}
            className="rounded-2xl overflow-hidden" style={S.card}>
            <table className="w-full text-sm">
              <thead>
                <tr style={{ borderBottom: '1px solid #f1f5f9', background: '#f8fafc' }}>
                  {['Nom','Quantité','Date assignation'].map((h) => (
                    <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style={{ color: '#64748b' }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {licenses.length === 0 && <tr><td colSpan={3} className="px-4 py-8 text-center text-sm" style={{ color: '#94a3b8' }}>Aucune licence.</td></tr>}
                {licenses.map((l) => (
                  <tr key={l.id} className="hover:bg-slate-50/60 transition-colors" style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td className="px-4 py-3 font-medium" style={{ color: '#1e293b' }}>{l.nom}</td>
                    <td className="px-4 py-3" style={{ color: '#475569' }}>{l.quantite_disponible}</td>
                    <td className="px-4 py-3" style={{ color: '#475569' }}>{new Date(l.date_assignation).toLocaleDateString('fr-FR')}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  )
}
