import { motion } from 'framer-motion'

export default function StatCard({ icon: Icon, label, value, sub, accent }) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 16 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.3 }}
      className="rounded-2xl p-5 flex items-center gap-4"
      style={{
        background: accent ? 'linear-gradient(135deg, #22419A 0%, #439670 100%)' : '#ffffff',
        border: accent ? 'none' : '1px solid #e2e8f0',
        boxShadow: '0 1px 3px rgba(0,0,0,0.06)',
      }}
    >
      <div className="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
        style={{ background: accent ? 'rgba(255,255,255,0.2)' : '#f0f4ff' }}>
        <Icon size={20} color={accent ? '#ffffff' : '#22419A'} strokeWidth={2} />
      </div>
      <div>
        <p className="text-sm" style={{ color: accent ? 'rgba(255,255,255,0.8)' : '#64748b' }}>{label}</p>
        <p className="text-2xl font-bold leading-tight" style={{ color: accent ? '#ffffff' : '#1e293b' }}>{value}</p>
        {sub && <p className="text-xs mt-0.5" style={{ color: accent ? 'rgba(255,255,255,0.6)' : '#94a3b8' }}>{sub}</p>}
      </div>
    </motion.div>
  )
}
