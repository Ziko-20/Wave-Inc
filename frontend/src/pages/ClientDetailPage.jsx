import { useEffect, useState } from 'react'
import { useParams, Link } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { ArrowLeft, CreditCard, Key, Bell, FileText, Plus, Pencil, Trash2, X, Phone, Mail, Calendar, Package } from 'lucide-react'
import api from '@/lib/axios'
import StatusBadge from '@/components/StatusBadge'

const S = {
  card:  { background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '1rem', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' },
  input: { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '0.625rem', color: '#1e293b' },
  grad:  'linear-gradient(135deg, #22419A 0%, #439670 100%)',
}

/* ─── Payment Modal ─────────────────────────────────────────────────────────── */
function PaymentModal({ clientId, payment, onClose, onSaved }) {
  const isEdit = !!payment
  const [form, setForm] = useState({
    montant: payment?.montant ?? '',
    date_payment: payment?.date_payment ?? new Date().toISOString().split('T')[0],
    status_payment: payment?.status_payment ?? 'en_attente',
  })
  const [errors, setErrors] = useState({})
  const [saving, setSaving] = useState(false)

  const handleSubmit = async (e) => {
    e.preventDefault(); setSaving(true); setErrors({})
    try {
      isEdit ? await api.put(`/payments/${payment.id}`, form) : await api.post(`/clients/${clientId}/payments`, form)
      onSaved(); onClose()
    } catch (err) {
      if (err.response?.status === 422) {
        const flat = {}
        Object.entries(err.response.data.errors ?? {}).forEach(([k, v]) => { flat[k] = v[0] })
        setErrors(flat)
      }
    } finally { setSaving(false) }
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4"
      style={{ background: 'rgba(15,23,42,0.4)', backdropFilter: 'blur(4px)' }}>
      <motion.div initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.96 }}
        className="w-full max-w-md rounded-2xl p-6" style={S.card}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="font-bold text-lg" style={{ color: '#1e293b' }}>{isEdit ? 'Modifier le paiement' : 'Ajouter un paiement'}</h2>
          <button onClick={onClose} className="p-1.5 rounded-lg hover:bg-slate-100"><X size={16} color="#64748b" /></button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-3">
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Montant (DH)</label>
            <input type="number" step="0.01" min="0" value={form.montant}
              onChange={(e) => setForm({ ...form, montant: e.target.value })}
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
            {errors.montant && <p className="text-xs mt-0.5" style={{ color: '#dc2626' }}>{errors.montant}</p>}
          </div>
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Date</label>
            <input type="date" value={form.date_payment} onChange={(e) => setForm({ ...form, date_payment: e.target.value })}
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
          </div>
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Statut</label>
            <select value={form.status_payment} onChange={(e) => setForm({ ...form, status_payment: e.target.value })}
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input}>
              <option value="en_attente">En attente</option>
              <option value="payé">Payé</option>
              <option value="en_retard">En retard</option>
            </select>
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose} className="flex-1 py-2 rounded-xl text-sm font-medium hover:bg-slate-50"
              style={{ border: '1px solid #e2e8f0', color: '#64748b' }}>Annuler</button>
            <motion.button type="submit" disabled={saving} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
              className="flex-1 py-2 rounded-xl text-white text-sm font-semibold disabled:opacity-60"
              style={{ background: S.grad }}>
              {saving ? 'Enregistrement...' : isEdit ? 'Modifier' : 'Ajouter'}
            </motion.button>
          </div>
        </form>
      </motion.div>
    </div>
  )
}

