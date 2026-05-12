import type { PaymentStatus } from '@/types'

const config: Record<PaymentStatus, { label: string; color: string }> = {
  'payé':       { label: 'Payé',       color: 'rgba(5,150,105,0.2)'  },
  'en_attente': { label: 'En attente', color: 'rgba(217,119,6,0.2)'  },
  'en_retard':  { label: 'En retard',  color: 'rgba(220,38,38,0.2)'  },
}

const textColor: Record<PaymentStatus, string> = {
  'payé':       '#34d399',
  'en_attente': '#fbbf24',
  'en_retard':  '#f87171',
}

export default function StatusBadge({ status }: { status: PaymentStatus }) {
  const { label, color } = config[status] ?? { label: status, color: 'rgba(255,255,255,0.1)' }
  return (
    <span
      className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
      style={{ background: color, color: textColor[status] }}
    >
      {label}
    </span>
  )
}
