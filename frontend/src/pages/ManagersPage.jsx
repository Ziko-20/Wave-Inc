import { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { ShieldCheck, Plus, Trash2, X, UserPlus } from 'lucide-react'
import api from '@/lib/axios'

const S = {
  card:  { background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '1rem', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' },
  input: { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '0.625rem', color: '#1e293b' },
  grad:  'linear-gradient(135deg, #22419A 0%, #439670 100%)',
}

export default function ManagersPage() {
  const [managers, setManagers] = useState([])
  const [loading, setLoading]   = useState(true)
  const [showForm, setShowForm] = useState(false)
  const [form, setForm]         = useState({ name: '', email: '', password: '', password_confirmation: '' })
  const [errors, setErrors]     = useState({})
  const [saving, setSaving]     = useState(false)

  const fetchManagers = () => {
    setLoading(true)
    api.get('/managers').then(({ data }) => setManagers(data.data)).finally(() => setLoading(false))
  }
  useEffect(() => { fetchManagers() }, [])

  const handleAdd = async (e) => {
    e.preventDefault(); setSaving(true); setErrors({})
    try {
      await api.post('/managers', form)
      setForm({ name: '', email: '', password: '', password_confirmation: '' })
      setShowForm(false); fetchManagers()
    } catch (err) {
      if (err.response?.status === 422) {
        const flat = {}
        Object.entries(err.response.data.errors ?? {}).forEach(([k, v]) => { flat[k] = v[0] })
        setErrors(flat)
      }
    } finally { setSaving(false) }
  }

  const handleDelete = async (id) => {
    if (!confirm('Supprimer ce manager ?')) return
    await api.delete(`/managers/${id}`); fetchManagers()
  }

  return (
    <div className="p-6 lg:p-8">
      <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }}
        className="rounded-2xl p-6 mb-6 text-white flex items-center justify-between flex-wrap gap-4"
        style={{ background: S.grad }}>
        <div>
          <h1 className="text-2xl font-bold">Managers</h1>
          <p className="text-white/70 text-sm mt-1">{managers.length} manager{managers.length > 1 ? 's' : ''}</p>
        </div>
        <motion.button whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}
          onClick={() => setShowForm(!showForm)}
          className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold"
          style={{ background: 'rgba(255,255,255,0.2)', color: '#ffffff' }}>
          {showForm ? <><X size={15} /> Annuler</> : <><UserPlus size={15} /> Ajouter un manager</>}
        </motion.button>
      </motion.div>

      <AnimatePresence>
        {showForm && (
          <motion.div initial={{ opacity: 0, height: 0 }} animate={{ opacity: 1, height: 'auto' }} exit={{ opacity: 0, height: 0 }}
            className="rounded-2xl p-6 mb-6 overflow-hidden" style={S.card}>
            <h2 className="font-semibold mb-4 flex items-center gap-2" style={{ color: '#1e293b' }}>
              <Plus size={16} color="#22419A" /> Nouveau manager
            </h2>
            <form onSubmit={handleAdd} className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {[['name','Nom','text'],['email','Email','email'],['password','Mot de passe','password'],['password_confirmation','Confirmer','password']].map(([k,l,t]) => (
                <div key={k}>
                  <label className="block text-xs font-medium mb-1" style={{ color: '#374151' }}>{l}</label>
                  <input type={t} value={form[k]} onChange={(e) => setForm({ ...form, [k]: e.target.value })}
                    className="w-full px-3 py-2 rounded-xl text-sm outline-none" style={S.input} />
                  {errors[k] && <p className="text-xs mt-0.5" style={{ color: '#dc2626' }}>{errors[k]}</p>}
                </div>
              ))}
              <div className="sm:col-span-2 flex justify-end">
                <motion.button type="submit" disabled={saving} whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
                  className="flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold disabled:opacity-60"
                  style={{ background: S.grad }}>
                  <ShieldCheck size={15} /> {saving ? 'Ajout...' : 'Ajouter'}
                </motion.button>
              </div>
            </form>
          </motion.div>
        )}
      </AnimatePresence>

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
                {['Nom','Email','Créé le','Actions'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style={{ color: '#64748b' }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {managers.length === 0 && <tr><td colSpan={4} className="px-4 py-10 text-center text-sm" style={{ color: '#94a3b8' }}>Aucun manager.</td></tr>}
              {managers.map((m, i) => (
                <motion.tr key={m.id} initial={{ opacity: 0, x: -8 }} animate={{ opacity: 1, x: 0 }} transition={{ delay: i * 0.04 }}
                  className="hover:bg-slate-50/60 transition-colors" style={{ borderBottom: '1px solid #f1f5f9' }}>
                  <td className="px-4 py-3 font-medium" style={{ color: '#1e293b' }}>{m.name}</td>
                  <td className="px-4 py-3" style={{ color: '#475569' }}>{m.email}</td>
                  <td className="px-4 py-3" style={{ color: '#475569' }}>{new Date(m.created_at).toLocaleDateString('fr-FR')}</td>
                  <td className="px-4 py-3">
                    <motion.button whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.9 }}
                      onClick={() => handleDelete(m.id)}
                      className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium hover:bg-red-50 transition-colors"
                      style={{ color: '#dc2626', border: '1px solid #fecaca' }}>
                      <Trash2 size={13} /> Supprimer
                    </motion.button>
                  </td>
                </motion.tr>
              ))}
            </tbody>
          </table>
        )}
      </motion.div>
    </div>
  )
}
