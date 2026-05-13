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
15. [Style & Design System](#style--design-system)

---

## Vue d'ensemble

GestionClients est une application B2B permettant à une équipe (admin + managers) de gérer un portefeuille de clients, leurs paiements, leurs licences logicielles et leurs relances. Les clients ont accès à leur propre espace en lecture seule et peuvent acheter des licences supplémentaires via PayPal.

**Avant la migration :** monolithe Laravel + Livewire (MPA).  
**Après la migration :** API REST Laravel + SPA React découplée.

---

## Structure du projet

```
GestionClients-Abonnements/
│
├── backend/                        ← Laravel 12 REST API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/    ← 10 contrôleurs API
│   │   │   ├── Middleware/
│   │   │   └── Requests/Api/       ← Form Requests (validation)
│   │   ├── Mail/
│   │   │   └── PaymentReminderMail.php
│   │   ├── Models/
│   │   │   ├── Client.php
│   │   │   ├── License.php
│   │   │   ├── LicenseOffer.php
│   │   │   ├── Payment.php
│   │   │   └── User.php
│   │   ├── Observers/
│   │   │   └── PaymentObserver.php
│   │   └── Providers/
│   │       └── AppServiceProvider.php
│   ├── config/
│   │   ├── cors.php
│   │   ├── paypal.php
│   │   └── sanctum.php
│   ├── database/
│   │   ├── migrations/             ← 10 migrations
│   │   └── seeders/
│   │       ├── DatabaseSeeder.php
│   │       ├── UserSeeder.php      ← comptes de test
│   │       ├── ClientSeeder.php
│   │       ├── HistoriqueDePaiments.php
│   │       ├── LicenseSeeder.php
│   │       └── roleseeder.php
│   ├── resources/views/pdf/        ← templates PDF (DomPDF)
│   │   ├── client.blade.php
│   │   └── payments.blade.php
│   └── routes/
│       ├── api.php                 ← 33 routes API
│       └── web.php                 ← health check uniquement
│
├── frontend/                       ← React 19 SPA
│   └── src/
│       ├── components/             ← composants réutilisables
│       ├── lib/
│       │   └── axios.ts            ← instance Axios configurée
│       ├── pages/                  ← 8 pages
│       ├── store/
│       │   └── authStore.ts        ← Zustand auth store
│       └── types/
│           └── index.ts            ← types TypeScript partagés
│
├── .gitignore
├── .gitattributes
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
| Spatie Laravel Permission | 6.24 | Gestion des rôles (admin, manager, client) |
| barryvdh/laravel-dompdf | ^3.1 | Export PDF |
| srmklive/paypal | * | Intégration PayPal REST |
| SQLite / MySQL | — | Base de données |

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
| Recharts | ^3.8 | Graphiques |

---

## Démarrage rapide

### Prérequis

- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm 10+

### Backend

```bash
cd backend

# 1. Installer les dépendances PHP
composer install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 3. Créer la base de données SQLite et migrer
touch database/database.sqlite
php artisan migrate

# 4. Seeder les rôles et les comptes de test
php artisan db:seed

# 5. Lancer le serveur
php artisan serve
# → http://localhost:8000
```

### Frontend

```bash
cd frontend

# 1. Installer les dépendances JS
npm install

# 2. Lancer le dev server
npm run dev
# → http://localhost:5173
```

### Build de production (frontend)

```bash
cd frontend
npm run build
# → dist/ prêt à déployer
```

---

## Variables d'environnement

### `backend/.env`

```env
APP_NAME="GestionClients API"
APP_ENV=local
APP_KEY=                          # généré par php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr

# Base de données (SQLite par défaut)
DB_CONNECTION=sqlite
# Pour MySQL :
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=gestion_clients
# DB_USERNAME=root
# DB_PASSWORD=

# Sanctum SPA — domaine du frontend
SANCTUM_STATEFUL_DOMAINS=localhost:5173
FRONTEND_URL=http://localhost:5173

# PayPal
PAYPAL_MODE=sandbox               # sandbox | live
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=
PAYPAL_LIVE_CLIENT_ID=
PAYPAL_LIVE_CLIENT_SECRET=
PAYPAL_CURRENCY=USD

# Mail
MAIL_MAILER=log                   # log | smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_FROM_ADDRESS="noreply@gestionclients.local"
```

---

## Base de données

### Schéma

#### `users`
| Colonne | Type | Description |
|---|---|---|
| id | bigint PK | |
| name | string | Nom complet |
| email | string unique | Email de connexion |
| password | string | Hash bcrypt |
| email_verified_at | timestamp nullable | |
| remember_token | string nullable | |
| created_at / updated_at | timestamp | |

#### `clients`
| Colonne | Type | Description |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users nullable | Compte utilisateur lié |
| nom | string | Nom du client |
| email | string | Email du client |
| telephone | string | Téléphone |
| statut_paiement | enum | `payé` · `en_attente` · `en_retard` |
| date_maintenance | date | Date de prochaine maintenance |
| licences_count | integer | Nombre de licences |
| relance_flag | boolean | Relance active ? |
| date_relance | date nullable | Date prévue de relance |
| note_relance | text nullable | Note de relance |
| created_at / updated_at | timestamp | |

#### `payments`
| Colonne | Type | Description |
|---|---|---|
| id | bigint PK | |
| client_id | FK → clients (cascade delete) | |
| montant | decimal(10,2) | Montant en € |
| date_payment | date | Date du paiement |
| status_payment | enum | `payé` · `en_attente` · `en_retard` |
| created_at / updated_at | timestamp | |

#### `licenses`
| Colonne | Type | Description |
|---|---|---|
| id | bigint PK | |
| client_id | FK → clients | |
| nom | string | Nom de la licence |
| quantite_disponible | integer | Quantité |
| date_assignation | date | Date d'assignation |
| created_at / updated_at | timestamp | |

#### `license_offers`
| Colonne | Type | Description |
|---|---|---|
| id | bigint PK | |
| nom | string | Nom de l'offre |
| description | text nullable | Description |
| prix | decimal(10,2) | Prix en USD |
| quantite_disponible | integer | Stock disponible |
| created_at / updated_at | timestamp | |

#### Tables Spatie Permission
`roles` · `permissions` · `model_has_roles` · `model_has_permissions` · `role_has_permissions`

#### `personal_access_tokens`
Gérée par Laravel Sanctum pour les tokens Bearer.

### Relations

```
User ──hasOne──► Client
Client ──hasMany──► Payment
Client ──hasMany──► License
Payment ──belongsTo──► Client
License ──belongsTo──► Client
```

---

## API Reference

**Base URL :** `http://localhost:8000/api`  
**Auth :** `Authorization: Bearer {token}` sur toutes les routes protégées  
**Format :** JSON — réponse standard `{ data: ..., message: ... }`

### Auth (public)

| Méthode | Route | Description | Body |
|---|---|---|---|
| POST | `/login` | Connexion → token + user + roles | `email`, `password` |
| POST | `/register` | Créer un compte (admin) | `name`, `email`, `password`, `password_confirmation`, `role` |

**Réponse `/login` :**
```json
{
  "data": {
    "token": "1|abc...",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@test.com",
      "roles": ["admin"]
    }
  },
  "message": "Connexion réussie."
}
```

### Auth (protégé)

| Méthode | Route | Description |
|---|---|---|
| POST | `/logout` | Révoque le token courant |
| GET | `/user` | Retourne l'utilisateur authentifié + rôles |

### Clients `role: admin|manager`

| Méthode | Route | Description | Params |
|---|---|---|---|
| GET | `/clients` | Liste paginée (15/page) | `?search=nom&status=payé&page=1` |
| POST | `/clients` | Créer un client | body complet |
| GET | `/clients/{id}` | Détail + payments + licenses | |
| PUT | `/clients/{id}` | Modifier | champs partiels acceptés |
| DELETE | `/clients/{id}` | Supprimer (révoque aussi le compte user lié) | |
| PUT | `/clients/{id}/relance` | Mettre à jour la relance | `relance_flag`, `date_relance`, `note_relance` |
| GET | `/clients/export/csv` | Télécharger tous les clients en CSV | |
| GET | `/clients/{id}/export/pdf` | Fiche client PDF (paiements + licences) | |

**Validation création/modification client :**
```
nom              : required, string, min:3, max:200
email            : required, email, max:200
telephone        : required, string, min:8, max:20
statut_paiement  : required, in:payé,en_attente,en_retard
date_maintenance : required, date
licences_count   : required, integer, min:0, max:500
```

### Paiements `role: admin|manager`

| Méthode | Route | Description |
|---|---|---|
| GET | `/clients/{id}/payments` | Liste des paiements d'un client |
| POST | `/clients/{id}/payments` | Ajouter un paiement |
| PUT | `/payments/{id}` | Modifier un paiement |
| DELETE | `/payments/{id}` | Supprimer un paiement |

**Validation paiement :**
```
montant        : required, numeric, min:0
date_payment   : required, date
status_payment : required, in:payé,en_attente,en_retard
```

### Licences `role: admin|manager`

| Méthode | Route | Description |
|---|---|---|
| GET | `/licenses` | Toutes les licences (avec client) |
| POST | `/licenses` | Créer une licence standalone |
| DELETE | `/licenses/{id}` | Supprimer |
| POST | `/clients/{id}/licenses` | Assigner une licence à un client |

### Offres de licences `role: admin`

| Méthode | Route | Description |
|---|---|---|
| GET | `/license-offers` | Liste des offres (accessible à tous les authentifiés) |
| POST | `/license-offers` | Créer une offre |
| DELETE | `/license-offers/{id}` | Supprimer une offre |

### Managers `role: admin`

| Méthode | Route | Description |
|---|---|---|
| GET | `/managers` | Liste des managers |
| POST | `/managers` | Créer un manager (crée un User + assigne le rôle) |
| DELETE | `/managers/{id}` | Supprimer (impossible sur un admin) |

### Statistiques `role: admin|manager`

| Méthode | Route | Description | Réponse |
|---|---|---|---|
| GET | `/stats` | KPIs du mois courant | `mrr`, `total_clients`, `paid`, `pending`, `late` |
| GET | `/stats/revenue` | Revenus mensuels | `?year=2025` → tableau de 12 valeurs |

**Réponse `/stats` :**
```json
{
  "data": {
    "mrr": 4250.00,
    "total_clients": 42,
    "paid": 30,
    "pending": 8,
    "late": 4
  }
}
```

**Réponse `/stats/revenue?year=2025` :**
```json
{
  "data": {
    "year": 2025,
    "monthly_revenue": [1200, 980, 1450, 0, 2100, 1800, 0, 0, 0, 0, 0, 0],
    "available_years": [2025, 2024]
  }
}
```

### Espace client `role: client`

| Méthode | Route | Description |
|---|---|---|
| GET | `/mon-espace` | Profil + paiements + licences du client connecté |
| GET | `/mon-espace/export/pdf` | Export PDF de ses paiements |

### PayPal `role: client`

| Méthode | Route | Description |
|---|---|---|
| POST | `/boutique/checkout` | Initier un paiement PayPal → retourne `approval_url` |
| GET | `/boutique/success` | Capture le paiement après retour PayPal |
| GET | `/boutique/cancel` | Annulation PayPal |

**Body `/boutique/checkout` :**
```json
{ "offer_id": 1 }
```

**Réponse :**
```json
{
  "data": {
    "order_id": "5O190127TN364715T",
    "approval_url": "https://www.sandbox.paypal.com/checkoutnow?token=...",
    "offer_id": 1
  }
}
```

---

## Frontend — Pages & Routes

| Route | Page | Rôles autorisés | Description |
|---|---|---|---|
| `/login` | `LoginPage` | public | Formulaire de connexion |
| `/dashboard` | `DashboardPage` | admin, manager | KPIs + raccourcis |
| `/clients` | `ClientsPage` | admin, manager | Table paginée + CRUD + filtres |
| `/clients/:id` | `ClientDetailPage` | admin, manager | Détail client, onglets paiements/licences, relance, PDF |
| `/chart` | `RevenuePage` | admin, manager | Graphique revenus mensuels (Recharts) |
| `/managers` | `ManagersPage` | admin uniquement | Gestion des managers |
| `/mon-espace` | `MonEspacePage` | client | Espace client — paiements + licences en lecture |
| `/boutique` | `BoutiquePage` | client | Achat de licences via PayPal |

**Redirections automatiques :**
- `/` → `/dashboard`
- Non authentifié → `/login`
- Mauvais rôle → `/dashboard` (admin/manager) ou `/mon-espace` (client)
- Route inconnue → `/login`

---

## Frontend — Composants

### `AppLayout`
Layout principal avec `Sidebar` à gauche et `<Outlet>` pour le contenu. Utilisé par toutes les pages protégées.

### `Sidebar`
Navigation latérale avec :
- Logo GC avec dégradé
- Liens filtrés par rôle (les managers ne voient pas `/managers`)
- Indicateur de route active (dégradé bleu→vert)
- Bloc utilisateur (nom, rôle) + bouton déconnexion

### `ProtectedRoute`
Guard de route React Router. Vérifie :
1. L'utilisateur est authentifié (token présent dans le store)
2. L'utilisateur a l'un des rôles requis

### `StatCard`
Carte glassmorphism affichant une icône, un label et une valeur numérique. Supporte un mode `gradient` pour les KPIs principaux.

### `StatusBadge`
Badge coloré pour les statuts de paiement :
- `payé` → vert `#34d399`
- `en_attente` → jaune `#fbbf24`
- `en_retard` → rouge `#f87171`

---

## Authentification & Rôles

### Flux d'authentification

```
1. POST /api/login  →  { token, user: { roles } }
2. Token stocké en sessionStorage (pas localStorage)
3. Zustand authStore hydraté avec token + user
4. Axios interceptor ajoute Authorization: Bearer {token} à chaque requête
5. Interceptor 401 → vide le store + redirige vers /login
6. POST /api/logout → révoque le token côté serveur + vide le store
```

### Rôles (Spatie Laravel Permission)

| Rôle | Permissions |
|---|---|
| `admin` | Tout — clients, paiements, licences, offres, managers, stats, exports |
| `manager` | Clients, paiements, licences, stats, exports — pas les managers ni les offres |
| `client` | Mon espace (lecture seule), boutique PayPal |

### Zustand `authStore`

```typescript
interface AuthState {
  token: string | null
  user: { id, name, email, roles[] } | null
  isAuthenticated: () => boolean
  hasRole: (role: string | string[]) => boolean
  login: (email, password) => Promise<void>
  logout: () => Promise<void>
  restoreSession: () => void   // recharge depuis sessionStorage au démarrage
}
```

---

## Comptes de test

Créés par `php artisan db:seed --class=UserSeeder` (inclus dans `php artisan db:seed`).

| Rôle | Email | Mot de passe | Accès |
|---|---|---|---|
| **Admin** | `admin@test.com` | `password123` | Tout |
| **Manager** | `manager@test.com` | `password123` | Dashboard, Clients, Revenus |
| **Client** | `client@test.com` | `password123` | Mon Espace, Boutique |

---

## Logique métier

### Observer PaymentObserver

Déclenché automatiquement à chaque `save()` sur un `Payment` :

```
status_payment = 'en_retard' ou 'en_attente'
  → client.relance_flag = true
  → client.date_relance = now() + 3 jours

status_payment = 'payé'
  → client.relance_flag = false
  → client.date_relance = null
```

### Relance manuelle

`PUT /api/clients/{id}/relance` permet à un admin/manager de forcer manuellement :
- `relance_flag` (boolean)
- `date_relance` (date)
- `note_relance` (texte libre)

### Accès client

Un client peut avoir un compte utilisateur lié (`user_id` sur la table `clients`). L'admin crée ce lien depuis la page Clients. Le compte client ne peut voir que ses propres données via `/api/mon-espace`.

### Pagination

La liste des clients est paginée à **15 par page** avec filtres cumulables :
- `?search=nom` — recherche par nom (LIKE)
- `?status=payé|en_attente|en_retard` — filtre par statut

---

## Exports

### CSV — `GET /api/clients/export/csv`

Télécharge tous les clients en CSV (séparateur `;`, BOM UTF-8 pour compatibilité Excel).

Colonnes : `ID`, `Nom`, `Email`, `Téléphone`, `Statut`, `Date Maintenance`, `Nb Licences`, `Relance`, `Date Relance`, `Note Relance`

### PDF client — `GET /api/clients/{id}/export/pdf`

Fiche complète d'un client : informations + tableau des paiements + tableau des licences. Généré avec DomPDF, template Blade dans `resources/views/pdf/client.blade.php`.

### PDF paiements client — `GET /api/mon-espace/export/pdf`

Historique des paiements du client connecté. Template : `resources/views/pdf/payments.blade.php`.

---

## PayPal

### Configuration

```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_secret
PAYPAL_CURRENCY=USD
```

### Flux de paiement (stateless)

```
1. Client clique "Acheter" sur BoutiquePage
2. POST /api/boutique/checkout { offer_id }
   → Crée un order PayPal
   → Retourne { approval_url, order_id, offer_id }
3. Frontend redirige vers approval_url (PayPal)
4. PayPal redirige vers /boutique/success?token=...&offer_id=...
5. GET /api/boutique/success
   → Capture le paiement
   → Décrémente le stock de l'offre
   → Crée une License pour le client
   → Crée un Payment (status: payé) pour le client
6. En cas d'annulation → /boutique/cancel
```

---

## Style & Design System

### Palette de couleurs

| Variable | Valeur | Usage |
|---|---|---|
| `--primary` | `#22419A` | Bleu principal, boutons, accents |
| `--accent` | `#439670` | Vert secondaire, succès |
| `--bg` | `#0d1b2a` | Fond global dark navy |

### Règles de style

**Fond global**
```css
background-color: #0d1b2a;
```

**Cards glassmorphism**
```css
background: rgba(255, 255, 255, 0.05);
backdrop-filter: blur(12px);
border-radius: 1.5rem;        /* rounded-3xl */
border: 1px solid rgba(255, 255, 255, 0.1);
```

**Dégradé hero / boutons**
```css
background: linear-gradient(135deg, #22419A 0%, #439670 100%);
```

**Inputs**
```css
background: rgba(255, 255, 255, 0.08);
border: 1px solid rgba(255, 255, 255, 0.15);
border-radius: 0.75rem;       /* rounded-xl */
color: white;
```

**Texte**
- Principal : `white`
- Secondaire : `rgba(255,255,255,0.7)` → `text-white/70`
- Tertiaire : `rgba(255,255,255,0.4)` → `text-white/40`

**Tables**
- Lignes : fond transparent
- Hover : `rgba(255,255,255,0.05)` → `hover:bg-white/5`
- Séparateurs : `rgba(255,255,255,0.05)`

**Boutons**
- Primaire : dégradé `#22419A → #439670` + `hover:scale-[1.02]`
- Secondaire : `border border-white/20 hover:border-white/40`
- Danger : `rgba(220,38,38,0.7)`

> Pas de mode clair. Pas de Bootstrap. Pas de palette grise Tailwind par défaut.
