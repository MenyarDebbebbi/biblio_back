#!/bin/bash

set -e

echo "🚀 Configuration du projet Symfony avec WAMP..."

cd "$(dirname "$0")"

if [ ! -f .env.local ]; then
    echo "📝 Création du fichier .env.local..."
    cat > .env.local << 'EOF'
APP_ENV=dev
APP_SECRET=!ChangeMe!

DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

FRONTEND_URL=http://localhost:5173
MAILER_DSN=null://null
EOF
fi

echo "📦 Vérification de Composer..."
if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé. Installez-le depuis https://getcomposer.org/"
    exit 1
fi

echo "📦 Installation des dépendances..."
composer install --no-interaction

echo "📁 Création du répertoire var..."
mkdir -p var

echo "🗄️  Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || echo "⚠️  Les migrations ont peut-être déjà été exécutées"

echo "🔑 Génération des clés JWT..."
php bin/console lexik:jwt:generate-keypair --skip-if-exists || echo "⚠️  Les clés JWT existent déjà"

echo "✅ Configuration terminée !"
echo ""
echo "📋 Pour démarrer le serveur :"
echo "   php -S localhost:8000 -t public"
echo ""
echo "   OU"
echo ""
echo "   symfony server:start"
echo ""
echo "🌐 Accès :"
echo "   - API : http://localhost:8000/api"
echo "   - Documentation : http://localhost:8000/api/docs"

