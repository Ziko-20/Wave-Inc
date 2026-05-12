import { useEffect, useState } from 'react'
import api from '@/lib/axios'
import type { LicenseOffer } from '@/types'

const cardStyle: React.CSSProperties = {
  background: 'rgba(255,255,255,0.05)',
  backdropFilter: 'blur(12px)',
}

export default function BoutiquePage() {
  const [offers, setOffers]   = useState<LicenseOffer[]>([])
  const [loading, setLoading] = useState(true)
  const [buying, setBuying]   = useState<number | null>(null)
  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null)

  useEffect(() => {
    api.get('/license-offers')
      .then(({ data }) => setOffers(data.data))
      .finally(() => setLoading(false))
  }, [])

  const handleBuy = async (offer: LicenseOffer) => {
    setBuying(offer.id)
    setMessage(null)
    try {
      const { data } = await api.post('/boutique/checkout', { offer_id: offer.id })
      // Redirect to PayPal approval URL
      window.location.href = data.data.approval_url
    } catch (err: any) {
      setMessage({ type: 'error', text: err.response?.data?.message ?? 'Erreur lors du paiement.' })
    } finally {
      setBuying(null)
    }
  }

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <div className="rounded-3xl p-6 mb-8 border border-white/10"
        style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}>
        <h1 className="text-white text-2xl font-bold">Boutique de licences</h1>
        <p className="text-white/70 text-sm mt-1">Achetez des licences supplémentaires via PayPal</p>
      </div>

      {message && (
        <div className={`mb-6 px-4 py-3 rounded-xl text-sm ${message.type === 'success' ? 'text-green-300' : 'text-red-300'}`}
          style={{ background: message.type === 'success' ? 'rgba(5,150,105,0.15)' : 'rgba(220,38,38,0.15)', border: `1px solid ${message.type === 'success' ? 'rgba(5,150,105,0.3)' : 'rgba(220,38,38,0.3)'}` }}>
          {message.text}
        </div>
      )}

      {loading ? (
        <div className="flex items-center justify-center h-40">
          <div className="w-8 h-8 border-2 border-white/20 border-t-white rounded-full animate-spin" />
        </div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {offers.length === 0 && (
            <p className="text-white/40 col-span-3 text-center py-10">Aucune offre disponible pour le moment.</p>
          )}
          {offers.map((offer) => (
            <div key={offer.id} className="rounded-3xl border border-white/10 p-6 flex flex-col" style={cardStyle}>
              <div className="flex-1">
                <h2 className="text-white font-bold text-lg">{offer.nom}</h2>
                {offer.description && (
                  <p className="text-white/60 text-sm mt-2">{offer.description}</p>
                )}
                <div className="mt-4">
                  <span className="text-3xl font-bold text-white">{Number(offer.prix).toLocaleString('fr-FR', { minimumFractionDigits: 2 })} $</span>
                </div>
                <p className="text-white/40 text-xs mt-2">
                  {offer.quantite_disponible > 0
                    ? `${offer.quantite_disponible} disponible${offer.quantite_disponible > 1 ? 's' : ''}`
                    : 'Rupture de stock'}
                </p>
              </div>
              <button
                onClick={() => handleBuy(offer)}
                disabled={offer.quantite_disponible <= 0 || buying === offer.id}
                className="mt-6 w-full py-3 rounded-xl text-white font-semibold text-sm transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                style={{ background: 'linear-gradient(135deg, #22419A 0%, #439670 100%)' }}
              >
                {buying === offer.id ? 'Redirection...' : offer.quantite_disponible <= 0 ? 'Indisponible' : '🛒 Acheter via PayPal'}
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
