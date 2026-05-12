import { useEffect, useState, useCallback } from 'react'
import { Link } from 'react-router-dom'
import api from '@/lib/axios'
import StatusBadge from '@/components/StatusBadge'
import type { Client, PaginatedResponse, PaymentStatus } from '@/types'

const STATUS_OPTIONS: { value: string; label: string }[] = [
  { value: 'all',        label: 'Tous les statuts' },
  { value: 'payé',       label: 'Payé' },
  { value: 'en_attente', label: 'En attente' },
  { value: 'en_retard',  label: 'En retard' },
]

const inputStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.08)',
  border: '1px solid rgba(255,255,255,0.15)',
}

const cardStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.05)',
  backdropFilter: 'blur(12px)',
}

/* ─── Client Form Modal ─────────────────────────────────────────────────────── */
interface ClientFormProps {
  initial?: Partial<Client>
  onClose: () => void
  onSaved: () => void
}

function ClientFormModal({ initial, onClose, onSaved }: ClientFormProps) {
  const isEdit = !!initial?.id
  const [form, setForm] = useState({
    nom:              initial?.nom              ?? '',
    email:            initial?.email            ?? '',
    telephone:        initial?.telephone        ?? '',
    statut_paiement:  initial?.statut_paiement  ?? 'en_attente',
    date_maintenance: initial?.date_maintenance ?? '',
    licences_count:   initial?.licences_count   ?? 0,
  })
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [saving, setSaving] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSaving(true)
    setErrors({})
    try {
      if (isEdit) {
        await api.put(`/clients/${initial!.id}`, form)
      } else {
        await api.post('/clients', form)
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

  const field = (key: keyof typeof form, label: string, type = 'text', extra?: React.InputHTMLAttributes<HTMLInputElement>) => (
    <div>
      <label className="block text-white/70 text-sm mb-1">{label}</label>
      <input
        type={type}
        value={form[key] as string}
        onChange={(e) => setForm({ ...form, [key]: type === 'number' ? Number(e.target.value) : e.target.value })}
        className="w-full px-3 py-2.5 rounded-xl text-white placeholder-white/30 outline-none text-sm"
        style={inputStyle}
        {...extra}
      />
      {errors[key] && <p className="text-red-400 text-xs mt-1">{errors[key]}</p>}
    </div>
  )

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(4px)' }}>
      <div className="w-full max-w-lg rounded-3xl border border-white/10 p-6" style={cardStyle}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="text-white font-bold text-lg">{isEdit ? 'Modifier le client' : 'Nouveau client'}</h2>
          <button onClick={onClose} className="text-white/50 hover:text-white text-xl">✕</button>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            {field('nom', 'Nom')}
            {field('email', 'Email', 'email')}
            {field('telephone', 'Téléphone')}
            {field('date_maintenance', 'Date maintenance', 'date')}
            {field('licences_count', 'Nb licences', 'number', { min: 0 })}
          </div>

          <div>
            <label className="block text-white/70 text-sm mb-1">Statut paiement</label>
            <select
              value={form.statut_paiement}
              onChange={(e) => setForm({ ...form, statut_paiement: e.target.value as PaymentStatus })}
              className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm"
              style={inputStyle}
            >
              <option value="en_attente">En attente</option>
              <option value="payé">Payé</option>
              <option value="en_retard">En retard</option>
            </select>
          </div>

          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose}
              className="flex-1 py-2.5 rounded-xl text-white/70 hover:text-white text-sm border border-white/20 hover:border-white/40 transition-all">
              Annuler
            </button>
            <button type="submit" disabled={saving}
              className="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02] disabled:opacity-60"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              {saving ? 'Enregistrement...' : isEdit ? 'Modifier' : 'Créer'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

/* ─── Delete Confirm Modal ──────────────────────────────────────────────────── */
function DeleteModal({ client, onClose, onDeleted }: { client: Client; onClose: () => void; onDeleted: () => void }) {
  const [deleting, setDeleting] = useState(false)

  const confirm = async () => {
    setDeleting(true)
    await api.delete(`/clients/${client.id}`)
    onDeleted()
    onClose()
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(4px)' }}>
      <div className="w-full max-w-sm rounded-3xl border border-white/10 p-6" style={cardStyle}>
        <h2 className="text-white font-bold text-lg mb-2">Supprimer le client</h2>
        <p className="text-white/60 text-sm mb-6">
          Êtes-vous sûr de vouloir supprimer <strong className="text-white">{client.nom}</strong> ? Cette action est irréversible.
        </p>
        <div className="flex gap-3">
          <button onClick={onClose} className="flex-1 py-2.5 rounded-xl text-white/70 text-sm border border-white/20 hover:border-white/40 transition-all">
            Annuler
          </button>
          <button onClick={confirm} disabled={deleting}
            className="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition-all disabled:opacity-60"
            style={{ background: 'rgba(220,38,38,0.7)' }}>
            {deleting ? 'Suppression...' : 'Supprimer'}
          </button>
        </div>
      </div>
    </div>
  )
}

/* ─── Main Page ─────────────────────────────────────────────────────────────── */
export default function ClientsPage() {
  const [data, setData]         = useState<PaginatedResponse<Client> | null>(null)
  const [loading, setLoading]   = useState(true)
  const [search, setSearch]     = useState('')
  const [status, setStatus]     = useState('all')
  const [page, setPage]         = useState(1)
  const [showForm, setShowForm] = useState(false)
  const [editClient, setEditClient]   = useState<Client | undefined>()
  const [deleteClient, setDeleteClient] = useState<Client | undefined>()

  const fetchClients = useCallback(() => {
    setLoading(true)
    const params: Record<string, string | number> = { page }
    if (search) params.search = search
    if (status !== 'all') params.status = status

    api.get('/clients', { params })
      .then(({ data }) => setData(data.data))
      .finally(() => setLoading(false))
  }, [search, status, page])

  useEffect(() => { fetchClients() }, [fetchClients])

  // Debounce search
  useEffect(() => { setPage(1) }, [search, status])

  const handleExportCsv = () => {
    const token = sessionStorage.getItem('auth_token')
    window.open(`http://localhost:8000/api/clients/export/csv?token=${token}`, '_blank')
  }

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-white text-2xl font-bold">Clients</h1>
          <p className="text-white/50 text-sm mt-0.5">
            {data ? `${data.total} client${data.total > 1 ? 's' : ''}` : '—'}
          </p>
        </div>
        <div className="flex gap-3">
          <button onClick={handleExportCsv}
            className="px-4 py-2 rounded-xl text-white/70 hover:text-white text-sm border border-white/20 hover:border-white/40 transition-all">
            📥 CSV
          </button>
          <button onClick={() => { setEditClient(undefined); setShowForm(true) }}
            className="px-4 py-2 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02]"
            style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
            + Nouveau client
          </button>
        </div>
      </div>

      {/* Filters */}
      <div className="flex flex-col sm:flex-row gap-3 mb-5">
        <input
          type="text"
          placeholder="Rechercher par nom..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="flex-1 px-4 py-2.5 rounded-xl text-white placeholder-white/30 outline-none text-sm"
          style={inputStyle}
        />
        <select
          value={status}
          onChange={(e) => setStatus(e.target.value)}
          className="px-4 py-2.5 rounded-xl text-white outline-none text-sm"
          style={inputStyle}
        >
          {STATUS_OPTIONS.map((o) => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
      </div>

      {/* Table */}
      <div className="rounded-3xl border border-white/10 overflow-hidden" style={cardStyle}>
        {loading ? (
          <div className="flex items-center justify-center h-40">
            <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
          </div>
        ) : (
          <table className="w-full text-sm">
            <thead>
              <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                {['Nom', 'Email', 'Téléphone', 'Statut', 'Maintenance', 'Licences', 'Actions'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-white/50 font-medium text-xs uppercase tracking-wide">
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {data?.data.length === 0 && (
                <tr>
                  <td colSpan={7} className="px-4 py-10 text-center text-white/40">
                    Aucun client trouvé.
                  </td>
                </tr>
              )}
              {data?.data.map((client) => (
                <tr key={client.id}
                  className="transition-colors hover:bg-white/5"
                  style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td className="px-4 py-3 text-white font-medium">{client.nom}</td>
                  <td className="px-4 py-3 text-white/70">{client.email}</td>
                  <td className="px-4 py-3 text-white/70">{client.telephone}</td>
                  <td className="px-4 py-3"><StatusBadge status={client.statut_paiement} /></td>
                  <td className="px-4 py-3 text-white/70">
                    {new Date(client.date_maintenance).toLocaleDateString('fr-FR')}
                  </td>
                  <td className="px-4 py-3 text-white/70">{client.licences_count}</td>
                  <td className="px-4 py-3">
                    <div className="flex items-center gap-2">
                      <Link to={`/clients/${client.id}`}
                        className="px-2.5 py-1 rounded-lg text-white/60 hover:text-white text-xs border border-white/15 hover:border-white/30 transition-all">
                        Détails
                      </Link>
                      <button onClick={() => { setEditClient(client); setShowForm(true) }}
                        className="px-2.5 py-1 rounded-lg text-white/60 hover:text-white text-xs border border-white/15 hover:border-white/30 transition-all">
                        ✏️
                      </button>
                      <button onClick={() => setDeleteClient(client)}
                        className="px-2.5 py-1 rounded-lg text-red-400/70 hover:text-red-400 text-xs border border-red-400/20 hover:border-red-400/40 transition-all">
                        🗑️
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}

        {/* Pagination */}
        {data && data.last_page > 1 && (
          <div className="flex items-center justify-between px-4 py-3 border-t border-white/10">
            <p className="text-white/40 text-xs">
              {data.from}–{data.to} sur {data.total}
            </p>
            <div className="flex gap-2">
              <button disabled={page <= 1} onClick={() => setPage(page - 1)}
                className="px-3 py-1.5 rounded-lg text-white/60 hover:text-white text-xs border border-white/15 disabled:opacity-30 transition-all">
                ← Préc.
              </button>
              <span className="px-3 py-1.5 text-white/60 text-xs">
                {page} / {data.last_page}
              </span>
              <button disabled={page >= data.last_page} onClick={() => setPage(page + 1)}
                className="px-3 py-1.5 rounded-lg text-white/60 hover:text-white text-xs border border-white/15 disabled:opacity-30 transition-all">
                Suiv. →
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Modals */}
      {showForm && (
        <ClientFormModal
          initial={editClient}
          onClose={() => setShowForm(false)}
          onSaved={fetchClients}
        />
      )}
      {deleteClient && (
        <DeleteModal
          client={deleteClient}
          onClose={() => setDeleteClient(undefined)}
          onDeleted={fetchClients}
        />
      )}
    </div>
  )
}
