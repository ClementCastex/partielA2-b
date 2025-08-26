# Location Rental - Symfony

## Prérequis
- PHP 8.2+
- Composer
- MySQL/MariaDB (base: `partielA2-b`)
- Symfony CLI (optionnel)

## Installation
```bash
composer install
```

## Configuration
Créez `.env.local` avec votre URL de base de données (exemple fourni) :
```bash
printf 'DATABASE_URL="mysql://root:toor@127.0.0.1:3306/partielA2-b?serverVersion=10.11&charset=utf8mb4"\n' > .env.local
```

## Création du schéma
```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate -n
```

## Démarrer le serveur
Avec Symfony CLI :
```bash
symfony serve -d
```
Ou avec PHP interne :
```bash
php -S 127.0.0.1:8000 -t public
```

## Fonctionnalités
- Gestion du matériel: liste, création, édition (pas de suppression)
- Commandes: création avec sélection de matériel et quantités, affichage d'une commande avec total, restitution (ré-incrémente le stock et supprime la commande)

## Routes principales
- Accueil: `/`
- Matériel: `/equipment`
- Commandes: `/orders`

## Design & Navigation
- Palette: primary `#5B348B`, gray-900 `#23272A`, gray-800 `#2C2F33`, accent `#CCAA1D`, white `#F7F3F7`.
- Styles globaux: `assets/styles/app.css` (chargé via `{{ asset('styles/app.css') }}` dans `templates/base.html.twig`).
- Layout: header sticky avec liens Accueil, Matériel, Commandes; contenu dans `.wrapper`; footer minimal.
- Composants utilitaires: `.btn`, `.btn-primary`, `.btn-outline`, `.card`, `.table`, `.badge`, `.form-group`, `.flash-*`.
- Breadcrumb: `templates/partials/_breadcrumb.html.twig`, inclus via le block `breadcrumb` de chaque page.
- Filtre Twig monnaie: `|money` (défini dans `src/Twig/AppExtension.php`).

### Ajouter une entrée de menu
Dans `templates/base.html.twig`, ajoutez un lien dans `<div class="nav-links">` et gérez l'état actif via `aria-current="page"` selon `app.request.getPathInfo()`.

## Notes
- Pas d'authentification, rendu via Twig uniquement.
