# API Platform Project

Projet Symfony avec API Platform, dockerisé, pour gérer une bibliothèque avec les entités : User, Editeur, Auteur, Categorie, et Livre.

## Prérequis

- PHP 8.2+ avec extensions : pdo, pdo_sqlite, json, openssl
- Composer

## Installation (Sans Docker - Recommandé pour le développement)

1. Installer les dépendances :
```bash
composer install
```

2. Le fichier `.env.local` est déjà configuré pour SQLite

3. Exécuter les migrations :
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

4. Créer un utilisateur super admin :
```bash
php bin/console app:create-user --email=admin@example.com --password=admin --roles=ROLE_SUPER_ADMIN
```

5. Démarrer le serveur :
```bash
php -S localhost:8000 -t public
```

## Installation (Avec Docker)

Si Docker est installé, vous pouvez utiliser :

```bash
docker compose up -d
docker compose exec php php bin/console doctrine:migrations:migrate
docker compose exec php php bin/console app:create-user --email=admin@example.com --password=admin --roles=ROLE_SUPER_ADMIN
```

## Utilisation

L'API est accessible sur `http://localhost:8000/api`

Documentation API : `http://localhost:8000/api/docs`

## Rôles

- ROLE_SUPER_ADMIN : Accès complet, peut gérer les utilisateurs
- ROLE_ADMIN : Peut gérer les livres, auteurs, éditeurs, catégories
- ROLE_USER : Accès en lecture seule aux livres

## Endpoints

- POST `/api/login_check` : Connexion (retourne un token JWT)
- GET `/api/me` : Informations de l'utilisateur connecté
- GET `/api/livres` : Liste des livres
- GET `/api/auteurs` : Liste des auteurs
- GET `/api/editeurs` : Liste des éditeurs
- GET `/api/categories` : Liste des catégories
- GET `/api/users` : Liste des utilisateurs (SUPER_ADMIN uniquement)

