# Configuration Docker pour le Backend Symfony

## Prérequis

- Docker
- Docker Compose

## Démarrage rapide

### Méthode automatique (recommandée)

**Linux/Mac :**
```bash
chmod +x docker-start.sh
./docker-start.sh
```

**Windows :**
```bash
docker-start.bat
```

### Méthode manuelle

1. Copier le fichier d'environnement :
```bash
cp env.docker.example .env
```

2. Démarrer les conteneurs :
```bash
docker compose up -d
```

3. Installer les dépendances :
```bash
docker compose exec php composer install
```

4. Exécuter les migrations :
```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

5. Générer les clés JWT :
```bash
docker compose exec php php bin/console lexik:jwt:generate-keypair --skip-if-exists
```

6. Créer un utilisateur admin :
```bash
docker compose exec php php bin/console app:create-user --email=admin@example.com --password=admin --roles=ROLE_SUPER_ADMIN
```

## Services disponibles

- **API Symfony** : http://localhost:8000
- **PostgreSQL** : localhost:5432
- **Documentation API** : http://localhost:8000/api/docs

## Commandes utiles

### Voir les logs
```bash
docker compose logs -f php
docker compose logs -f nginx
docker compose logs -f database
```

### Accéder au conteneur PHP
```bash
docker compose exec php sh
```

### Exécuter des commandes Symfony
```bash
docker compose exec php php bin/console [commande]
```

### Arrêter les conteneurs
```bash
docker compose down
```

### Arrêter et supprimer les volumes
```bash
docker compose down -v
```

### Reconstruire les images
```bash
docker compose build --no-cache
```

## Configuration

### Variables d'environnement

Modifier le fichier `.env` pour configurer :
- `DATABASE_URL` : URL de connexion à la base de données
- `FRONTEND_URL` : URL du frontend (pour les liens de réinitialisation de mot de passe)
- `MAILER_DSN` : Configuration du mailer (ex: `smtp://user:pass@smtp.example.com:587`)
- `APP_SECRET` : Clé secrète de l'application
- `JWT_PASSPHRASE` : Phrase secrète pour les clés JWT

### Ports

- **Nginx** : 8000 (modifiable via `NGINX_PORT`)
- **PostgreSQL** : 5432 (modifiable via `POSTGRES_PORT`)

## Dépannage

### Problème de permissions
```bash
docker compose exec php chown -R www-data:www-data /var/www/html/var
```

### Réinitialiser la base de données
```bash
docker compose exec php php bin/console doctrine:database:drop --force
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### Vider le cache
```bash
docker compose exec php php bin/console cache:clear
```

