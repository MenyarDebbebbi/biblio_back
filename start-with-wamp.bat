@echo off
setlocal enabledelayedexpansion

echo 🚀 Configuration du projet Symfony avec WAMP...

cd /d "%~dp0"

if not exist .env.local (
    echo 📝 Création du fichier .env.local...
    (
        echo APP_ENV=dev
        echo APP_SECRET=!ChangeMe!
        echo.
        echo DATABASE_URL="sqlite:///%%kernel.project_dir%%/var/data.db"
        echo.
        echo FRONTEND_URL=http://localhost:5173
        echo MAILER_DSN=null://null
    ) > .env.local
)

echo 📦 Vérification de Composer...
where composer >nul 2>&1
if errorlevel 1 (
    echo ❌ Composer n'est pas installé. Installez-le depuis https://getcomposer.org/
    pause
    exit /b 1
)

echo 📦 Installation des dépendances...
composer install --no-interaction

echo 📁 Création du répertoire var...
if not exist var mkdir var

echo 🗄️  Exécution des migrations...
php bin/console doctrine:migrations:migrate --no-interaction || echo ⚠️  Les migrations ont peut-être déjà été exécutées

echo 🔑 Génération des clés JWT...
php bin/console lexik:jwt:generate-keypair --skip-if-exists || echo ⚠️  Les clés JWT existent déjà

echo ✅ Configuration terminée !
echo.
echo 📋 Pour démarrer le serveur :
echo    php -S localhost:8000 -t public
echo.
echo    OU
echo.
echo    symfony server:start
echo.
echo 🌐 Accès :
echo    - API : http://localhost:8000/api
echo    - Documentation : http://localhost:8000/api/docs
echo.

pause

