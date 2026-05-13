import { useEffect, useState, useCallback } from 'react'
import { Link } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { Plus, Download, Search, ChevronLeft, ChevronRight, Pencil, Trash2, Eye, X } from 'lucide-react'
import api from '@/lib/axios'
import StatusBadge from '@/components/StatusBadge'

const S = {
  card:  { background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '1rem', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' },
  input: { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '0.625rem', color: '#1e293b' },
  grad:  'linear-gradient(135deg, #22419A 0%, #439670 100%)',
}

/* ─── Client Form Modal ─────────────────────────────────────────────────────── */
function ClientFormModal({ initial, onClose, onSaved }) {
  const isEdit = !!initial?.id
  const [form, setForm] = useState({
    nom: initial?.nom ?? '',
    email: initial?.email ?? '',
    telephone: initial?.telephone ?? '',
    statut_paiement: initial?.statut_paiement ?? 'en_attente',
    date_maintenance: initial?.date_maintenance ?? '',
    licences_count: initial?.licences_count ?? 0,
  })
  const [errors, setErrors] = useState({})
  const [saving, setSaving] = useState(false)

  const handleSubmit = async (e) => {
    e.preventDefault(); setSaving(true); setErrors({})
    try {
      isEdit ? await api.put(`/clients/${initial.id}`, form) : await api.post('/clients', form)
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
        className="w-full max-w-lg rounded-2xl p-6" style={S.card}>
        <div className="flex items-center justify-between mb-5">
          <h2 className="font-bold text-lg" style={{ color: '#1e293b' }}>{isEdit ? 'Modifier le client' : 'Nouveau client'}</h2>
          <button onClick={onClose} className="p-1.5 rounded-lg hover:bg-slate-100 transition-colors"><X size={16} color="#64748b" /></button>
        </div>
        <form onSubmit={handleSubmit} className="space-y-3">
          <div className="grid grid-cols-2 gap-3">
            {[['nom','Nom'],['email','Email'],['telephone','Téléphone'],['date_maintenance','Date maintenance','date']].map(([k,l,t]) => (
              <div key={k}>
                <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>{l}</label>
                <input type={t ?? 'text'} value={form[k]}
                  onChange={(e) => setForm({ ...form, [k]: e.target.value })}
                  className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
                {errors[k] && <p className="text-xs mt-0.5" style={{ color: '#dc2626' }}>{errors[k]}</p>}
              </div>
            ))}
            <div>
              <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Nb licences</label>
              <input type="number" min={0} value={form.licences_count}
                onChange={(e) => setForm({ ...form, licences_count: Number(e.target.value) })}
                className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
            </div>
            <div>
              <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>Statut</label>
              <select value={form.statut_paiement}
                onChange={(e) => setForm({ ...form, statut_paiement: e.target.value })}
                className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input}>
                <option value="en_attente">En attente</option>
                <option value="payé">Payé</option>
                <option value="en_retard">En retard</option>
              </select>
            </div>
          </div>
          <div className="flex gap-3 pt-2">
            <button type="button" onClick={onClose}
              className="flex-1 py-2 rounded-xl text-sm font-medium transition-all hover:bg-slate-50"
              style={{ border: '1px solid #e2e8f0', color: '#64748b' }}>Annuler</button>
            <motion.button type="submit" disabled={saving} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
              className="flex-1 py-2 rounded-xl text-white text-sm font-semibold disabled:opacity-60"
              style={{ background: S.grad }}>
              {saving ? 'Enregistrement...' : isEdit ? 'Modifier' : 'Créer'}
            </motion.button>
          </div>
        </form>
      </motion.div>
    </div>
  )
}

/* ─── Delete Modal ──────────────────────────────────────────────────────────── */
function DeleteModal({ client, onClose, onDeleted }) {
  const [deleting, setDeleting] = useState(false)
  const confirm = async () => {
    setDeleting(true)
    await api.delete(`/clients/${client.id}`)
    onDeleted(); onClose()
  }
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4"
      style={{ background: 'rgba(15,23,42,0.4)', backdropFilter: 'blur(4px)' }}>
      <motion.div initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }}
        className="w-full max-w-sm rounded-2xl p-6" style={S.card}>
        <h2 className="font-bold text-lg mb-2" style={{ color: '#1e293b' }}>Supprimer le client</h2>
        <p className="text-sm mb-6" style={{ color: '#64748b' }}>
          Supprimer <strong style={{ color: '#1e293b' }}>{client.nom}</strong> ? Cette action est irréversible.
        </p>
        <div className="flex gap-3">
          <button onClick={onClose} className="flex-1 py-2 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all"
            style={{ border: '1px solid #e2e8f0', color: '#64748b' }}>Annuler</button>
          <motion.button onClick={confirm} disabled={deleting} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            className="flex-1 py-2 rounded-xl text-sm font-semibold disabled:opacity-60"
            style={{ background: '#fee2e2', color: '#dc2626', border: '1px solid #fecaca' }}>
            {deleting ? 'Suppression...' : 'Supprimer'}
          </motion.button>
        </div>
      </motion.div>
    </div>
  )
}

