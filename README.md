Thought for 1 second### 🚀 API de Gestion de Recrutements














Une API REST complète pour la gestion des recrutements, permettant aux recruteurs de publier des annonces et aux candidats de postuler. Développée avec Laravel 10 et Sanctum pour l'authentification.

## 📋 Table des matières

- [🚀 API de Gestion de Recrutements](#-api-de-gestion-de-recrutements)

- [📋 Table des matières](#-table-des-matières)
- [✨ Fonctionnalités](#-fonctionnalités)
- [🔧 Technologies utilisées](#-technologies-utilisées)
- [📥 Installation](#-installation)
- [⚙️ Configuration](#️-configuration)
- [🏗️ Structure du projet](#️-structure-du-projet)
- [🔐 Authentification & Sécurité](#-authentification--sécurité)
- [📢 Gestion des Annonces](#-gestion-des-annonces)
- [📝 Gestion des Candidatures](#-gestion-des-candidatures)
- [🔔 Notifications](#-notifications)
- [👤 Gestion des Utilisateurs](#-gestion-des-utilisateurs)
- [📊 Statistiques et Rapports](#-statistiques-et-rapports)
- [🧪 Tests](#-tests)
- [📚 Documentation API](#-documentation-api)
- [👨‍💻 Contributeurs](#-contributeurs)
- [📄 Licence](#-licence)





## ✨ Fonctionnalités

- 🔐 **Authentification sécurisée** avec Laravel Sanctum
- 👥 **Gestion des rôles** (candidat, recruteur, admin)
- 📢 **Gestion des annonces** (création, modification, suppression)
- 📝 **Gestion des candidatures** (postuler, retirer, filtrer)
- 📊 **Suivi des candidatures** (mise à jour du statut, notifications)
- 🔔 **Système de notifications** pour les changements de statut
- 📈 **Statistiques** pour les recruteurs et administrateurs
- 🛡️ **Permissions basées sur les rôles** avec Laravel Gates & Policies


## 🔧 Technologies utilisées

- **Laravel 10.x** - Framework PHP
- **PHP 8.1+** - Langage de programmation
- **Laravel Sanctum** - Authentification API
- **MySQL** - Base de données
- **Repository Pattern** - Architecture
- **Service Layer** - Logique métier
- **PHPUnit** - Tests unitaires
- **Swagger/OpenAPI** - Documentation API


## 📥 Installation

```shellscript
# Cloner le dépôt
git clone https://github.com/votre-nom/recrutement-api.git

# Accéder au répertoire du projet
cd recrutement-api

# Installer les dépendances
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

## ⚙️ Configuration

1. Configurez votre base de données dans le fichier `.env`:


```plaintext
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recrutement_api
DB_USERNAME=root
DB_PASSWORD=
```

2. Exécutez les migrations et les seeders:


```shellscript
php artisan migrate --seed
```

3. Configurez Sanctum:


```shellscript
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

4. Démarrez le serveur:


```shellscript
php artisan serve
```

## 🏗️ Structure du projet

Le projet suit une architecture en couches avec Repository Pattern et Service Layer:

```plaintext
app/
├── Http/
│   ├── Controllers/
│   │   └── API/
│   │       ├── AuthController.php
│   │       ├── AnnonceController.php
│   │       ├── CandidatureController.php
│   │       ├── StatistiqueController.php
│   │       └── UserController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── User.php
│   ├── Annonce.php
│   ├── Candidature.php
│   └── Notification.php
├── Repositories/
│   ├── BaseRepository.php
│   ├── UserRepository.php
│   ├── AnnonceRepository.php
│   └── CandidatureRepository.php
└── Services/
    ├── AuthService.php
    ├── AnnonceService.php
    ├── CandidatureService.php
    ├── NotificationService.php
    └── StatistiqueService.php
```

## 🔐 Authentification & Sécurité

| Méthode | URL | Description | Rôle requis
|-----|-----|-----|-----
| POST | `/api/auth/register` | 📝 Inscription d'un nouvel utilisateur | Public
| POST | `/api/auth/login` | 🔑 Connexion et récupération du token JWT | Public
| POST | `/api/auth/logout` | 🚪 Déconnexion (révocation du token) | Authentifié
| POST | `/api/auth/refresh` | 🔄 Rafraîchir le token d'authentification | Authentifié
| POST | `/api/auth/password/forgot` | 📧 Demande de réinitialisation du mot de passe | Public
| POST | `/api/auth/password/reset` | 🔒 Réinitialisation du mot de passe | Public


### Exemple d'inscription

```json
// POST /api/auth/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "candidat"
}
```

### Exemple de connexion

```json
// POST /api/auth/login
{
  "email": "john@example.com",
  "password": "password"
}
```

## 📢 Gestion des Annonces

| Méthode | URL | Description | Rôle requis
|-----|-----|-----|-----
| GET | `/api/annonces` | 📋 Récupérer la liste des annonces | Public
| GET | `/api/annonces/{id}` | 🔍 Récupérer les détails d'une annonce spécifique | Public
| POST | `/api/annonces` | ➕ Ajouter une nouvelle annonce | Recruteur, Admin
| PUT | `/api/annonces/{id}` | ✏️ Modifier une annonce existante | Recruteur (propriétaire), Admin
| DELETE | `/api/annonces/{id}` | 🗑️ Supprimer une annonce | Recruteur (propriétaire), Admin


### Exemple de création d'annonce

```json
// POST /api/annonces
{
  "titre": "Développeur Laravel",
  "description": "Nous recherchons un développeur Laravel expérimenté...",
  "localisation": "Paris",
  "type_contrat": "CDI",
  "salaire": 45000
}
```

## 📝 Gestion des Candidatures

| Méthode | URL | Description | Rôle requis
|-----|-----|-----|-----
| GET | `/api/candidatures` | 📋 Récupérer les candidatures (selon le rôle) | Authentifié
| GET | `/api/candidatures/mes-candidatures` | 🧑‍💼 Récupérer ses propres candidatures | Candidat
| GET | `/api/candidatures/annonce/{annonceId}` | 🔍 Récupérer les candidatures pour une annonce | Recruteur (propriétaire), Admin
| GET | `/api/candidatures/{id}` | 📄 Récupérer les détails d'une candidature | Candidat (propriétaire), Recruteur (annonce), Admin
| POST | `/api/candidatures` | ✉️ Postuler à une annonce | Candidat
| DELETE | `/api/candidatures/{id}` | 🗑️ Retirer une candidature | Candidat (propriétaire), Admin
| PUT | `/api/candidatures/{id}/statut` | 🔄 Modifier le statut d'une candidature | Recruteur (annonce), Admin


### Exemple de soumission de candidature

```plaintext
// POST /api/candidatures (multipart/form-data)
annonce_id: 1
cv: [fichier PDF]
lettre_motivation: "Je suis très intéressé par votre offre..."
```

### Exemple de modification de statut

```json
// PUT /api/candidatures/1/statut
{
  "statut": "en_cours"
}
```

## 🔔 Notifications

| Méthode | URL | Description | Rôle requis
|-----|-----|-----|-----
| POST | `/api/notifications/candidature/{id}` | 🔔 Notifier un candidat du changement de statut | Recruteur (annonce), Admin


## 👤 Gestion des Utilisateurs

| Méthode | URL | Description | Rôle requis
|-----|-----|-----|-----
| GET | `/api/utilisateurs/profil` | 👤 Récupérer son profil utilisateur | Authentifié
| PUT | `/api/utilisateurs/profil` | ✏️ Modifier son profil utilisateur | Authentifié
| DELETE | `/api/utilisateurs/{id}` | 🗑️ Supprimer un utilisateur | Admin, Utilisateur (soi-même)


## 📊 Statistiques et Rapports

| Méthode | URL | Description | Rôle requis
|-----|-----|-----|-----
| GET | `/api/stats/recruteur` | 📈 Obtenir des statistiques sur ses annonces et candidatures | Recruteur, Admin
| GET | `/api/stats/globales` | 📊 Obtenir des statistiques globales sur l'utilisation de la plateforme | Admin


### Exemple de réponse pour les statistiques globales

```json
// GET /api/stats/globales
{
  "total_utilisateurs": 15,
  "total_recruteurs": 5,
  "total_candidats": 9,
  "total_annonces": 12,
  "total_candidatures": 25,
  "candidatures_par_statut": {
    "en_attente": 10,
    "en_cours": 8,
    "acceptee": 5,
    "refusee": 2
  }
}
```

## 🧪 Tests

Le projet inclut des tests unitaires pour les fonctionnalités clés:

```shellscript
# Exécuter tous les tests
php artisan test

# Exécuter un test spécifique
php artisan test --filter=AuthTest
```

## 📚 Documentation API

La documentation complète de l'API est disponible via Swagger/OpenAPI:

```shellscript
# Générer la documentation
php artisan l5-swagger:generate
```

Accédez à la documentation à l'URL:

```plaintext
http://localhost:8000/api/documentation
```

## 👨‍💻 Contributeurs

- [Votre Nom](https://github.com/votre-nom) - Développeur principal


## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.