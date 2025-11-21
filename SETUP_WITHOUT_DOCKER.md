# Installation sans Docker (Windows avec WAMP)

Ce guide vous permet d'installer et d'exécuter le projet Symfony sans Docker, en utilisant WAMP qui est déjà installé sur votre système.

## Prérequis

- WAMP installé (déjà présent sur votre système)
- PHP 8.2+ (disponible dans WAMP)
- Composer installé
- PostgreSQL installé (ou utiliser SQLite)

## Installation

### 1. Installer PostgreSQL (optionnel)

Si vous voulez utiliser PostgreSQL au lieu de SQLite :

1. Télécharger PostgreSQL : https://www.postgresql.org/download/windows/
2. Installer avec les paramètres par défaut
3. Créer une base de données :
   ```sql
   CREATE DATABASE app;
   ```

### 2. Configurer l'environnement

```bash
cd api-platform-project
```

Créer un fichier `.env.local` :

```env
APP_ENV=dev
APP_SECRET=!ChangeMe!

# Pour SQLite (plus simple, pas besoin de PostgreSQL)
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

# OU pour PostgreSQL
# DATABASE_URL="postgresql://postgres:password@127.0.0.1:5432/app?serverVersion=16&charset=utf8"

FRONTEND_URL=http://localhost:5173
MAILER_DSN=null://null
```

### 3. Installer les dépendances

```bash
composer install
```

### 4. Configurer la base de données

**Avec SQLite (recommandé pour le développement) :**

```bash
# Créer le répertoire var s'il n'existe pas
mkdir -p var

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction
```

**Avec PostgreSQL :**

```bash
# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction
```

### 5. Générer les clés JWT

```bash
php bin/console lexik:jwt:generate-keypair --skip-if-exists
```

### 6. Créer un utilisateur admin

```bash
php bin/console app:create-user --email=admin@example.com --password=admin --roles=ROLE_SUPER_ADMIN
```

### 7. Démarrer le serveur Symfony

```bash
# Option 1 : Serveur Symfony (recommandé)
symfony server:start

# Option 2 : PHP built-in server
php -S localhost:8000 -t public
```

## Accès

- **API** : http://localhost:8000/api
- **Documentation API** : http://localhost:8000/api/docs

## Configuration WAMP (Alternative)

Si vous préférez utiliser Apache de WAMP :

1. **Configurer un Virtual Host dans Apache**

   Éditer `C:\wamp64\bin\apache\apache2.4.x\conf\extra\httpd-vhosts.conf` :

   ```apache
   <VirtualHost *:80>
       ServerName symfony-api.local
       DocumentRoot "C:/Users/menya/Desktop/symfony/api-platform-project/public"
       
       <Directory "C:/Users/menya/Desktop/symfony/api-platform-project/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

2. **Ajouter dans hosts**

   Éditer `C:\Windows\System32\drivers\etc\hosts` :

   ```
   127.0.0.1 symfony-api.local
   ```

3. **Redémarrer Apache dans WAMP**

4. **Accéder à** : http://symfony-api.local/api

## Commandes utiles

```bash
# Vider le cache
php bin/console cache:clear

# Voir les routes
php bin/console debug:router

# Créer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate
```

