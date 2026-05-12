export type PaymentStatus = 'payé' | 'en_attente' | 'en_retard'

export interface Client {
  id: number
  nom: string
  email: string
  telephone: string
  statut_paiement: PaymentStatus
  date_maintenance: string
  licences_count: number
  relance_flag: boolean
  date_relance: string | null
  note_relance: string | null
  user_id: number | null
  created_at: string
  updated_at: string
  payments?: Payment[]
  license?: License[]
}

export interface Payment {
  id: number
  montant: number
  date_payment: string
  status_payment: PaymentStatus
  client_id: number
  created_at: string
  updated_at: string
}

export interface License {
  id: number
  nom: string
  quantite_disponible: number
  client_id: number
  date_assignation: string
  created_at: string
  updated_at: string
}

export interface LicenseOffer {
  id: number
  nom: string
  description: string | null
  prix: number
  quantite_disponible: number
}

export interface Manager {
  id: number
  name: string
  email: string
  created_at: string
}

export interface Stats {
  mrr: number
  total_clients: number
  paid: number
  pending: number
  late: number
}

export interface RevenueData {
  year: number
  monthly_revenue: number[]
  available_years: number[]
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}
