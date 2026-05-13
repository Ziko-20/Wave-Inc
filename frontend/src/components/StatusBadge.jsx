const config = {
  'payé':       { label: 'Payé',       bg: '#dcfce7', color: '#16a34a' },
  'en_attente': { label: 'En attente', bg: '#fef9c3', color: '#ca8a04' },
  'en_retard':  { label: 'En retard',  bg: '#fee2e2', color: '#dc2626' },
}

export default function StatusBadge({ status }) {
  const { label, bg, color } = config[status] ?? { label: status, bg: '#f1f5f9', color: '#64748b' }
  return (
    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
      style={{ background: bg, color }}>
      {label}
    </span>
  )
}
