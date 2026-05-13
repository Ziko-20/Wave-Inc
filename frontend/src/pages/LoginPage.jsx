import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { Mail, Lock, AlertCircle } from 'lucide-react'
import { useAuthStore } from '@/store/authStore'
import appLogo from '@/img/appLogo.jpeg'

export default function LoginPage() {
  const [email, setEmail]       = useState('')
  const [password, setPassword] = useState('')
  const [error, setError]       = useState(null)
  const { login, isLoading }    = useAuthStore()
  const navigate                = useNavigate()

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError(null)
    try {
      await login(email, password)
      const { user } = useAuthStore.getState()
      navigate(user?.roles.includes('client') ? '/mon-espace' : '/dashboard')
    } catch (err) {
      setError(
        err?.response?.data?.errors?.email?.[0]
        ?? err?.response?.data?.message
        ?? 'Identifiants incorrects.'
      )
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center p-4" style={{ background: '#f4f6fb' }}>
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-32 -left-32 w-80 h-80 rounded-full opacity-10 blur-3xl" style={{ background: '#22419A' }} />
        <div className="absolute -bottom-32 -right-32 w-80 h-80 rounded-full opacity-10 blur-3xl" style={{ background: '#439670' }} />
      </div>

      <motion.div
        initial={{ opacity: 0, y: 24 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.35 }}
        className="relative w-full max-w-md"
      >
        <div className="rounded-2xl p-8" style={{ background: '#ffffff', border: '1px solid #e2e8f0', boxShadow: '0 4px 24px rgba(0,0,0,0.07)' }}>

          <div className="text-center mb-8">
            <img src={appLogo} alt="Wavy" className="w-16 h-16 rounded-2xl object-cover mx-auto mb-4 shadow-sm" />
{/*             <h1 className="text-2xl font-bold" style={{ color: '#1e293b' }}></h1>
 */}            <p className="text-sm mt-1" style={{ color: '#64748b' }}>Connectez-vous à votre espace</p>
          </div>

          {error && (
            <motion.div initial={{ opacity: 0, scale: 0.97 }} animate={{ opacity: 1, scale: 1 }}
              className="mb-4 px-4 py-3 rounded-xl flex items-center gap-2 text-sm"
              style={{ background: '#fee2e2', color: '#dc2626', border: '1px solid #fecaca' }}>
              <AlertCircle size={15} strokeWidth={2} />
              {error}
            </motion.div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1.5" style={{ color: '#374151' }}>Email</label>
              <div className="relative">
                <Mail size={15} className="absolute left-3 top-1/2 -translate-y-1/2" style={{ color: '#94a3b8' }} />
                <input type="email" value={email} onChange={(e) => setEmail(e.target.value)}
                  required placeholder="admin@example.com"
                  className="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm outline-none"
                  style={{ background: '#f8fafc', border: '1px solid #e2e8f0', color: '#1e293b' }} />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium mb-1.5" style={{ color: '#374151' }}>Mot de passe</label>
              <div className="relative">
                <Lock size={15} className="absolute left-3 top-1/2 -translate-y-1/2" style={{ color: '#94a3b8' }} />
                <input type="password" value={password} onChange={(e) => setPassword(e.target.value)}
                  required placeholder="••••••••"
                  className="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm outline-none"
                  style={{ background: '#f8fafc', border: '1px solid #e2e8f0', color: '#1e293b' }} />
              </div>
            </div>

            <motion.button type="submit" disabled={isLoading}
              whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
              className="w-full py-2.5 rounded-xl text-white font-semibold text-sm mt-2 disabled:opacity-60 disabled:cursor-not-allowed"
              style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
              {isLoading ? 'Connexion...' : 'Se connecter'}
            </motion.button>
          </form>
        </div>
      </motion.div>
    </div>
  )
}
