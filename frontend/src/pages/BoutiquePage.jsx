import { useEffect, useState } from 'react'
import { motion } from 'framer-motion'
import {
  ShoppingCart, Package, CheckCircle, AlertCircle, Globe,
  TrendingUp, Zap, ShoppingBag, Layout, ShieldCheck,
} from 'lucide-react'
import api from '@/lib/axios'

const S = {
  card: { background: '#ffffff', border: '1px solid #e2e8f0', borderRadius: '1rem', boxShadow: '0 1px 3px rgba(0,0,0,0.05)' },
  grad: 'linear-gradient(135deg, #22419A 0%, #439670 100%)',
}

const container = { hidden: {}, show: { transition: { staggerChildren: 0.08 } } }
const cardAnim  = { hidden: { opacity: 0, y: 20 }, show: { opacity: 1, y: 0, transition: { duration: 0.3 } } }

const wpLicenses = [
  { category: 'SEO & Marketing',     items: ['Yoast SEO Premium', 'Rank Math Pro', 'SEOPress Pro'] },
  { category: 'Performance & Cache', items: ['WP Rocket', 'LiteSpeed Cache'] },
  { category: 'E-commerce',          items: ['WooCommerce', 'Extensions WooCommerce (checkout, shipping, subscriptions)'] },
  { category: 'Page Builders',       items: ['Elementor Pro', 'Divi Builder', 'WPBakery Page Builder'] },
  { category: 'Sécurité & Backup',   items: ['Wordfence Premium', 'UpdraftPlus Premium', 'iThemes Security Pro'] },
]

const itemPrices = {
  'Yoast SEO Premium': 490,
  'Rank Math Pro': 390,
  'SEOPress Pro': 320,
  'WP Rocket': 560,
  'LiteSpeed Cache': 280,
  'WooCommerce': 0,
  'Extensions WooCommerce (checkout, shipping, subscriptions)': 890,
  'Elementor Pro': 750,
  'Divi Builder': 690,
  'WPBakery Page Builder': 420,
  'Wordfence Premium': 380,
  'UpdraftPlus Premium': 290,
  'iThemes Security Pro': 350,
}

const categoryMeta = {
  'SEO & Marketing':      { icon: TrendingUp,  color: '#059669' },
  'Performance & Cache':  { icon: Zap,         color: '#d97706' },
  'E-commerce':           { icon: ShoppingBag, color: '#7c3aed' },
  'Page Builders':        { icon: Layout,      color: '#db2777' },
  'Sécurité & Backup':    { icon: ShieldCheck, color: '#22419A' },
}

