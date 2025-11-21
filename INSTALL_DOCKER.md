# Installation de Docker sur Windows

## Option 1 : Docker Desktop (Recommandé)

### Étapes d'installation

1. **Télécharger Docker Desktop**

    - Allez sur : https://www.docker.com/products/docker-desktop/
    - Téléchargez Docker Desktop pour Windows
    - Exécutez l'installateur

2. **Configuration requise**

    - Windows 10 64-bit : Pro, Enterprise, ou Education (Build 19041 ou supérieur)
    - Windows 11 64-bit : Home ou Pro version 21 ou supérieure
    - WSL 2 activé (Windows Subsystem for Linux 2)
    - Virtualisation activée dans le BIOS

3. **Activer WSL 2** (si nécessaire)

    ```powershell
    # Ouvrir PowerShell en tant qu'administrateur
    wsl --install
    # Redémarrer l'ordinateur
    ```

4. **Vérifier l'installation**
    ```bash
    docker --version
    docker compose version
    ```

## Option 2 : Utiliser WAMP sans Docker

Si vous préférez ne pas installer Docker, vous pouvez utiliser WAMP qui est déjà installé sur votre système :

### Configuration avec WAMP

1. **Configurer la base de données PostgreSQL**

    - Installer PostgreSQL pour Windows
    - Créer une base de données `app`
    - Configurer les identifiants dans `.env`

2. **Utiliser PHP de WAMP**

    - PHP est déjà disponible dans : `C:\wamp64\bin\php\php8.2.13`
    - Utiliser directement les commandes Symfony

3. **Configurer Nginx ou Apache**
    - Utiliser le serveur web de WAMP
    - Ou installer Nginx séparément

## Vérification rapide

Après installation de Docker Desktop, testez avec :

```bash
docker run hello-world
```

Si cette commande fonctionne, Docker est correctement installé.

## Démarrer le projet avec Docker

Une fois Docker installé :

```bash
cd api-platform-project
cp env.docker.example .env
docker compose up -d
```

## Problèmes courants

### Docker Desktop ne démarre pas

-   Vérifier que la virtualisation est activée dans le BIOS
-   Vérifier que WSL 2 est installé et à jour
-   Redémarrer l'ordinateur

### Erreur "WSL 2 installation is incomplete"

```powershell
# Mettre à jour WSL
wsl --update
```

### Docker Desktop demande de se connecter

-   Créer un compte Docker (gratuit) ou ignorer cette étape