/* ─── License Modal ─────────────────────────────────────────────────────────── */
function LicenseModal({ clientId, onClose, onSaved }) {
  const [form, setForm] = useState({ nom: '', quantite_disponible: 1, date_assignation: new Date().toISOString().split('T')[0] })
  const [saving, setSaving] = useState(false)
  const handleSubmit = async (e) => {
    e.preventDefault(); setSaving(true)
    await api.post(`/clients/${clientId}/licenses`, form)
    onSaved(); onClose(); setSaving(false)
  }
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4"
      style={{ background: 'rgba(15,23,42,0.4)', backdropFilter: 'blur(4px)' }}>
      <motion.div initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.96 }}
        className="w-full max-w-md rounded-2xl p-6" style={S.card}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="font-bold text-lg" style={{ color: '#1e293b' }}>Assigner une licence</h2>
          <button onClick={onClose} className="p-1.5 rounded-lg hover:bg-slate-100"><X size={16} color="#64748b" /></button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-3">
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Nom de la licence</label>
            <input type="text" value={form.nom} onChange={(e) => setForm({ ...form, nom: e.target.value })} required
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
          </div>
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Quantité</label>
            <input type="number" min="1" value={form.quantite_disponible}
              onChange={(e) => setForm({ ...form, quantite_disponible: Number(e.target.value) })}
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
          </div>
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Date d'assignation</label>
            <input type="date" value={form.date_assignation} onChange={(e) => setForm({ ...form, date_assignation: e.target.value })}
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose} className="flex-1 py-2 rounded-xl text-sm font-medium hover:bg-slate-50"
              style={{ border: '1px solid #e2e8f0', color: '#64748b' }}>Annuler</button>
            <motion.button type="submit" disabled={saving} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
              className="flex-1 py-2 rounded-xl text-white text-sm font-semibold disabled:opacity-60"
              style={{ background: S.grad }}>
              {saving ? 'Assignation...' : 'Assigner'}
            </motion.button>
          </div>
        </form>
      </motion.div>
    </div>
  )
}

/* ─── Relance Modal ─────────────────────────────────────────────────────────── */
function RelanceModal({ client, onClose, onSaved }) {
  const [form, setForm] = useState({
    relance_flag: client.relance_flag,
    date_relance: client.date_relance ?? '',
    note_relance: client.note_relance ?? '',
  })
  const [saving, setSaving] = useState(false)
  const handleSubmit = async (e) => {
    e.preventDefault(); setSaving(true)
    await api.put(`/clients/${client.id}/relance`, form)
    onSaved(); onClose(); setSaving(false)
  }
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4"
      style={{ background: 'rgba(15,23,42,0.4)', backdropFilter: 'blur(4px)' }}>
      <motion.div initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.96 }}
        className="w-full max-w-md rounded-2xl p-6" style={S.card}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="font-bold text-lg" style={{ color: '#1e293b' }}>Relance client</h2>
          <button onClick={onClose} className="p-1.5 rounded-lg hover:bg-slate-100"><X size={16} color="#64748b" /></button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-3">
          <label className="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" checked={form.relance_flag}
              onChange={(e) => setForm({ ...form, relance_flag: e.target.checked })} className="w-4 h-4 rounded" />
            <span className="text-sm" style={{ color: '#374151' }}>Activer la relance</span>
          </label>
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Date de relance</label>
            <input type="date" value={form.date_relance} onChange={(e) => setForm({ ...form, date_relance: e.target.value })}
              className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
          </div>
          <div>
            <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Note</label>
            <textarea value={form.note_relance} onChange={(e) => setForm({ ...form, note_relance: e.target.value })}
              rows={3} className="w-full px-3 py-2 rounded-xl text-sm outline-none resize-none" style={S.input} />
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose} className="flex-1 py-2 rounded-xl text-sm font-medium hover:bg-slate-50"
              style={{ border: '1px solid #e2e8f0', color: '#64748b' }}>Annuler</button>
            <motion.button type="submit" disabled={saving} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
              className="flex-1 py-2 rounded-xl text-white text-sm font-semibold disabled:opacity-60"
              style={{ background: S.grad }}>
              {saving ? 'Enregistrement...' : 'Enregistrer'}
            </motion.button>
          </div>
        </form>
      </motion.div>
    </div>
  )
}