/* ─── Main Page ─────────────────────────────────────────────────────────────── */
export default function ClientsPage() {
  const [data, setData]         = useState(null)
  const [loading, setLoading]   = useState(true)
  const [search, setSearch]     = useState('')
  const [status, setStatus]     = useState('all')
  const [page, setPage]         = useState(1)
  const [showForm, setShowForm] = useState(false)
  const [editClient, setEditClient]     = useState(undefined)
  const [deleteClient, setDeleteClient] = useState(undefined)

  const fetchClients = useCallback(() => {
    setLoading(true)
    const params = { page }
    if (search) params.search = search
    if (status !== 'all') params.status = status
    api.get('/clients', { params }).then(({ data }) => setData(data.data)).finally(() => setLoading(false))
  }, [search, status, page])

  useEffect(() => { fetchClients() }, [fetchClients])
  useEffect(() => { setPage(1) }, [search, status])

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }}
        className="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div>
          <h1 className="text-2xl font-bold" style={{ color: '#1e293b' }}>Clients</h1>
          <p className="text-sm mt-0.5" style={{ color: '#64748b' }}>
            {data ? `${data.total} client${data.total > 1 ? 's' : ''}` : '—'}
          </p>
        </div>
        <div className="flex gap-2">
          <motion.button whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            onClick={() => window.open('http://localhost:8000/api/clients/export/csv', '_blank')}
            className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all hover:bg-slate-50"
            style={{ border: '1px solid #e2e8f0', color: '#475569' }}>
            <Download size={15} /> CSV
          </motion.button>
          <motion.button whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            onClick={() => { setEditClient(undefined); setShowForm(true) }}
            className="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold"
            style={{ background: S.grad }}>
            <Plus size={15} /> Nouveau client
          </motion.button>
        </div>
      </motion.div>

      {/* Filters */}
      <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.05 }}
        className="flex flex-col sm:flex-row gap-3 mb-5">
        <div className="relative flex-1">
          <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2" style={{ color: '#94a3b8' }} />
          <input type="text" placeholder="Rechercher par nom..." value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm outline-none"
            style={{ background: '#ffffff', border: '1px solid #e2e8f0', color: '#1e293b' }} />
        </div>
        <select value={status} onChange={(e) => setStatus(e.target.value)}
          className="px-4 py-2.5 rounded-xl text-sm outline-none"
          style={{ background: '#ffffff', border: '1px solid #e2e8f0', color: '#1e293b' }}>
          <option value="all">Tous les statuts</option>
          <option value="payé">Payé</option>
          <option value="en_attente">En attente</option>
          <option value="en_retard">En retard</option>
        </select>
      </motion.div>

      {/* Table */}
      <motion.div initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.1 }}
        className="rounded-2xl overflow-hidden" style={S.card}>
        {loading ? (
          <div className="flex items-center justify-center h-40">
            <div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" />
          </div>
        ) : (
          <table className="w-full text-sm">
            <thead>
              <tr style={{ borderBottom: '1px solid #f1f5f9', background: '#f8fafc' }}>
                {['Nom','Email','Téléphone','Statut','Maintenance','Licences','Actions'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style={{ color: '#64748b' }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {data?.data.length === 0 && (
                <tr><td colSpan={7} className="px-4 py-10 text-center text-sm" style={{ color: '#94a3b8' }}>Aucun client trouvé.</td></tr>
              )}
              {data?.data.map((client, i) => (
                <motion.tr key={client.id}
                  initial={{ opacity: 0, x: -8 }} animate={{ opacity: 1, x: 0 }} transition={{ delay: i * 0.03 }}
                  className="transition-colors hover:bg-slate-50/60"
                  style={{ borderBottom: '1px solid #f1f5f9' }}>
                  <td className="px-4 py-3 font-medium" style={{ color: '#1e293b' }}>{client.nom}</td>
                  <td className="px-4 py-3" style={{ color: '#475569' }}>{client.email}</td>
                  <td className="px-4 py-3" style={{ color: '#475569' }}>{client.telephone}</td>
                  <td className="px-4 py-3"><StatusBadge status={client.statut_paiement} /></td>
                  <td className="px-4 py-3" style={{ color: '#475569' }}>{new Date(client.date_maintenance).toLocaleDateString('fr-FR')}</td>
                  <td className="px-4 py-3" style={{ color: '#475569' }}>{client.licences_count}</td>
                  <td className="px-4 py-3">
                    <div className="flex items-center gap-1.5">
                      <Link to={`/clients/${client.id}`}>
                        <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                          className="p-1.5 rounded-lg hover:bg-blue-50 transition-colors" title="Détails">
                          <Eye size={14} color="#22419A" />
                        </motion.button>
                      </Link>
                      <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                        onClick={() => { setEditClient(client); setShowForm(true) }}
                        className="p-1.5 rounded-lg hover:bg-amber-50 transition-colors" title="Modifier">
                        <Pencil size={14} color="#d97706" />
                      </motion.button>
                      <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                        onClick={() => setDeleteClient(client)}
                        className="p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Supprimer">
                        <Trash2 size={14} color="#dc2626" />
                      </motion.button>
                    </div>
                  </td>
                </motion.tr>
              ))}
            </tbody>
          </table>
        )}

        {data && data.last_page > 1 && (
          <div className="flex items-center justify-between px-4 py-3" style={{ borderTop: '1px solid #f1f5f9' }}>
            <p className="text-xs" style={{ color: '#94a3b8' }}>{data.from}–{data.to} sur {data.total}</p>
            <div className="flex items-center gap-2">
              <motion.button whileHover={{ scale: 1.05 }} whileTap={{ scale: 0.95 }}
                disabled={page <= 1} onClick={() => setPage(page - 1)}
                className="p-1.5 rounded-lg disabled:opacity-30 hover:bg-slate-100 transition-colors"
                style={{ border: '1px solid #e2e8f0' }}>
                <ChevronLeft size={14} color="#475569" />
              </motion.button>
              <span className="text-xs px-2" style={{ color: '#64748b' }}>{page} / {data.last_page}</span>
              <motion.button whileHover={{ scale: 1.05 }} whileTap={{ scale: 0.95 }}
                disabled={page >= data.last_page} onClick={() => setPage(page + 1)}
                className="p-1.5 rounded-lg disabled:opacity-30 hover:bg-slate-100 transition-colors"
                style={{ border: '1px solid #e2e8f0' }}>
                <ChevronRight size={14} color="#475569" />
              </motion.button>
            </div>
          </div>
        )}
      </motion.div>

      <AnimatePresence>
        {showForm && <ClientFormModal initial={editClient} onClose={() => setShowForm(false)} onSaved={fetchClients} />}
        {deleteClient && <DeleteModal client={deleteClient} onClose={() => setDeleteClient(undefined)} onDeleted={fetchClients} />}
      </AnimatePresence>
    </div>
  )
}
