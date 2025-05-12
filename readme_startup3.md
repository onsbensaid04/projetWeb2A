# 💼 Startup Academy – Plateforme d’outils pour startups

Bienvenue dans **Startup Academy**, une application web complète développée en **PHP** avec **MySQL** via **XAMPP**. Ce projet propose une suite d'outils intégrés pour les jeunes entreprises, dont un forum, une bibliothèque de cours, un système de réclamations, un espace de publication d’offres d’emploi et un module de gestion des utilisateurs.

---

## 🧩 Modules Principaux

### 📚 Supports de cours
- Téléversement de fichiers (PDF, vidéos)
- Catégorisation par thème (dev, design, etc.)
- Visualisation intégrée
- Fonction de recherche et pagination

### 🛠️ Réclamations
- Envoi de réclamations avec suivi
- Statut : en attente, en cours, résolu
- Tableau d’administration pour traitement

### 🗨️ Forum / Discussion
- Interface type Discord
- Anonymat optionnel
- Gestion des canaux
- Sécurité contre les injections SQL
- Utilisation de **Sight Engine** pour la modération des messages (détection de contenu inapproprié).
- Intégration de **VirusTotal** pour vérifier les liens URL partagés dans les discussions.

### 💼 Offres d'emploi
- Publication d’offres avec description, entreprise, localisation
- Candidature via formulaire + CV
- CRUD complet pour les recruteurs

### 👤 Authentification & Utilisateurs
- Connexion/inscription
- Rôles (admin, utilisateur, formateur, recruteur)
- Gestion des comptes (modifier, supprimer, ajouter)
- Hachage des mots de passe (bcrypt)

### 🎯 Gestion des tests et quiz interactifs
- Création de tests et quiz liés aux cours
- Calcul automatique du score et progression dynamique
- Attribution automatique de badges (Débutant, Intermédiaire, Expert)
- Statistiques détaillées sur les résultats

---

## 🛠️ Technologies utilisées

- **Langage serveur** : PHP 
- **Base de données** : MySQL
- **Serveur local** : XAMPP (Apache + MySQL)
- **Frontend** : HTML, CSS, Bootstrap, JavaScript
- **Sécurité** : Validation côté client/serveur, requêtes préparées, sessions sécurisées

---

## ⚙️ Installation (XAMPP)

### 1. Cloner ou télécharger le projet
Placez le dossier du projet dans : "C:\xampp\htdocs\"

### 2. Lancer XAMPP
- Démarrer **Apache** et **MySQL** via le panneau de contrôle

### 3. Créer la base de données
- Accédez à `http://localhost/phpmyadmin`
- Créez une base nommée `startup_academy`
- Importez le fichier `startup_academy.sql` (fourni dans le dossier `/database`)

### 4. Configuration
Dans `config/database.php`, modifiez si nécessaire :

```php
$host = 'localhost';
$dbname = 'startup_academy';
$user = 'root';
$password = '';

###### 5. Lancer le projet

Accédez à :

[http://localhost/startup-academy/](http://localhost/startup-academy/)


startup-academy/
├── controllers/ 
│ ├── ForumController.php
│ ├── ReclamationController.php
│ ├── UserController.php
│ ├── CoursController.php
│ ├── QuizController.php
│ └── BadgeController.php
├── models/ 
│ ├── Forum.php
│ ├── Reclamation.php
│ ├── User.php
│ ├── Cours.php
│ ├── Quiz.php
│ └── Badge.php
├── views/
│ ├── front/ 
│ │ ├── forum/
│ │ ├── supports/
│ │ ├── emplois/
│ │ ├── quiz/
│ │ └── users/
│ └── back/ 
│ ├── dashboard/
│ ├── reclamations/
│ ├── utilisateurs/
│ └── quiz/
├── includes/ #
│ ├── header.php
│ ├── footer.php
│ └── database.php
├── config/
│ ├── database.php
├── index.php 
└── README.md


## 👨‍💻 Équipe

- **Jaibi Mohammed Amine** – Réclamations
- **Ons Ben Said ** – Cours
- **Aouadi Mohammed Yassine** – Forum
- **Klibi Fatma** – Offres d’emploi
- **Mariam Hamdi** – Utilisateurs
- **Salma Ben Rjeb** – Test et Quiz
