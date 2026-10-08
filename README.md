# 🎵 Projet API Music — Slim 4

**BTS CIEL — Valeria Rodriguez**

API REST développée en **PHP avec Slim 4**, permettant de gérer des **artistes, albums et notes**, avec une authentification **JWT** et une base de données **MySQL**.

---

## 🎯 Objectif

L'objectif du projet est de concevoir une API REST permettant de :

* 🎤 gérer les artistes ;
* 💿 gérer leurs albums ;
* ⭐ gérer les notes attribuées aux artistes ;
* 🔐 sécuriser les routes avec une authentification JWT ;
* 🗄️ communiquer avec une base de données MySQL ;
* 🌐 déployer l'API sur **Alwaysdata**.

---

## 🛠️ Technologies utilisées

| Technologie          | Utilisation                                   |
| -------------------- | --------------------------------------------- |
| **PHP 8.2**          | Langage principal                             |
| **Slim 4**           | Micro-framework REST                          |
| **PHP-DI**           | Injection de dépendances                      |
| **PDO / MySQL**      | Accès à la base de données                    |
| **firebase/php-jwt** | Authentification JWT                          |
| **Monolog**          | Gestion des logs                              |
| **Postman**          | Tests de l'API                                |
| **PhpStorm**         | Développement et déploiement SFTP             |
| **Alwaysdata**       | Hébergement de l'API et de la base de données |
| **Git / GitHub**     | Gestion du versioning                         |

---

## 📁 Architecture du projet

```text
api_music/
│
├── app/
│   ├── settings.php
│   ├── dependencies.php
│   ├── repositories.php
│   ├── middleware.php
│   └── routes.php
│
├── src/
│   ├── Entity/
│   │   ├── Artist.php
│   │   ├── Album.php
│   │   └── Rating.php
│   │
│   ├── Repository/
│   │   ├── BaseRepository.php
│   │   ├── ArtistRepository.php
│   │   ├── AlbumRepository.php
│   │   └── RatingRepository.php
│   │
│   ├── Middleware/
│   │   ├── JwtHelper.php
│   │   └── JwtMiddleware.php
│   │
│   └── routes/
│       ├── routesJWT.php
│       └── routesApi.php
│
├── public/
│   ├── index.php
│   └── .htaccess
│
├── composer.json
├── composer.lock
└── README.md
```

### 📦 `app/`

Contient la configuration et la préparation des différents services de l'application.

* `settings.php` → configuration générale et connexion à la base de données.
* `dependencies.php` → déclaration des dépendances utilisées par PHP-DI.
* `repositories.php` → déclaration des différents repositories.
* `middleware.php` → configuration des middlewares.
* `routes.php` → anciennes routes utilisées au début du projet.

### 🧩 `src/Entity/`

Les **Entities** représentent les données manipulées par l'API.

* `Artist.php` → représente un artiste.
* `Album.php` → représente un album.
* `Rating.php` → représente une note.

Chaque Entity permet notamment de transformer les données provenant de la base SQL en objets PHP et inversement.

### 🗄️ `src/Repository/`

Les repositories assurent la communication entre l'application et la base de données.

* `BaseRepository.php` → contient les opérations CRUD communes.
* `ArtistRepository.php` → opérations spécifiques aux artistes.
* `AlbumRepository.php` → opérations spécifiques aux albums.
* `RatingRepository.php` → opérations spécifiques aux notes.

Le `BaseRepository` permet d'éviter de répéter les mêmes opérations SQL dans chaque repository.

### 🔐 `src/Middleware/`

Gestion de l'authentification JWT.

* `JwtHelper.php` → génération et validation des tokens.
* `JwtMiddleware.php` → vérification du token avant l'accès aux routes protégées.

### 🛣️ `src/routes/`

Contient les différentes routes de l'API.

* `routesJWT.php` → authentification et routes de test JWT.
* `routesApi.php` → routes principales de l'API.

### 🌐 `public/`

Point d'entrée de l'application.

* `index.php` → initialise l'application Slim et charge les différents services et routes.
* `.htaccess` → permet la réécriture des URL avec Apache.

---

## 🗄️ Base de données

### Base locale

```text
music
```

### Base en ligne

```text
valeria_music
```

### 📊 Tables

#### Artists

| Champ         | Description              |
| ------------- | ------------------------ |
| `idArtist`    | Identifiant de l'artiste |
| `Name`        | Nom de l'artiste         |
| `Annee`       | Année                    |
| `Description` | Description              |
| `Ville`       | Ville                    |

#### Albums

| Champ      | Description              |
| ---------- | ------------------------ |
| `idAlbum`  | Identifiant de l'album   |
| `Titre`    | Titre de l'album         |
| `idArtist` | Identifiant de l'artiste |

