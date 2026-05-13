# Wavy — GestionClients & Abonnements

Application de gestion de clients, paiements et licences — architecture découplée **Laravel REST API + React SPA**.

---

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Structure du projet](#structure-du-projet)
3. [Stack technique](#stack-technique)
4. [Démarrage rapide](#démarrage-rapide)
5. [Variables d'environnement](#variables-denvironnement)
6. [Base de données](#base-de-données)
7. [API Reference](#api-reference)
8. [Frontend — Pages & Routes](#frontend--pages--routes)
9. [Frontend — Composants](#frontend--composants)
10. [Authentification & Rôles](#authentification--rôles)
11. [Comptes de test](#comptes-de-test)
12. [Logique métier](#logique-métier)
13. [Exports](#exports)
14. [PayPal](#paypal)
15. [Design System](#design-system)

---

## Vue d'ensemble

Wavy est une application B2B permettant à une équipe (admin + managers) de gérer un portefeuille de clients, leurs paiements, leurs licences logicielles et leurs relances. Les clients ont accès à leur propre espace en lecture seule et peuvent acheter des licences via PayPal ou consulter des produits WordPress.

---

## Structure du projet

```
GestionClients-Abonnements/
│
├── backend/                          ← Laravel 12 REST API
│   ├── app/
│   │   ├── Http/Controllers/Api/     ← 10 contrôleurs API
│   │   ├── Http/Requests/Api/        ← Form Requests (validation)
│   │   ├── Models/                   ← Client, Payment, License, LicenseOffer, User
│   │   ├── Observers/PaymentObserver.php
│   │   └── Providers/AppServiceProvider.php
│   ├── config/
│   │   ├── cors.php                  ← CORS (localhost:5173)
│   │   ├── sanctum.php               ← Token Bearer auth
│   │   └── paypal.php
│   ├── database/
│   │   ├── migrations/               ← 10 migrations
│   │   └── seeders/
│   │       ├── UserSeeder.php        ← 3 comptes de test
│   │       ├── ClientSeeder.php
│   │       ├── HistoriqueDePaiments.php
│   │       ├── LicenseSeeder.php
│   │       └── roleseeder.php
│   ├── resources/views/pdf/          ← Templates PDF (DomPDF)
│   └── routes/api.php                ← 33 routes API
│
├── frontend/                         ← React 19 SPA
│   └── src/
│       ├── components/               ← AppLayout, Sidebar, StatCard, StatusBadge, ProtectedRoute
│       ├── lib/
│       │   ├── axios.ts              ← Instance Axios + intercepteurs
│       │   └── styles.ts             ← Constantes de style partagées
│       ├── pages/                    ← 8 pages
│       ├── store/authStore.ts        ← Zustand auth store
│       └── types/index.ts            ← Types TypeScript partagés
│
├── .gitignore
└── README.md
```

---

## Stack technique

### Backend

| Technologie | Version | Rôle |
|---|---|---|
| PHP | ^8.2 | Runtime |
| Laravel | ^12.0 | Framework API |
| Laravel Sanctum | ^4.0 | Auth par token Bearer |
| Spatie Laravel Permission | 6.24 | Rôles (admin, manager, client) |
| barryvdh/laravel-dompdf | ^3.1 | Export PDF |
| srmklive/paypal | * | Intégration PayPal REST |
| MySQL (XAMPP) | — | Base de données |

### Frontend

| Technologie | Version | Rôle |
|---|---|---|
| React | ^19.2 | UI |
| TypeScript | ~6.0 | Typage statique |
| Vite | ^8.0 | Build tool |
| React Router DOM | ^6.30 | Routing SPA |
| Zustand | ^5.0 | State management (auth) |
| Axios | ^1.16 | Client HTTP |
| TailwindCSS | ^4.3 | Styles utilitaires |
| Lucide React | latest | Icônes (remplace tous les emojis) |
| Framer Motion | latest | Animations (transitions, fade-in, hover) |
| Chart.js + react-chartjs-2 | latest | Graphiques avancés (admin) |

---

## Démarrage rapide

### Prérequis

- PHP 8.2+ avec XAMPP (MySQL)
- Composer 2+
- Node.js 20+ / npm 10+

### Backend

```bash
cd backend

# 1. Installer les dépendances
composer install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 3. Créer la base MySQL dans phpMyAdmin : "wave"
# Puis migrer et seeder
php artisan migrate
php artisan db:seed

# 4. Lancer le serveur
php artisan serve
# → http://localhost:8000
```

### Frontend

```bash
cd frontend
npm install
npm run dev
# → http://localhost:5173
```

---

## Variables d'environnement

### `backend/.env` (valeurs clés)

```env
APP_NAME="Wavy"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wave
DB_USERNAME=root
DB_PASSWORD=          # vide pour XAMPP par défaut

SANCTUM_STATEFUL_DOMAINS=localhost:5173
FRONTEND_URL=http://localhost:5173

PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=
```

---

## Base de données

### Tables

| Table | Description |
|---|---|
| `users` | Comptes utilisateurs (admin, manager, client) |
| `clients` | Profils clients avec statut paiement et relance |
| `payments` | Historique des paiements par client |
| `licenses` | Licences assignées aux clients |
| `license_offers` | Offres de licences disponibles en boutique |
| `roles` / `permissions` | Spatie Permission |
| `personal_access_tokens` | Tokens Sanctum |

### Relations

```
User ──hasOne──► Client
Client ──hasMany──► Payment
Client ──hasMany──► License
```

### Statuts de paiement

`payé` · `en_attente` · `en_retard`

---

## API Reference

**Base URL :** `http://localhost:8000/api`  
**Auth :** `Authorization: Bearer {token}`  
**Devise :** Dirham marocain (DH / MAD)

### Auth (public)

| Méthode | Route | Body |
|---|---|---|
| POST | `/login` | `email`, `password` |
| POST | `/register` | `name`, `email`, `password`, `password_confirmation`, `role` |

**Réponse `/login` :**
```json
{
  "data": {
    "token": "1|abc...",
    "user": { "id": 1, "name": "Admin", "email": "admin@test.com", "roles": ["admin"] }
  }
}
```

### Auth (protégé)

| Méthode | Route | Description |
|---|---|---|
| POST | `/logout` | Révoque le token |
| GET | `/user` | Utilisateur courant |

### Clients `admin|manager`

| Méthode | Route | Description |
|---|---|---|
| GET | `/clients` | Liste paginée (`?search=&status=&page=`) |
| POST | `/clients` | Créer |
| GET | `/clients/{id}` | Détail + payments + licenses |
| PUT | `/clients/{id}` | Modifier |
| DELETE | `/clients/{id}` | Supprimer |
| PUT | `/clients/{id}/relance` | Mettre à jour la relance |
| GET | `/clients/export/csv` | Export CSV |
| GET | `/clients/{id}/export/pdf` | Fiche PDF |

### Paiements `admin|manager`

| Méthode | Route |
|---|---|
| GET | `/clients/{id}/payments` |
| POST | `/clients/{id}/payments` |
| PUT | `/payments/{id}` |
| DELETE | `/payments/{id}` |

### Licences `admin|manager`

| Méthode | Route |
|---|---|
| GET | `/licenses` |
| POST | `/licenses` |
| DELETE | `/licenses/{id}` |
| POST | `/clients/{id}/licenses` |

### Offres `admin`

| Méthode | Route |
|---|---|
| GET | `/license-offers` |
| POST | `/license-offers` |
| DELETE | `/license-offers/{id}` |

### Managers `admin`

| Méthode | Route |
|---|---|
| GET | `/managers` |
| POST | `/managers` |
| DELETE | `/managers/{id}` |

### Stats `admin|manager`

| Méthode | Route | Réponse |
|---|---|---|
| GET | `/stats` | `mrr` (DH), `total_clients`, `paid`, `pending`, `late` |
| GET | `/stats/revenue?year=` | Tableau 12 valeurs mensuelles (DH) |

### Espace client `client`

| Méthode | Route |
|---|---|
| GET | `/mon-espace` |
| GET | `/mon-espace/export/pdf` |
| POST | `/boutique/checkout` |
| GET | `/boutique/success` |
| GET | `/boutique/cancel` |

---

## Frontend — Pages & Routes

| Route | Page | Rôles | Description |
|---|---|---|---|
| `/login` | `LoginPage` | public | Connexion avec logo Wavy |
| `/dashboard` | `DashboardPage` | admin, manager | KPIs en DH + raccourcis |
| `/clients` | `ClientsPage` | admin, manager | Table paginée + CRUD + filtres |
| `/clients/:id` | `ClientDetailPage` | admin, manager | Paiements, licences, relance, PDF |
| `/chart` | `RevenuePage` | admin, manager | Chart.js avancé (barres/courbe) |
| `/managers` | `ManagersPage` | admin | Gestion des managers |
| `/mon-espace` | `MonEspacePage` | client | Paiements + licences en lecture |
| `/boutique` | `BoutiquePage` | client | Licences PayPal + produits WordPress |

---

## Frontend — Composants

| Composant | Description |
|---|---|
| `AppLayout` | Layout avec Sidebar + Framer Motion page transitions |
| `Sidebar` | Navigation avec icônes Lucide, filtrée par rôle, logo Wavy |
| `ProtectedRoute` | Guard de route (auth + rôle), attend `isRestored` |
| `StatCard` | Carte KPI avec icône Lucide, animation fade-in |
| `StatusBadge` | Badge coloré pour statuts paiement (light theme) |

---

## Authentification & Rôles

### Flux

```
POST /api/login → { token, user: { roles } }
→ Token stocké en sessionStorage
→ Zustand authStore hydraté
→ Axios interceptor ajoute Bearer token
→ 401 → vide store + redirige /login
```

### Rôles

| Rôle | Accès |
|---|---|
| `admin` | Tout |
| `manager` | Clients, paiements, licences, stats — pas managers ni offres |
| `client` | Mon espace (lecture), boutique |

### `isRestored` flag

Au démarrage, `App.tsx` attend que `restoreSession()` ait fini avant de rendre le router — évite le flash-redirect vers `/login` au refresh.

---

## Comptes de test

Créés par `php artisan db:seed`.

| Rôle | Email | Mot de passe |
|---|---|---|
| **Admin** | `admin@test.com` | `password123` |
| **Manager** | `manager@test.com` | `password123` |
| **Client** | `client@test.com` | `password123` |

---

## Logique métier

### PaymentObserver

Déclenché à chaque `save()` sur `Payment` :
- `en_retard` / `en_attente` → `relance_flag = true`, `date_relance = now() + 3j`
- `payé` → `relance_flag = false`, `date_relance = null`

### Pagination clients

15 par page, filtres cumulables : `?search=nom&status=payé`

---

## Exports

| Export | Route | Format |
|---|---|---|
| CSV clients | `GET /api/clients/export/csv` | CSV UTF-8 BOM, séparateur `;` |
| PDF client | `GET /api/clients/{id}/export/pdf` | A4 portrait, DomPDF |
| PDF paiements | `GET /api/mon-espace/export/pdf` | A4 portrait, DomPDF |

---

## PayPal

Flux stateless (Bearer token, pas de session) :

```
POST /boutique/checkout { offer_id }
→ { approval_url, order_id }
→ Redirect vers PayPal
→ GET /boutique/success?token=&offer_id=
→ Capture + License créée + Payment créé
```

---

## Design System

### Thème : Light

| Élément | Valeur |
|---|---|
| Fond global | `#f4f6fb` |
| Surface (cards) | `#ffffff` |
| Bordures | `#e2e8f0` |
| Texte principal | `#1e293b` |
| Texte secondaire | `#64748b` |
| Primaire | `#22419A` |
| Accent | `#439670` |
| Dégradé | `linear-gradient(135deg, #22419A 0%, #439670 100%)` |

### Cards

```css
background: #ffffff;
border: 1px solid #e2e8f0;
border-radius: 1rem;
box-shadow: 0 1px 3px rgba(0,0,0,0.06);
```

### Icônes

Toutes les icônes utilisent **Lucide React** — aucun emoji dans l'UI.

### Animations (Framer Motion)

- Page transitions : fade + slide Y sur `<AnimatePresence>`
- Sections : `initial={{ opacity:0, y:16 }}` → `animate={{ opacity:1, y:0 }}`
- Boutons : `whileHover={{ scale:1.02 }}` + `whileTap={{ scale:0.98 }}`
- Listes : `staggerChildren: 0.07` pour les grilles de cards

### Graphiques (Chart.js)

- Barres et courbes avec toggle
- Tooltips personnalisés (fond sombre, montants en DH)
- Légende en haut, couleurs dégradé primaire/accent
- Animations `easeInOutQuart` 700ms
- Grille légère `#f1f5f9`