/* ─── Main Page ─────────────────────────────────────────────────────────────── */
export default function ClientDetailPage() {
  const { id } = useParams()
  const [client, setClient]   = useState(null)
  const [payments, setPayments] = useState([])
  const [licenses, setLicenses] = useState([])
  const [loading, setLoading] = useState(true)
  const [tab, setTab]         = useState('payments')
  const [showPayment, setShowPayment] = useState(false)
  const [editPayment, setEditPayment] = useState(undefined)
  const [showLicense, setShowLicense] = useState(false)
  const [showRelance, setShowRelance] = useState(false)

  const fetchClient = () => {
    setLoading(true)
    api.get(`/clients/${id}`).then(({ data }) => {
      setClient(data.data)
      setPayments(data.data.payments ?? [])
      setLicenses(data.data.license ?? [])
    }).finally(() => setLoading(false))
  }
  useEffect(() => { fetchClient() }, [id])

  const deletePayment = async (pid) => { if (!confirm('Supprimer ce paiement ?')) return; await api.delete(`/payments/${pid}`); fetchClient() }
  const deleteLicense = async (lid) => { if (!confirm('Supprimer cette licence ?')) return; await api.delete(`/licenses/${lid}`); fetchClient() }

  if (loading) return <div className="flex items-center justify-center h-screen"><div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" /></div>
  if (!client) return <div className="p-8 text-sm" style={{ color: '#94a3b8' }}>Client introuvable.</div>

  return (
    <div className="p-6 lg:p-8">
      <Link to="/clients" className="inline-flex items-center gap-1.5 text-sm mb-6 transition-colors hover:opacity-70" style={{ color: '#64748b' }}>
        <ArrowLeft size={15} /> Retour aux clients
      </Link>

      {/* Header */}
      <motion.div initial={{ opacity: 0, y: -8 }} animate={{ opacity: 1, y: 0 }}
        className="rounded-2xl p-6 mb-6 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        style={{ background: S.grad }}>
        <div>
          <h1 className="text-2xl font-bold">{client.nom}</h1>
          <div className="flex flex-wrap items-center gap-3 mt-2 text-white/80 text-sm">
            <span className="flex items-center gap-1"><Mail size={13} />{client.email}</span>
            <span className="flex items-center gap-1"><Phone size={13} />{client.telephone}</span>
          </div>
          <div className="flex items-center gap-2 mt-3">
            <StatusBadge status={client.statut_paiement} />
            {client.relance_flag && (
              <span className="text-xs px-2 py-0.5 rounded-full font-medium"
                style={{ background: 'rgba(251,191,36,0.25)', color: '#fef08a' }}>
                Relance active
              </span>
            )}
          </div>
        </div>
        <div className="flex gap-2 flex-wrap">
          <motion.button whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}
            onClick={() => setShowRelance(true)}
            className="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium"
            style={{ background: 'rgba(255,255,255,0.2)', color: '#ffffff' }}>
            <Bell size={14} /> Relance
          </motion.button>
          <motion.button whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}
            onClick={() => window.open(`http://localhost:8000/api/clients/${id}/export/pdf`, '_blank')}
            className="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium"
            style={{ background: 'rgba(255,255,255,0.2)', color: '#ffffff' }}>
            <FileText size={14} /> PDF
          </motion.button>
        </div>
      </motion.div>

      {/* Info grid */}
      <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.1 }}
        className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        {[
          { icon: Calendar,   label: 'Date maintenance',   value: new Date(client.date_maintenance).toLocaleDateString('fr-FR') },
          { icon: Package,    label: 'Nb licences',        value: client.licences_count },
          { icon: CreditCard, label: 'Paiements',          value: payments.length },
          { icon: Key,        label: 'Licences assignées', value: licenses.length },
        ].map((item) => (
          <div key={item.label} className="rounded-2xl p-4 flex items-center gap-3" style={S.card}>
            <div className="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: '#f0f4ff' }}>
              <item.icon size={16} color="#22419A" strokeWidth={2} />
            </div>
            <div>
              <p className="text-xs" style={{ color: '#64748b' }}>{item.label}</p>
              <p className="font-bold text-lg" style={{ color: '#1e293b' }}>{item.value}</p>
            </div>
          </div>
        ))}
      </motion.div>

      {/* Tabs */}
      <div className="flex gap-2 mb-4">
        {['payments', 'licenses'].map((t) => (
          <motion.button key={t} onClick={() => setTab(t)} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all"
            style={tab === t
              ? { background: S.grad, color: '#ffffff' }
              : { background: '#ffffff', border: '1px solid #e2e8f0', color: '#475569' }}>
            {t === 'payments' ? <><CreditCard size={14} /> Paiements ({payments.length})</> : <><Key size={14} /> Licences ({licenses.length})</>}
          </motion.button>
        ))}
      </div>

      <AnimatePresence mode="wait">
        {tab === 'payments' && (
          <motion.div key="payments" initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}
            className="rounded-2xl overflow-hidden" style={S.card}>
            <div className="flex items-center justify-between px-4 py-3"
              style={{ borderBottom: '1px solid #f1f5f9', background: '#f8fafc' }}>
              <p className="text-sm font-semibold" style={{ color: '#374151' }}>Historique des paiements</p>
              <motion.button whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}
                onClick={() => { setEditPayment(undefined); setShowPayment(true) }}
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-white text-xs font-semibold"
                style={{ background: S.grad }}>
                <Plus size={13} /> Ajouter
              </motion.button>
            </div>
            <table className="w-full text-sm">
              <thead>
                <tr style={{ borderBottom: '1px solid #f1f5f9', background: '#fafafa' }}>
                  {['Date','Montant','Statut','Actions'].map((h) => (
                    <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style={{ color: '#64748b' }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {payments.length === 0 && <tr><td colSpan={4} className="px-4 py-8 text-center text-sm" style={{ color: '#94a3b8' }}>Aucun paiement.</td></tr>}
                {payments.map((p) => (
                  <tr key={p.id} className="hover:bg-slate-50/60 transition-colors" style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td className="px-4 py-3" style={{ color: '#475569' }}>{new Date(p.date_payment).toLocaleDateString('fr-FR')}</td>
                    <td className="px-4 py-3 font-semibold" style={{ color: '#1e293b' }}>{Number(p.montant).toLocaleString('fr-MA', { minimumFractionDigits: 2 })} DH</td>
                    <td className="px-4 py-3"><StatusBadge status={p.status_payment} /></td>
                    <td className="px-4 py-3">
                      <div className="flex gap-1.5">
                        <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                          onClick={() => { setEditPayment(p); setShowPayment(true) }}
                          className="p-1.5 rounded-lg hover:bg-amber-50"><Pencil size={13} color="#d97706" /></motion.button>
                        <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                          onClick={() => deletePayment(p.id)}
                          className="p-1.5 rounded-lg hover:bg-red-50"><Trash2 size={13} color="#dc2626" /></motion.button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </motion.div>
        )}

        {tab === 'licenses' && (
          <motion.div key="licenses" initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}
            className="rounded-2xl overflow-hidden" style={S.card}>
            <div className="flex items-center justify-between px-4 py-3"
              style={{ borderBottom: '1px solid #f1f5f9', background: '#f8fafc' }}>
              <p className="text-sm font-semibold" style={{ color: '#374151' }}>Licences assignées</p>
              <motion.button whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}
                onClick={() => setShowLicense(true)}
                className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-white text-xs font-semibold"
                style={{ background: S.grad }}>
                <Plus size={13} /> Assigner
              </motion.button>
            </div>
            <table className="w-full text-sm">
              <thead>
                <tr style={{ borderBottom: '1px solid #f1f5f9', background: '#fafafa' }}>
                  {['Nom','Quantité','Date assignation','Actions'].map((h) => (
                    <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style={{ color: '#64748b' }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {licenses.length === 0 && <tr><td colSpan={4} className="px-4 py-8 text-center text-sm" style={{ color: '#94a3b8' }}>Aucune licence.</td></tr>}
                {licenses.map((l) => (
                  <tr key={l.id} className="hover:bg-slate-50/60 transition-colors" style={{ borderBottom: '1px solid #f1f5f9' }}>
                    <td className="px-4 py-3 font-medium" style={{ color: '#1e293b' }}>{l.nom}</td>
                    <td className="px-4 py-3" style={{ color: '#475569' }}>{l.quantite_disponible}</td>
                    <td className="px-4 py-3" style={{ color: '#475569' }}>{new Date(l.date_assignation).toLocaleDateString('fr-FR')}</td>
                    <td className="px-4 py-3">
                      <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                        onClick={() => deleteLicense(l.id)}
                        className="p-1.5 rounded-lg hover:bg-red-50"><Trash2 size={13} color="#dc2626" /></motion.button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </motion.div>
        )}
      </AnimatePresence>

      <AnimatePresence>
        {showPayment && <PaymentModal clientId={client.id} payment={editPayment} onClose={() => setShowPayment(false)} onSaved={fetchClient} />}
        {showLicense && <LicenseModal clientId={client.id} onClose={() => setShowLicense(false)} onSaved={fetchClient} />}
        {showRelance && <RelanceModal client={client} onClose={() => setShowRelance(false)} onSaved={fetchClient} />}
      </AnimatePresence>
    </div>
  )
}
