# Plateforme de webinaires (Webinaire OC MVC)

## Installation du projet
1. Récuperez ce projet et placez-le dans le dossier de votre serveur local (souvent `www` ou `htdocs`)
2. Ouvrez PHPMyAdmin, créez une base de données `webinaires`, et importez le fichier `webinaires.sql` présent dans ce dossier.
3. Modifier les identifiants de la BDD dans les fichiers `index.php`, `login.php` et `new_webinar.php`.
4. Ouvrez le projet dans votre navigateur (`http://localhost/[nom du dossier]`)
5. Pour la page de connexion, vous pouvez utiliser l'identifiant `admin` et le mot de passe `123`.

## Objectif du projet
L'objectif de ce projet est de faire évoluer ce projet vers une architecture MVC.

Voici un exemple de plan possible pour faire évoluer votre architecture :
1. Créer les dossiers de votre architecture : un dossier model, un dossier view, un dossier controller
2. Créer un fichier dans le dossier model pour chaque table de votre BDD (un pour les utilisateurs, un pour les catégories, un pour les webinaires).
    1. Créer, dans les fichiers correspondants, une fonction ou méthode pour chaque requête SQL faite sur le projet actuel
    2. Remplacer les requêtes SQL du projet actuel par les fonctions fraîchement créées.
3. Créer un fichier dans le dossier controller, et y créer une fonction par page (accueil, page de connexion, création de webinaire).
    1. Reprendre la logique du code des différents fichiers et le déplacer dans les fonctions correspondantes du contrôleur (exemple : vérification d’accès, appel des fonctions du model, vérification des formulaires, redirections, etc)
4. Dans le dossier view, créer un fichier template.php et un fichier par page
    1. Déplacer le code HTML commun aux différentes pages dans fichier template.php (header, footer, …)
    2. Reprenez le code HTML spécifique aux différentes pages dans les fichiers correspondants du dossier view.
5. Créer un fichier index.php, qui fera office de routeur
    1. Récupérer l’action de l’URL ($_GET[‘action’] par exemple), représentant la page à afficher
    2. Appelez la fonction du contrôleur correspondant à l’action (par exemple la fonction accueil si $_GET[‘action’] == ‘accueil’).
    3. Changez les différents liens des vues pour respecter le nouveau format des liens (toutes les pages doivent être appelées depuis le fichier index.php).
   