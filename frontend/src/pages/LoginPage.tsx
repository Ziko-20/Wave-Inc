import { useState, type FormEvent } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '@/store/authStore'

export default function LoginPage() {
  const [email, setEmail]       = useState('')
  const [password, setPassword] = useState('')
  const [error, setError]       = useState<string | null>(null)
  const { login, isLoading, hasRole } = useAuthStore()
  const navigate = useNavigate()

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault()
    setError(null)
    try {
      await login(email, password)
      // Redirect based on role
      const { user } = useAuthStore.getState()
      if (user?.roles.includes('client')) {
        navigate('/mon-espace')
      } else {
        navigate('/dashboard')
      }
    } catch (err: any) {
      const msg = err?.response?.data?.errors?.email?.[0]
        ?? err?.response?.data?.message
        ?? 'Identifiants incorrects.'
      setError(msg)
    }
  }

  return (
    <div
      className="min-h-screen flex items-center justify-center p-4"
      style={{ backgroundColor: '#0d1b2a' }}
    >
      {/* Background blobs */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -left-40 w-96 h-96 rounded-full opacity-20 blur-3xl"
          style={{ background: 'radial-gradient(circle, #22419A, transparent)' }} />
        <div className="absolute -bottom-40 -right-40 w-96 h-96 rounded-full opacity-20 blur-3xl"
          style={{ background: 'radial-gradient(circle, #439670, transparent)' }} />
      </div>

      <div className="relative w-full max-w-md">
        {/* Card */}
        <div
          className="rounded-3xl border border-white/10 p-8"
          style={{ background: 'rgba(255,255,255,0.05)', backdropFilter: 'blur(12px)' }}
        >
          {/* Header */}
          <div className="text-center mb-8">
            <div
              className="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-4"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}
            >
              GC
            </div>
            <h1 className="text-white text-2xl font-bold">Connexion</h1>
            <p className="text-white/50 text-sm mt-1">GestionClients — Espace sécurisé</p>
          </div>

          {/* Error */}
          {error && (
            <div className="mb-4 px-4 py-3 rounded-xl text-sm text-red-300"
              style={{ background: 'rgba(220,38,38,0.15)', border: '1px solid rgba(220,38,38,0.3)' }}>
              {error}
            </div>
          )}

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-white/70 text-sm mb-1.5">Email</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                placeholder="admin@example.com"
                className="w-full px-4 py-3 rounded-xl text-white placeholder-white/30 outline-none transition-all focus:ring-2"
                style={{
                  background: 'rgba(255,255,255,0.08)',
                  border: '1px solid rgba(255,255,255,0.15)',
                  // @ts-ignore
                  '--tw-ring-color': '#22419A',
                }}
              />
            </div>

            <div>
              <label className="block text-white/70 text-sm mb-1.5">Mot de passe</label>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                placeholder="••••••••"
                className="w-full px-4 py-3 rounded-xl text-white placeholder-white/30 outline-none transition-all focus:ring-2"
                style={{
                  background: 'rgba(255,255,255,0.08)',
                  border: '1px solid rgba(255,255,255,0.15)',
                }}
              />
            </div>

            <button
              type="submit"
              disabled={isLoading}
              className="w-full py-3 rounded-xl text-white font-semibold text-sm transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed mt-2"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}
            >
              {isLoading ? 'Connexion...' : 'Se connecter'}
            </button>
          </form>
        </div>
      </div>
    </div>
  )
}
