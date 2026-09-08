# 💼 Plateforme de Facturation B2B & Gestion d'Abonnements Récurrents

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-7-DC382D?logo=redis&logoColor=white)](https://redis.io)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://docker.com)
[![Nginx](https://img.shields.io/badge/Nginx-Serveur_Web-009639?logo=nginx&logoColor=white)](https://nginx.org)

Une API RESTful d'entreprise conçue pour gérer la facturation inter-entreprises (B2B) et les abonnements récurrents. Ce projet met l'accent sur les performances élevées, la sécurité, l'isolation des services sous Docker et les traitements asynchrones.

---

## 🏛 Architecture des Services (Docker)

L'application repose sur une architecture multi-conteneurs isolée sur un réseau privé virtuel (`backend_network`) :

```
[ Client / Navigateur / Application mobile ]
                     │
                     ▼ (Port 8000)
       ┌───────────────────────────┐
       │      NGINX (Proxy)        │  <-- Réception des requêtes web & sécurité
       └─────────────┬─────────────┘
                     │ (FastCGI :9000)
                     ▼
       ┌───────────────────────────┐
       │       PHP 8.3 - FPM       │  <───> [ PostgreSQL 16 ] (Base relationnelle)
       │    (Application Laravel)  │
       └─────────────┬─────────────┘
                     │
                     ├───> [ REDIS 7 ] (Cache, Sessions & Files d'attente)
                     │
                     └───> [ Mailpit ] (Capture des emails en dev - Port 8025)
```

### Rôle de chaque service :
* **Nginx** : Serveur web et reverse proxy haute performance. Il gère la redirection FastCGI, les timeouts et masque les fichiers sensibles (`.env`, `.git`).
* **PHP 8.3 FPM** : Interpréteur applicatif équipé des extensions requises (`pdo_pgsql`, `redis`, `bcmath`, `pcntl`).
* **PostgreSQL 16** : Base de données relationnelle stricte (modélisation financière, clés étrangères, indexation optimisée).
* **Redis 7** : Serveur de données en mémoire vive (RAM) pour les sessions ultra-rapides, le cache et les files de traitement asynchrone (*queues*).
* **Mailpit** : Serveur SMTP de développement interceptant les emails pour consultation via une interface web locale.

---

## 🚀 Démarrage Rapide

### Prérequis
* **Docker** et **Docker Compose** installés et démarrés.

### Installation et lancement
```bash
# 1. Cloner le projet
git clone https://github.com/essentiel2021/plateforme-facturation-b2b.git
cd plateforme-facturation-b2b

# 2. Démarrer l'ensemble des conteneurs
docker compose up -d

# 3. Exécuter les migrations de base de données
docker compose exec app php artisan migrate
```

---

## 🔍 Points d'Accès & Vérifications

* **API Healthcheck (Vérification globale de la stack) :**  
  [http://localhost:8000/api/health](http://localhost:8000/api/health)  
  *Retourne l'état de santé de Nginx, PHP, PostgreSQL et Redis.*

* **Interface Mailpit (Boîte de réception des emails) :**  
  [http://localhost:8025](http://localhost:8025)

---

## 🔒 Sécurité & Bonnes Pratiques Appliquées
* **Authentification sécurisée :** Gestion des jetons via **Laravel Sanctum**.
* **Contrôle d'accès granulaire :** Politiques d'autorisation (**Policies & RBAC**) pour cloisonner les données entre organisations.
* **Validation stricte :** Toutes les données entrantes sont filtrées via des **Form Requests**.
* **Traitements en arrière-plan :** Tâches lourdes (génération de factures PDF, envoi d'emails) déportées sur **Redis Queues & Workers** pour maintenir une API instantanée.
* **Précision financière :** Utilisation de l'extension **BCMath** pour garantir zéro imprécision sur les calculs monétaires.

---

## 📄 Licence
Projet sous licence [MIT](LICENSE).
