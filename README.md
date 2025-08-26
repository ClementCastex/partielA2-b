# lac de Poses - Gestion de locations (Symfony)

Application Symfony (Twig + Doctrine ORM + Migrations) pour gérer le matériel et les locations.

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
Créez `.env.local` avec votre URL de base de données (exemple) :
```bash
printf 'DATABASE_URL="mysql://nom:utilisateur@127.0.0.1:3306/nom_de_la_db?serverVersion=10.11&charset=utf8mb4"\n' > .env.local
```

## Base de données
```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate -n
```

## Lancer
Avec Symfony CLI :
```bash
symfony serve -d
```
Ou PHP interne :
```bash
php -S 127.0.0.1:8000 -t public
```

## Fonctionnalités
- Matériel: liste, création, édition (pas de suppression)
- Locations: création (ajout dynamique de lignes matériel/quantité), édition, affichage avec total, restitution (ré-incrémente le stock et supprime la location)
- Validation: pas de stock négatif, champs requis, prix formaté

## Navigation
- Accueil: `/` (stats + CTA)
- Matériel: `/equipment`
- Locations: `/orders`

## Design & Assets
- Styles globaux: `public/assets/app.css`
- Layout: `templates/base.html.twig` (navbar, flash, container)
- Composants utiles: `.btn`, `.btn-primary`, `.btn-outline`, `.card`, `.table`, `.badge`, `.form`, `.input`, `.breadcrumb`
- Filtre Twig monnaie: `|money` via `src/Twig/AppExtension.php`

## Service métier
- `src/Service/RentalService.php`
  - `createOrder(customerName, equipmentIdToQuantity)`
  - `updateOrder(order, customerName, equipmentIdToQuantity)`
  - `returnOrder(order)`
  - `calculateTotalPerDay(order)`

## Publier sur GitHub (manuel)
1) Initialiser le dépôt si besoin
```bash
git init
git add .
git commit -m "feat: initial implementation (lac de Poses)"
```
2) Créer un repo vide sur GitHub (nom suggéré: `lac-de-poses`)
3) Lier et pousser
```bash
git branch -M main
git remote add origin https://github.com/<votre-utilisateur>/lac-de-poses.git
git push -u origin main
```
