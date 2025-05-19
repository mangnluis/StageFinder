# CE PROJET A ETE CONCUS POUR UN OBJECTIF PEDAGOGIQUE POUR LE CESI NANTERRE PAR OTHMANE BEKALLI, LOUIS MANGIN, GEORGES ZAMFIROIU ET YASMINE DJEKKHAR.

# StageFinder – Architecture du site

Ce README.md contient l'ensemble des explications des fichiers destinés à être servis côté client (frontend), ainsi que certains éléments de traitement côté serveur si nécessaire. Ce document décrit l'architecture générale du site pour aider à la compréhension, la maintenance et l’évolution du projet. Pour une démo envoyé un mail à : louis.mangin.2004@gmail.com

---

## 1. Structure générale des dossiers et fichiers

```
public_html/
├── assets/           # Fichiers statiques (images, CSS, JS, polices…)
├── includes/         # Fichiers PHP inclus dynamiquement (header, footer, config…)
├── pages/            # Pages principales du site (home, offres, profil, etc.)
├── api/              # Points d’entrée pour les requêtes AJAX (PHP/API)
├── index.php         # Point d’entrée principal du site
├── .htaccess         # Configuration Apache pour les URLs et la sécurité
└── README.md         # Documentation de l’architecture (ce fichier)
```

---

## 2. Détail des principaux dossiers

- **assets/**  
  Contient toutes les ressources statiques nécessaires à l'affichage du site :
  - `css/` : feuilles de style
  - `js/` : scripts JavaScript
  - `img/` : images et icônes

- **includes/**  
  Composants PHP réutilisables par plusieurs pages, tels que :
  - `header.php`, `footer.php` : en-tête et pied de page communs
  - `config.php` : configuration globale (connexion à la base de données, constantes…)

- **pages/**  
  Chaque page majeure du site a son propre fichier, par exemple :
  - `home.php` : page d’accueil
  - `offres.php` : liste des offres de stage
  - `profil.php` : espace personnel de l’utilisateur

- **api/**  
  Scripts PHP qui servent de points d’accès pour les requêtes asynchrones (AJAX/fetch), par exemple :
  - Récupération ou envoi de données (offres, candidature, etc.)
  - Authentification et gestion de session

---

## 3. Fonctionnement général

- **Navigation**  
  La navigation s'effectue principalement via des liens qui chargent de nouvelles pages PHP. Pour une expérience plus fluide, certaines fonctionnalités utilisent AJAX pour charger ou envoyer des données sans recharger la page.

- **Séparation Frontend/Backend**  
  - **Frontend** : HTML, CSS, et JS (dans `assets/`) gèrent l’affichage et l’interactivité côté client.
  - **Backend** : PHP (dans `pages/`, `includes/`, `api/`) traite les requêtes, génère le contenu dynamique, interagit avec la base de données.

- **Gestion des utilisateurs**  
  Des sessions PHP sont utilisées pour gérer l’authentification et la persistance des utilisateurs connectés. Les informations sensibles ne transitent jamais par le frontend sans contrôle.

---

## 4. Exemple de flux de page

1. L’utilisateur accède à `index.php` (accueil).
2. Selon l’action ou la page demandée, le script PHP principal va inclure les composants nécessaires (`includes/header.php`, la page demandée dans `pages/`, puis `includes/footer.php`).
3. Pour des interactions dynamiques (par exemple, postuler à une offre), le frontend envoie une requête AJAX à un script dans `api/`.
4. Le script `api/` traite la demande, interroge si besoin la base de données, puis renvoie une réponse (souvent en JSON) au frontend.

---

## 5. Sécurité & bonnes pratiques

- Les accès directs aux dossiers sensibles sont bloqués par `.htaccess`.
- Les entrées utilisateur sont systématiquement validées et échappées côté serveur.
- Les mots de passe et données sensibles sont stockés de manière sécurisée.

---

## 6. Évolution

Cette architecture est conçue pour être modulaire et évolutive. Pour ajouter une nouvelle fonctionnalité :
- Ajouter une page dans `pages/`
- Ajouter le JS/CSS associé dans `assets/`
- Créer un endpoint dans `api/` si besoin d’une interaction dynamique
- Réutiliser les composants de `includes/` pour la cohérence

---

**Contact** : Pour toute question concernant l’architecture, contactez l’équipe de développement via le dépôt GitHub.

```
Repo : https://github.com/mangnluis/StageFinder
```
