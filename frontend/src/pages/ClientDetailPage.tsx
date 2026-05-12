import { useEffect, useState } from 'react'
import { useParams, Link } from 'react-router-dom'
import api from '@/lib/axios'
import StatusBadge from '@/components/StatusBadge'
import type { Client, Payment, License, PaymentStatus } from '@/types'

const cardStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.05)',
  backdropFilter: 'blur(12px)',
}
const inputStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.08)',
  border: '1px solid rgba(255,255,255,0.15)',
}

/* ─── Payment Modal ─────────────────────────────────────────────────────────── */
function PaymentModal({
  clientId, payment, onClose, onSaved,
}: { clientId: number; payment?: Payment; onClose: () => void; onSaved: () => void }) {
  const isEdit = !!payment
  const [form, setForm] = useState({
    montant:        payment?.montant        ?? '',
    date_payment:   payment?.date_payment   ?? new Date().toISOString().split('T')[0],
    status_payment: payment?.status_payment ?? 'en_attente',
  })
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [saving, setSaving] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSaving(true)
    setErrors({})
    try {
      if (isEdit) {
        await api.put(`/payments/${payment!.id}`, form)
      } else {
        await api.post(`/clients/${clientId}/payments`, form)
      }
      onSaved()
      onClose()
    } catch (err: any) {
      if (err.response?.status === 422) {
        const raw = err.response.data.errors ?? {}
        const flat: Record<string, string> = {}
        Object.entries(raw).forEach(([k, v]) => { flat[k] = (v as string[])[0] })
        setErrors(flat)
      }
    } finally {
      setSaving(false)
    }
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(4px)' }}>
      <div className="w-full max-w-md rounded-3xl border border-white/10 p-6" style={cardStyle}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="text-white font-bold text-lg">{isEdit ? 'Modifier le paiement' : 'Ajouter un paiement'}</h2>
          <button onClick={onClose} className="text-white/50 hover:text-white text-xl">✕</button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-white/70 text-sm mb-1">Montant (€)</label>
            <input type="number" step="0.01" min="0" value={form.montant}
              onChange={(e) => setForm({ ...form, montant: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
            {errors.montant && <p className="text-red-400 text-xs mt-1">{errors.montant}</p>}
          </div>
          <div>
            <label className="block text-white/70 text-sm mb-1">Date</label>
            <input type="date" value={form.date_payment}
              onChange={(e) => setForm({ ...form, date_payment: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
          </div>
          <div>
            <label className="block text-white/70 text-sm mb-1">Statut</label>
            <select value={form.status_payment}
              onChange={(e) => setForm({ ...form, status_payment: e.target.value as PaymentStatus })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle}>
              <option value="en_attente">En attente</option>
              <option value="payé">Payé</option>
              <option value="en_retard">En retard</option>
            </select>
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose}
              className="flex-1 py-2.5 rounded-xl text-white/70 text-sm border border-white/20 hover:border-white/40 transition-all">
              Annuler
            </button>
            <button type="submit" disabled={saving}
              className="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02] disabled:opacity-60"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              {saving ? 'Enregistrement...' : isEdit ? 'Modifier' : 'Ajouter'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

/* ─── License Assign Modal ──────────────────────────────────────────────────── */
function LicenseAssignModal({ clientId, onClose, onSaved }: { clientId: number; onClose: () => void; onSaved: () => void }) {
  const [form, setForm] = useState({ nom: '', quantite_disponible: 1, date_assignation: new Date().toISOString().split('T')[0] })
  const [saving, setSaving] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSaving(true)
    await api.post(`/clients/${clientId}/licenses`, form)
    onSaved()
    onClose()
    setSaving(false)
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(4px)' }}>
      <div className="w-full max-w-md rounded-3xl border border-white/10 p-6" style={cardStyle}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="text-white font-bold text-lg">Assigner une licence</h2>
          <button onClick={onClose} className="text-white/50 hover:text-white text-xl">✕</button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-white/70 text-sm mb-1">Nom de la licence</label>
            <input type="text" value={form.nom} onChange={(e) => setForm({ ...form, nom: e.target.value })} required
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
          </div>
          <div>
            <label className="block text-white/70 text-sm mb-1">Quantité</label>
            <input type="number" min="1" value={form.quantite_disponible}
              onChange={(e) => setForm({ ...form, quantite_disponible: Number(e.target.value) })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
          </div>
          <div>
            <label className="block text-white/70 text-sm mb-1">Date d'assignation</label>
            <input type="date" value={form.date_assignation}
              onChange={(e) => setForm({ ...form, date_assignation: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose}
              className="flex-1 py-2.5 rounded-xl text-white/70 text-sm border border-white/20 hover:border-white/40 transition-all">
              Annuler
            </button>
            <button type="submit" disabled={saving}
              className="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02] disabled:opacity-60"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              {saving ? 'Assignation...' : 'Assigner'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

/* ─── Relance Modal ─────────────────────────────────────────────────────────── */
function RelanceModal({ client, onClose, onSaved }: { client: Client; onClose: () => void; onSaved: () => void }) {
  const [form, setForm] = useState({
    relance_flag: client.relance_flag,
    date_relance: client.date_relance ?? '',
    note_relance: client.note_relance ?? '',
  })
  const [saving, setSaving] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSaving(true)
    await api.put(`/clients/${client.id}/relance`, form)
    onSaved()
    onClose()
    setSaving(false)
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(4px)' }}>
      <div className="w-full max-w-md rounded-3xl border border-white/10 p-6" style={cardStyle}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="text-white font-bold text-lg">Relance client</h2>
          <button onClick={onClose} className="text-white/50 hover:text-white text-xl">✕</button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="flex items-center gap-3">
            <input type="checkbox" id="relance_flag" checked={form.relance_flag}
              onChange={(e) => setForm({ ...form, relance_flag: e.target.checked })}
              className="w-4 h-4 rounded" />
            <label htmlFor="relance_flag" className="text-white/70 text-sm">Activer la relance</label>
          </div>
          <div>
            <label className="block text-white/70 text-sm mb-1">Date de relance</label>
            <input type="date" value={form.date_relance}
              onChange={(e) => setForm({ ...form, date_relance: e.target.value })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
          </div>
          <div>
            <label className="block text-white/70 text-sm mb-1">Note</label>
            <textarea value={form.note_relance}
              onChange={(e) => setForm({ ...form, note_relance: e.target.value })}
              rows={3}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm resize-none"
              style={inputStyle} />
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose}
              className="flex-1 py-2.5 rounded-xl text-white/70 text-sm border border-white/20 hover:border-white/40 transition-all">
              Annuler
            </button>
            <button type="submit" disabled={saving}
              className="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02] disabled:opacity-60"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              {saving ? 'Enregistrement...' : 'Enregistrer'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

/* ─── Main Page ─────────────────────────────────────────────────────────────── */
type Tab = 'payments' | 'licenses'

export default function ClientDetailPage() {
  const { id } = useParams<{ id: string }>()
  const [client, setClient]   = useState<Client | null>(null)
  const [payments, setPayments] = useState<Payment[]>([])
  const [licenses, setLicenses] = useState<License[]>([])
  const [loading, setLoading] = useState(true)
  const [tab, setTab]         = useState<Tab>('payments')

  const [showPaymentModal, setShowPaymentModal]   = useState(false)
  const [editPayment, setEditPayment]             = useState<Payment | undefined>()
  const [showLicenseModal, setShowLicenseModal]   = useState(false)
  const [showRelanceModal, setShowRelanceModal]   = useState(false)

  const fetchClient = () => {
    setLoading(true)
    api.get(`/clients/${id}`)
      .then(({ data }) => {
        setClient(data.data)
        setPayments(data.data.payments ?? [])
        setLicenses(data.data.license ?? [])
      })
      .finally(() => setLoading(false))
  }

  useEffect(() => { fetchClient() }, [id])

  const deletePayment = async (paymentId: number) => {
    if (!confirm('Supprimer ce paiement ?')) return
    await api.delete(`/payments/${paymentId}`)
    fetchClient()
  }

  const deleteLicense = async (licenseId: number) => {
    if (!confirm('Supprimer cette licence ?')) return
    await api.delete(`/licenses/${licenseId}`)
    fetchClient()
  }

  const exportPdf = () => {
    const token = sessionStorage.getItem('auth_token')
    window.open(`http://localhost:8000/api/clients/${id}/export/pdf?token=${token}`, '_blank')
  }

  if (loading) return (
    <div className="flex items-center justify-center h-screen">
      <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
    </div>
  )

  if (!client) return (
    <div className="p-8 text-white/50">Client introuvable.</div>
  )

  return (
    <div className="p-6 lg:p-8">
      {/* Back */}
      <Link to="/clients" className="inline-flex items-center gap-2 text-white/50 hover:text-white text-sm mb-6 transition-colors">
        ← Retour aux clients
      </Link>

      {/* Client header */}
      <div className="rounded-3xl p-6 mb-6 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        style={{ background: 'linear-gradient(135deg, rgba(34,65,154,0.3) 0%, rgba(67,150,112,0.3) 100%)', backdropFilter: 'blur(12px)' }}>
        <div>
          <h1 className="text-white text-2xl font-bold">{client.nom}</h1>
          <p className="text-white/60 text-sm mt-1">{client.email} · {client.telephone}</p>
          <div className="flex items-center gap-3 mt-2">
            <StatusBadge status={client.statut_paiement} />
            {client.relance_flag && (
              <span className="text-xs px-2 py-0.5 rounded-full text-yellow-300"
                style={{ background: 'rgba(251,191,36,0.15)' }}>
                🔔 Relance active
              </span>
            )}
          </div>
        </div>
        <div className="flex gap-2 flex-wrap">
          <button onClick={() => setShowRelanceModal(true)}
            className="px-3 py-2 rounded-xl text-white/70 hover:text-white text-sm border border-white/20 hover:border-white/40 transition-all">
            🔔 Relance
          </button>
          <button onClick={exportPdf}
            className="px-3 py-2 rounded-xl text-white/70 hover:text-white text-sm border border-white/20 hover:border-white/40 transition-all">
            📄 PDF
          </button>
        </div>
      </div>

      {/* Info grid */}
      <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        {[
          { label: 'Date maintenance', value: new Date(client.date_maintenance).toLocaleDateString('fr-FR') },
          { label: 'Nb licences', value: client.licences_count },
          { label: 'Paiements', value: payments.length },
          { label: 'Licences assignées', value: licenses.length },
        ].map((item) => (
          <div key={item.label} className="rounded-2xl p-4 border border-white/10" style={cardStyle}>
            <p className="text-white/50 text-xs">{item.label}</p>
            <p className="text-white font-bold text-xl mt-1">{item.value}</p>
          </div>
        ))}
      </div>

      {/* Tabs */}
      <div className="flex gap-2 mb-4">
        {(['payments', 'licenses'] as Tab[]).map((t) => (
          <button key={t} onClick={() => setTab(t)}
            className={`px-4 py-2 rounded-xl text-sm font-medium transition-all ${tab === t ? 'text-white' : 'text-white/50 hover:text-white'}`}
            style={tab === t ? { background: 'linear-gradient(135deg, rgba(34,65,154,0.5) 0%, rgba(67,150,112,0.5) 100%)', border: '1px solid rgba(255,255,255,0.1)' } : {}}>
            {t === 'payments' ? `💳 Paiements (${payments.length})` : `🔑 Licences (${licenses.length})`}
          </button>
        ))}
      </div>

      {/* Payments tab */}
      {tab === 'payments' && (
        <div className="rounded-3xl border border-white/10 overflow-hidden" style={cardStyle}>
          <div className="flex items-center justify-between px-4 py-3 border-b border-white/10">
            <p className="text-white/70 text-sm font-medium">Historique des paiements</p>
            <button onClick={() => { setEditPayment(undefined); setShowPaymentModal(true) }}
              className="px-3 py-1.5 rounded-xl text-white text-xs font-semibold transition-all hover:scale-[1.02]"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              + Ajouter
            </button>
          </div>
          <table className="w-full text-sm">
            <thead>
              <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                {['Date', 'Montant', 'Statut', 'Actions'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-white/50 font-medium text-xs uppercase tracking-wide">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {payments.length === 0 && (
                <tr><td colSpan={4} className="px-4 py-8 text-center text-white/40">Aucun paiement.</td></tr>
              )}
              {payments.map((p) => (
                <tr key={p.id} className="hover:bg-white/5 transition-colors" style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td className="px-4 py-3 text-white/70">{new Date(p.date_payment).toLocaleDateString('fr-FR')}</td>
                  <td className="px-4 py-3 text-white font-medium">{Number(p.montant).toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €</td>
                  <td className="px-4 py-3"><StatusBadge status={p.status_payment} /></td>
                  <td className="px-4 py-3">
                    <div className="flex gap-2">
                      <button onClick={() => { setEditPayment(p); setShowPaymentModal(true) }}
                        className="px-2.5 py-1 rounded-lg text-white/60 hover:text-white text-xs border border-white/15 hover:border-white/30 transition-all">✏️</button>
                      <button onClick={() => deletePayment(p.id)}
                        className="px-2.5 py-1 rounded-lg text-red-400/70 hover:text-red-400 text-xs border border-red-400/20 hover:border-red-400/40 transition-all">🗑️</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Licenses tab */}
      {tab === 'licenses' && (
        <div className="rounded-3xl border border-white/10 overflow-hidden" style={cardStyle}>
          <div className="flex items-center justify-between px-4 py-3 border-b border-white/10">
            <p className="text-white/70 text-sm font-medium">Licences assignées</p>
            <button onClick={() => setShowLicenseModal(true)}
              className="px-3 py-1.5 rounded-xl text-white text-xs font-semibold transition-all hover:scale-[1.02]"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              + Assigner
            </button>
          </div>
          <table className="w-full text-sm">
            <thead>
              <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                {['Nom', 'Quantité', 'Date assignation', 'Actions'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-white/50 font-medium text-xs uppercase tracking-wide">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {licenses.length === 0 && (
                <tr><td colSpan={4} className="px-4 py-8 text-center text-white/40">Aucune licence.</td></tr>
              )}
              {licenses.map((l) => (
                <tr key={l.id} className="hover:bg-white/5 transition-colors" style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td className="px-4 py-3 text-white font-medium">{l.nom}</td>
                  <td className="px-4 py-3 text-white/70">{l.quantite_disponible}</td>
                  <td className="px-4 py-3 text-white/70">{new Date(l.date_assignation).toLocaleDateString('fr-FR')}</td>
                  <td className="px-4 py-3">
                    <button onClick={() => deleteLicense(l.id)}
                      className="px-2.5 py-1 rounded-lg text-red-400/70 hover:text-red-400 text-xs border border-red-400/20 hover:border-red-400/40 transition-all">🗑️</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Modals */}
      {showPaymentModal && (
        <PaymentModal clientId={client.id} payment={editPayment} onClose={() => setShowPaymentModal(false)} onSaved={fetchClient} />
      )}
      {showLicenseModal && (
        <LicenseAssignModal clientId={client.id} onClose={() => setShowLicenseModal(false)} onSaved={fetchClient} />
      )}
      {showRelanceModal && (
        <RelanceModal client={client} onClose={() => setShowRelanceModal(false)} onSaved={fetchClient} />
      )}
    </div>
  )
}
