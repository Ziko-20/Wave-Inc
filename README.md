# 📋 GestionClients & Abonnements

> Application web fullstack de gestion de clients, abonnements, paiements et licences — construite avec **Laravel 12** + **Livewire 3**.

---

## 🎯 Présentation

**GestionClients-Abonnements** est une plateforme SaaS back-office complète permettant à une entreprise de gérer l'ensemble de son portefeuille clients : suivi des abonnements, historique des paiements, attribution de licences logicielles, et tableau de bord analytique en temps réel.

L'application dispose de **trois rôles distincts** (Admin, Manager, Client) avec des interfaces dédiées pour chacun.

---

## ✨ Fonctionnalités principales

### 👨‍💼 Espace Back-Office (Admin & Manager)

| Fonctionnalité | Description |
|---|---|
| **Tableau de bord** | Vue synthétique du portefeuille clients |
| **Gestion des clients** | CRUD complet avec recherche temps-réel et filtre par statut de paiement |
| **Historique des paiements** | Consulter, ajouter, modifier et supprimer les paiements par client |
| **Gestion des licences** | Attribuer des licences logicielles à un client avec date d'assignation |
| **Graphiques analytiques** | Revenus mensuels par année (Chart.js), MRR, clients payés / en attente / en retard |
| **Système de relance** | Marquage de clients à relancer avec notes et date de relance |
| **Export CSV** | Export de la liste clients en fichier CSV |
| **Export PDF** | Génération d'un relevé de paiements en PDF (DomPDF) |
| **Provisionnement de compte** | Création d'un compte utilisateur pour un client directement depuis l'interface |

### 👑 Espace Admin uniquement

| Fonctionnalité | Description |
|---|---|
| **Gestion des managers** | Créer et supprimer des comptes managers |
| **Boutique de licences** | Définir des offres de licences disponibles à l'achat |

### 🙋 Espace Client (self-service)

| Fonctionnalité | Description |
|---|---|
| **Mon espace** | Consulter ses propres paiements et licences attribuées |
| **Boutique** | Acheter des licences directement via **PayPal** |
| **Export PDF** | Télécharger son relevé de paiements personnel |

---

## 🏗️ Architecture & Stack technique

```
GestionClients-Abonnements/
├── app/
│   ├── Livewire/          # Composants réactifs (CRUD, Charts, Portails...)
│   ├── Models/            # Client, Payment, License, LicenseOffer, User
│   ├── Http/Controllers/  # PaypalController, LanguageController
│   └── Mail/              # PaymentReminderMail
├── database/
│   ├── migrations/        # Schéma complet de la BDD
│   ├── seeders/
│   └── factories/
├── resources/
│   └── views/
│       ├── livewire/      # Vues Blade des composants Livewire
│       └── pdf/           # Templates de génération PDF
├── routes/
│   └── web.php            # Routes protégées par rôle (admin|manager|client)
└── lang/
    ├── fr/                # Traductions françaises
    └── en/                # Traductions anglaises
```

### 🔧 Technologies utilisées

**Backend**
- [Laravel 12](https://laravel.com/) — Framework PHP (PHP 8.2+)
- [Livewire 3](https://livewire.laravel.com/) — Composants réactifs sans JavaScript
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) — Gestion des rôles et permissions (RBAC)
- [Laravel Breeze](https://laravel.com/docs/starter-kits) — Authentification prête à l'emploi
- [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) — Génération de PDF
- [srmklive/paypal](https://github.com/srmklive/laravel-paypal) — Intégration paiement PayPal

**Frontend**
- [Blade](https://laravel.com/docs/blade) — Moteur de templates Laravel
- [Tailwind CSS v4](https://tailwindcss.com/) — Framework CSS utilitaire
- [Bootstrap 5](https://getbootstrap.com/) — Composants UI
- [Chart.js](https://www.chartjs.org/) — Graphiques interactifs (revenus mensuels)
- [Vite](https://vite.dev/) — Bundler frontend

**Base de données**
- SQLite (par défaut) — Configurable vers MySQL/PostgreSQL via `.env`

**Internationalisation**
- 🇫🇷 Français / 🇬🇧 Anglais — Changement de langue à la volée

---

## 🗄️ Modèle de données

```
users ──────────────── clients
  │                      │
  │ (role: admin,         │ hasMany
  │  manager, client)     ▼
                       payments
                       licenses
                    license_offers
```

| Table | Champs principaux |
|---|---|
| `clients` | nom, email, téléphone, statut_paiement, date_maintenance, licences_count, relance_flag |
| `payments` | montant, date_payment, status_payment (payé / en_attente / en_retard) |
| `licenses` | nom, quantite_disponible, date_assignation |
| `license_offers` | nom, description, prix, quantite_disponible |
| `users` | name, email, password + rôles Spatie |

---

## 🔐 Système de rôles

| Rôle | Accès |
|---|---|
| **admin** | Tout : clients, paiements, licences, managers, statistiques |
| **manager** | Clients, paiements, licences, statistiques (pas la gestion des managers) |
| **client** | Son espace personnel, boutique de licences, export PDF |

Les routes sont protégées par les middlewares `auth`, `verified` et `role:...` (Spatie).

---

## 🚀 Installation & Démarrage

### Prérequis

- PHP >= 8.2
- Composer
- Node.js >= 18
- SQLite (inclus avec PHP) ou MySQL

### Installation

```bash
# 1. Cloner le projet
git clone https://github.com/votre-username/GestionClients-Abonnements.git
cd GestionClients-Abonnements

# 2. Installer les dépendances PHP
composer install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Lancer les migrations
php artisan migrate --seed

# 5. Installer les dépendances frontend
npm install

# 6. Démarrer en développement (serveur + Vite en parallèle)
composer run dev
```

L'application sera accessible sur **http://localhost:8000**.

### Configuration PayPal (optionnel)

Ajoutez vos clés dans `.env` :

```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_client_secret
```

---

## 📸 Aperçu des pages

| Page | Description |
|---|---|
| `/dashboard` | Tableau de bord synthétique |
| `/clients` | CRUD clients avec recherche et filtres |
| `/chart` | Graphique des revenus mensuels (Chart.js) |
| `/managers` | Gestion des comptes managers (admin only) |
| `/mon-espace` | Espace personnel du client |
| `/boutique` | Boutique de licences (paiement PayPal) |

---

## 📦 Scripts disponibles

```bash
# Développement (serveur PHP + queue + Vite)
composer run dev

# Build de production
npm run build

# Linter PHP (Laravel Pint)
composer run lint

# Tests
composer run test
```

---

## 🧠 Concepts & Patterns utilisés

- **RBAC (Role-Based Access Control)** avec Spatie Permissions
- **Composants réactifs Livewire** avec état local et validation intégrée
- **Architecture Monolithique MPA** — pas d'API REST séparée, tout est géré en Livewire
- **Observer Pattern** — `app/Observers/` pour réagir aux événements du modèle
- **Mail Notifications** — envoi de relances de paiement par email
- **Export multi-format** — CSV (streaming) et PDF (DomPDF)
- **Pagination Livewire** avec `WithPagination` et reset auto à la recherche

---

## 👨‍💻 Auteur

Développé par **[Votre Nom]** — Projet personnel / formation.

> N'hésitez pas à ⭐ le repo si le projet vous inspire !
