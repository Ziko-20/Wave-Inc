import { useEffect, useState } from 'react'
import api from '@/lib/axios'
import type { Manager } from '@/types'

const cardStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.05)',
  backdropFilter: 'blur(12px)',
}
const inputStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.08)',
  border: '1px solid rgba(255,255,255,0.15)',
}

export default function ManagersPage() {
  const [managers, setManagers] = useState<Manager[]>([])
  const [loading, setLoading]   = useState(true)
  const [showForm, setShowForm] = useState(false)
  const [form, setForm]         = useState({ name: '', email: '', password: '', password_confirmation: '' })
  const [errors, setErrors]     = useState<Record<string, string>>({})
  const [saving, setSaving]     = useState(false)

  const fetchManagers = () => {
    setLoading(true)
    api.get('/managers').then(({ data }) => setManagers(data.data)).finally(() => setLoading(false))
  }

  useEffect(() => { fetchManagers() }, [])

  const handleAdd = async (e: React.FormEvent) => {
    e.preventDefault()
    setSaving(true)
    setErrors({})
    try {
      await api.post('/managers', form)
      setForm({ name: '', email: '', password: '', password_confirmation: '' })
      setShowForm(false)
      fetchManagers()
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

  const handleDelete = async (id: number) => {
    if (!confirm('Supprimer ce manager ?')) return
    await api.delete(`/managers/${id}`)
    fetchManagers()
  }

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-white text-2xl font-bold">Managers</h1>
          <p className="text-white/50 text-sm mt-0.5">{managers.length} manager{managers.length > 1 ? 's' : ''}</p>
        </div>
        <button onClick={() => setShowForm(!showForm)}
          className="px-4 py-2 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02]"
          style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
          {showForm ? '✕ Annuler' : '+ Ajouter un manager'}
        </button>
      </div>

      {/* Add form */}
      {showForm && (
        <div className="rounded-3xl border border-white/10 p-6 mb-6" style={cardStyle}>
          <h2 className="text-white font-semibold mb-4">Nouveau manager</h2>
          <form onSubmit={handleAdd} className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {[
              { key: 'name', label: 'Nom', type: 'text' },
              { key: 'email', label: 'Email', type: 'email' },
              { key: 'password', label: 'Mot de passe', type: 'password' },
              { key: 'password_confirmation', label: 'Confirmer le mot de passe', type: 'password' },
            ].map(({ key, label, type }) => (
              <div key={key}>
                <label className="block text-white/70 text-sm mb-1">{label}</label>
                <input type={type} value={(form as any)[key]}
                  onChange={(e) => setForm({ ...form, [key]: e.target.value })}
                  className="w-full px-3 py-2.5 rounded-xl text-white outline-none text-sm" style={inputStyle} />
                {errors[key] && <p className="text-red-400 text-xs mt-1">{errors[key]}</p>}
              </div>
            ))}
            <div className="sm:col-span-2 flex justify-end">
              <button type="submit" disabled={saving}
                className="px-6 py-2.5 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02] disabled:opacity-60"
                style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
                {saving ? 'Ajout...' : 'Ajouter'}
              </button>
            </div>
          </form>
        </div>
      )}

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
                {['Nom', 'Email', 'Créé le', 'Actions'].map((h) => (
                  <th key={h} className="px-4 py-3 text-left text-white/50 font-medium text-xs uppercase tracking-wide">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {managers.length === 0 && (
                <tr><td colSpan={4} className="px-4 py-10 text-center text-white/40">Aucun manager.</td></tr>
              )}
              {managers.map((m) => (
                <tr key={m.id} className="hover:bg-white/5 transition-colors" style={{ borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                  <td className="px-4 py-3 text-white font-medium">{m.name}</td>
                  <td className="px-4 py-3 text-white/70">{m.email}</td>
                  <td className="px-4 py-3 text-white/70">{new Date(m.created_at).toLocaleDateString('fr-FR')}</td>
                  <td className="px-4 py-3">
                    <button onClick={() => handleDelete(m.id)}
                      className="px-2.5 py-1 rounded-lg text-red-400/70 hover:text-red-400 text-xs border border-red-400/20 hover:border-red-400/40 transition-all">
                      🗑️ Supprimer
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  )
}
