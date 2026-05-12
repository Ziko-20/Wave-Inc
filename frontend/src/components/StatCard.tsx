interface StatCardProps {
  icon: string
  label: string
  value: string | number
  sub?: string
  gradient?: boolean
}

export default function StatCard({ icon, label, value, sub, gradient }: StatCardProps) {
  return (
    <div
      className="rounded-3xl p-6 border border-white/10 flex items-center gap-4"
      style={{
        background: gradient
          ? 'linear-gradient(135deg, rgba(34,65,154,0.3) 0%, rgba(67,150,112,0.3) 100%)'
          : 'rgba(255,255,255,0.05)',
        backdropFilter: 'blur(12px)',
      }}
    >
      <div
        className="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0"
        style={{ background: 'rgba(255,255,255,0.08)' }}
      >
        {icon}
      </div>
      <div>
        <p className="text-white/60 text-sm">{label}</p>
        <p className="text-white text-2xl font-bold leading-tight">{value}</p>
        {sub && <p className="text-white/40 text-xs mt-0.5">{sub}</p>}
      </div>
    </div>
  )
}
