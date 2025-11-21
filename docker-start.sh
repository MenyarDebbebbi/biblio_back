#!/bin/bash

set -e

echo "🚀 Démarrage de l'environnement Docker pour Symfony API..."

if [ ! -f .env ]; then
    echo "📝 Création du fichier .env à partir de env.docker.example..."
    cp env.docker.example .env
fi

echo "🐳 Construction et démarrage des conteneurs..."
docker compose up -d --build

echo "⏳ Attente que les services soient prêts..."
sleep 10

echo "📦 Installation des dépendances Composer..."
docker compose exec -T php composer install --no-interaction

echo "🗄️  Exécution des migrations..."
docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction || echo "⚠️  Les migrations ont peut-être déjà été exécutées"

echo "🔑 Génération des clés JWT..."
docker compose exec -T php php bin/console lexik:jwt:generate-keypair --skip-if-exists || echo "⚠️  Les clés JWT existent déjà"

echo "✅ L'environnement Docker est prêt !"
echo ""
echo "📋 Services disponibles :"
echo "   - API Symfony : http://localhost:8000"
echo "   - Documentation API : http://localhost:8000/api/docs"
echo "   - PostgreSQL : localhost:5432"
echo ""
echo "💡 Commandes utiles :"
echo "   - Voir les logs : docker compose logs -f"
echo "   - Arrêter : docker compose down"
echo "   - Accéder au conteneur PHP : docker compose exec php sh"

