# 🎵 RetroCommerce

Une application web e-commerce dédiée à la vente de vinyles, construite avec **Symfony**.

---

##  Stack technique

| Technologie | Rôle |
|---|---|
| PHP / Symfony | Framework back-end |
| Twig | Moteur de templates front-end |
| JavaScript / Webpack Encore | Assets front-end |
| XAMPP | Serveur local (Apache + MySQL) |
| Doctrine + Migrations | ORM et gestion de base de données |

---

##  Structure du projet

```
vinyles-e-commerce/
├── assets/          # Fichiers JS/CSS source
├── bin/             # Console Symfony
├── config/          # Configuration de l'application
├── migrations/      # Migrations de base de données
├── public/          # Point d'entrée web
├── src/             # Code source PHP (Controllers, Entities, etc.)
├── templates/       # Templates Twig
├── webpack.config.js      # Configuration Webpack Encore
└── .env             # Variables d'environnement
```

---

##  Installation et lancement

### Prérequis

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)
- [PHP 8+](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)
- [Node.js](https://nodejs.org/) (pour la compilation des assets)

### 1. Cloner le dépôt

```bash
git clone https://github.com/Amiralahiani/vinyles-e-commerce.git
cd vinyles-e-commerce
```

### 2. Configurer les variables d'environnement

```bash
cp .env .env.local
# Modifier .env.local selon votre configuration (notamment DATABASE_URL)
```

### 3. Installer les dépendances PHP

```bash
composer install
```

### 4. Installer les dépendances JS et compiler les assets

```bash
npm install
npm run dev
```

### 5. Démarrer XAMPP

Lancer **Apache** et **MySQL** depuis le panneau de contrôle XAMPP.

### 6. Créer la base de données et exécuter les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 7. (Optionnel) Charger les fixtures

```bash
php bin/console doctrine:fixtures:load
```

### 8. Lancer le serveur Symfony

```bash
symfony serve
```

---

##  Accès à l'application

Une fois le serveur démarré, l'application est disponible sur :

```
http://127.0.0.1:8000
```

---

##  Commandes utiles

```bash
# Vider le cache Symfony
php bin/console cache:clear

# Créer une nouvelle migration
php bin/console make:migration

# Compiler les assets en mode watch
npm run watch

# Lancer le serveur en arrière-plan
symfony serve -d

# Arrêter le serveur
symfony server:stop
```

---

##  Variables d'environnement

| Variable | Description |
|---|---|
| `DATABASE_URL` | URL de connexion à la base de données |
| `APP_ENV` | Environnement (`dev`, `prod`) |
| `APP_SECRET` | Clé secrète Symfony |

---
