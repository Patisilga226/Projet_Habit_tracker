# Habit Tracker

Une application de suivi des habitudes développée avec Laravel et Python pour visualiser vos progrès.

## Fonctionnalités

- Suivi des habitudes quotidiennes, hebdomadaires ou mensuelles
- Définition d'objectifs quantifiables pour chaque habitude
- Visualisation des progrès avec des graphiques générés par Python
- Analyses statistiques (taux de complétion, meilleure séquence, etc.)
- Interface utilisateur intuitive et moderne

## Configuration technique

- Backend : Laravel 10
- Visualisation des données : Python avec Matplotlib, Pandas et Seaborn
- Base de données : MySQL
- Frontend : Blade avec TailwindCSS

## Installation

### Prérequis

- PHP 8.1+
- Composer
- Node.js et npm
- Python 3.8+
- MySQL

### Étapes d'installation

1. Cloner le dépôt
```
git clone https://github.com/votre-nom/habit-tracker.git
cd habit-tracker
```

2. Installer les dépendances PHP
```
composer install
```

3. Installer les dépendances JavaScript
```
npm install
npm run dev
```

4. Installer les dépendances Python
```
pip install -r python/requirements.txt
```

5. Configurer l'environnement
```
cp .env.example .env
php artisan key:generate
```

6. Configurer la base de données dans le fichier .env

7. Exécuter les migrations
```
php artisan migrate
```

8. Démarrer le serveur
```
php artisan serve
```

## Utilisation

1. Créez un compte utilisateur
2. Ajoutez vos habitudes à suivre
3. Enregistrez votre progression quotidiennement
4. Consultez vos statistiques et graphiques de progression

## Structure de l'application

- `app/Models` : Contient les modèles Eloquent (Habit, HabitLog)
- `app/Http/Controllers` : Contrôleurs Laravel
- `database/migrations` : Migrations de base de données
- `python/` : Scripts Python pour la génération de graphiques
- `resources/views` : Templates Blade

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.