export default function BoutiquePage() {
  const [offers, setOffers]       = useState([])
  const [loading, setLoading]     = useState(true)
  const [buying, setBuying]       = useState(null)
  const [message, setMessage]     = useState(null)
  const [activeTab, setActiveTab] = useState('licenses')

  useEffect(() => {
    api.get('/license-offers').then(({ data }) => setOffers(data.data)).finally(() => setLoading(false))
  }, [])

  const handleBuy = async (offer) => {
    setBuying(offer.id); setMessage(null)
    try {
      const { data } = await api.post('/boutique/checkout', { offer_id: offer.id })
      window.location.href = data.data.approval_url
    } catch (err) {
      setMessage({ type: 'error', text: err.response?.data?.message ?? 'Erreur lors du paiement.' })
    } finally { setBuying(null) }
  }

  return (
    <div className="p-6 lg:p-8">
      {/* Header */}
      <motion.div initial={{ opacity: 0, y: -10 }} animate={{ opacity: 1, y: 0 }}
        className="rounded-2xl p-6 mb-6 text-white" style={{ background: S.grad }}>
        <h1 className="text-2xl font-bold">Boutique</h1>
        <p className="text-white/70 text-sm mt-1">Licences logicielles et produits WordPress</p>
      </motion.div>

      {/* Message */}
      {message && (
        <motion.div initial={{ opacity: 0, scale: 0.97 }} animate={{ opacity: 1, scale: 1 }}
          className="mb-6 px-4 py-3 rounded-xl flex items-center gap-2 text-sm"
          style={{
            background: message.type === 'success' ? '#dcfce7' : '#fee2e2',
            color: message.type === 'success' ? '#16a34a' : '#dc2626',
            border: `1px solid ${message.type === 'success' ? '#bbf7d0' : '#fecaca'}`,
          }}>
          {message.type === 'success' ? <CheckCircle size={15} /> : <AlertCircle size={15} />}
          {message.text}
        </motion.div>
      )}

      {/* Tabs */}
      <div className="flex gap-2 mb-6">
        {[
          { key: 'licenses',  label: 'Licences',   icon: Package },
          { key: 'wordpress', label: 'WordPress',  icon: Globe },
        ].map(({ key, label, icon: Icon }) => (
          <motion.button key={key} onClick={() => setActiveTab(key)}
            whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
            className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all"
            style={activeTab === key
              ? { background: S.grad, color: '#ffffff' }
              : { background: '#ffffff', border: '1px solid #e2e8f0', color: '#475569' }}>
            <Icon size={15} /> {label}
          </motion.button>
        ))}
      </div>

      {/* ── Licenses tab ── */}
      {activeTab === 'licenses' && (
        loading ? (
          <div className="flex items-center justify-center h-40">
            <div className="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin" />
          </div>
        ) : (
          <motion.div variants={container} initial="hidden" animate="show"
            className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {offers.length === 0 && (
              <p className="col-span-3 text-center py-10 text-sm" style={{ color: '#94a3b8' }}>Aucune offre disponible.</p>
            )}
            {offers.map((offer) => (
              <motion.div key={offer.id} variants={cardAnim} whileHover={{ y: -3 }}
                className="rounded-2xl p-6 flex flex-col" style={S.card}>
                <div className="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style={{ background: '#f0f4ff' }}>
                  <Package size={20} color="#22419A" strokeWidth={2} />
                </div>
                <h2 className="font-bold text-base mb-1" style={{ color: '#1e293b' }}>{offer.nom}</h2>
                {offer.description && <p className="text-sm mb-4 flex-1" style={{ color: '#64748b' }}>{offer.description}</p>}
                <div className="mb-4">
                  <span className="text-3xl font-bold" style={{ color: '#1e293b' }}>
                    {Number(offer.prix).toLocaleString('fr-MA', { minimumFractionDigits: 2 })}
                  </span>
                  <span className="text-sm font-semibold ml-1" style={{ color: '#64748b' }}>DH</span>
                </div>
                <p className="text-xs mb-4" style={{ color: offer.quantite_disponible > 0 ? '#16a34a' : '#dc2626' }}>
                  {offer.quantite_disponible > 0
                    ? `${offer.quantite_disponible} disponible${offer.quantite_disponible > 1 ? 's' : ''}`
                    : 'Rupture de stock'}
                </p>
                <motion.button onClick={() => handleBuy(offer)}
                  disabled={offer.quantite_disponible <= 0 || buying === offer.id}
                  whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
                  className="w-full py-2.5 rounded-xl text-white text-sm font-semibold flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                  style={{ background: S.grad }}>
                  <ShoppingCart size={15} />
                  {buying === offer.id ? 'Redirection...' : offer.quantite_disponible <= 0 ? 'Indisponible' : 'Acheter via PayPal'}
                </motion.button>
              </motion.div>
            ))}
          </motion.div>
        )
      )}

      {/* ── WordPress tab ── */}
      {activeTab === 'wordpress' && (
        <div className="space-y-10">
          {wpLicenses.map(({ category, items }) => {
            const meta = categoryMeta[category] ?? { icon: Globe, color: '#22419A' }
            const Icon = meta.icon
            return (
              <div key={category}>
                {/* Category header */}
                <div className="flex items-center gap-3 mb-5">
                  <div className="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                    style={{ background: `${meta.color}15` }}>
                    <Icon size={18} color={meta.color} strokeWidth={2} />
                  </div>
                  <div>
                    <h2 className="text-base font-bold" style={{ color: '#1e293b' }}>{category}</h2>
                    <p className="text-xs" style={{ color: '#94a3b8' }}>{items.length} produit{items.length > 1 ? 's' : ''}</p>
                  </div>
                  <div className="flex-1 h-px ml-2" style={{ background: '#e2e8f0' }} />
                </div>

                {/* Product cards */}
                <motion.div variants={container} initial="hidden" animate="show"
                  className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                  {items.map((name) => {
                    const price = itemPrices[name] ?? 490
                    const isFree = price === 0
                    return (
                      <motion.div key={name} variants={cardAnim} whileHover={{ y: -3 }}
                        className="rounded-2xl p-6 flex flex-col" style={S.card}>
                        <div className="flex items-center gap-3 mb-4">
                          <div className="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                            style={{ background: `${meta.color}15` }}>
                            <Icon size={20} color={meta.color} strokeWidth={2} />
                          </div>
                          <span className="text-xs font-semibold px-2 py-0.5 rounded-full"
                            style={{ background: `${meta.color}12`, color: meta.color }}>
                            {category}
                          </span>
                        </div>
                        <h3 className="font-bold text-sm mb-4 flex-1 leading-snug" style={{ color: '#1e293b' }}>{name}</h3>
                        <div className="mb-4">
                          {isFree ? (
                            <span className="text-sm font-semibold px-2.5 py-1 rounded-lg"
                              style={{ background: '#dcfce7', color: '#16a34a' }}>Gratuit</span>
                          ) : (
                            <>
                              <span className="text-2xl font-bold" style={{ color: '#1e293b' }}>
                                {price.toLocaleString('fr-MA')}
                              </span>
                              <span className="text-sm font-semibold ml-1" style={{ color: '#64748b' }}>DH</span>
                              <span className="text-xs ml-1" style={{ color: '#94a3b8' }}>/an</span>
                            </>
                          )}
                        </div>
                        <motion.button whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}
                          onClick={() => setMessage({ type: 'error', text: `Contactez-nous pour acquérir "${name}".` })}
                          className="w-full py-2.5 rounded-xl text-white text-sm font-semibold flex items-center justify-center gap-2"
                          style={{ background: S.grad }}>
                          <ShoppingCart size={15} />
                          {isFree ? 'Télécharger' : 'Ajouter au panier'}
                        </motion.button>
                      </motion.div>
                    )
                  })}
                </motion.div>
              </div>
            )
          })}
        </div>
      )}
    </div>
  )
}