`idArtist` est une **clé étrangère** vers la table `artists`.

#### Ratings

| Champ      | Description              |
| ---------- | ------------------------ |
| `idRating` | Identifiant de la note   |
| `stars`    | Note sur 5               |
| `idArtist` | Identifiant de l'artiste |

`idArtist` est une **clé étrangère** vers la table `artists`.

---

## 🔐 Authentification JWT

Les routes `/api/*` sont protégées par une authentification **JWT (JSON Web Token)**.

### 1. Obtenir un token

Effectuer une requête :

```http
POST /login
```

avec :

```json
{
    "username": "SaintMichel",
    "password": "ITcampus"
}
```

L'API renvoie ensuite un token JWT.

### 2. Utiliser le token

Pour accéder aux routes protégées, le token doit être envoyé dans l'en-tête HTTP :

```http
Authorization: Bearer <token>
```

### 3. Token invalide

Si le token est absent, invalide ou expiré, l'API renvoie :

```http
401 Unauthorized
```

Le token est signé avec l'algorithme **HS256** et possède une durée de validité définie dans la configuration de l'application.

---

# 🚀 Routes de l'API

## 🎤 Artists

| Méthode | Route                     | Description                    |
| ------- | ------------------------- | ------------------------------ |
| `GET`   | `/api/artists`            | Liste tous les artistes        |
| `GET`   | `/api/artists/{id}`       | Récupère un artiste            |
| `GET`   | `/api/artists/annees`     | Liste les années sans doublon  |
| `GET`   | `/api/artists/villes`     | Liste les villes sans doublon  |
| `GET`   | `/api/artists/{id}/ville` | Récupère la ville d'un artiste |
| `GET`   | `/api/artists/{id}/annee` | Récupère l'année d'un artiste  |
| `POST`  | `/api/artists`            | Ajoute un artiste              |

### Exemple — Ajouter un artiste

```json
{
    "Name": "Nom de l'artiste",
    "Annee": 2024,
    "Description": "Description de l'artiste",
    "Ville": "Paris"
}
```

---

## 💿 Albums

| Méthode | Route                           | Description                   |
| ------- | ------------------------------- | ----------------------------- |
| `GET`   | `/api/albums`                   | Liste tous les albums         |
| `GET`   | `/api/albums/{id}`              | Récupère un album             |
| `GET`   | `/api/albums/artist/{artistId}` | Liste les albums d'un artiste |
| `POST`  | `/api/albums`                   | Ajoute un album               |

### Exemple — Ajouter un album

```json
{
    "Titre": "Nom de l'album",
    "idArtist": 1
}
```

---

## ⭐ Ratings

| Méthode | Route                            | Description                  |
| ------- | -------------------------------- | ---------------------------- |
| `GET`   | `/api/ratings`                   | Liste toutes les notes       |
| `GET`   | `/api/ratings/{id}`              | Récupère une note            |
| `GET`   | `/api/ratings/artist/{artistId}` | Liste les notes d'un artiste |
| `POST`  | `/api/ratings`                   | Ajoute une note              |

### Exemple — Ajouter une note

```json
{
    "stars": 5,
    "idArtist": 1
}
```

---

## 🧪 Routes historiques

Les premières routes développées au début du projet sont également présentes :

```http
GET  /GetAllArtist
GET  /getArtistById/{id}
POST /AddArtist
GET  /protected
```

La route `/protected` permet notamment de tester le fonctionnement de l'authentification JWT.

---

# 🌐 Déploiement

L'API est hébergée sur **Alwaysdata**.

### Configuration

* **Hébergement :** Alwaysdata
* **Serveur web :** Apache
* **Version PHP :** 8.2
* **Base de données :** MySQL
* **Déploiement :** SFTP depuis PhpStorm

### 📂 Répertoire distant

```text
/home/valeria/www/api_music
```

Le dossier `public/` est configuré comme **racine web** de l'application.

La base de données `valeria_music` a été créée et importée sur Alwaysdata.

---

# 📤 Git & GitHub

Le projet est versionné avec **Git** et hébergé sur GitHub.

**Dépôt :**
https://github.com/valeriarodriguez05/api_phpstorm

Le fichier `.gitignore` permet notamment d'exclure les éléments sensibles ou inutiles au versionnement :

```text
/vendor/
/var/
app/settings.php
.idea/
```

Le fichier `settings.php` n'est pas versionné car il contient notamment les informations de connexion à la base de données.

---

## 👩‍💻 Auteur

**Valeria Rodriguez**
BTS CIEL

Projet réalisé dans le cadre de la formation **BTS Cybersécurité, Informatique et réseaux, Électronique**.
