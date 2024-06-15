# AppWebSymfony

## Objectif du projet :

Développer une application web en Symfony qui permet aux utilisateurs de créer, gérer, s'inscrire et participer à des événements.
Cours / Projet sur : https://bright-caption-38d.notion.site/D-veloppement-d-une-application-web-Symfony-892a5cb49a824525b3adf1d1660af9cf

## Installation et Configuration de Symfony

### Configuration de l'environnement avec une image Docker

1. créer docker/install-composer.sh
2. créer DockerFile
3. créer docker-compose.yml

### Lancement de l'envirronement Docker
    sudo docker compose up -d --build
    v
    ✔ Container project_symfony_php  Started
    ✔ Container project_symfony_db   Started

    (pour arrêter : sudo docker compose down)

### Création d'un projet Symfony
    // Se connecter au container php
    sudo docker exec -it project_symfony_php bash
    // Créer un projet Symfony
    composer create-project symfony/skeleton AppWebSymfonyProject
    
    (pour le supprimer : rm -rf /var/www/AppWebSymfonyProject)

### Lancer le serveur web local de Symfony
    (lancer l'env docker sudo docker compose up -d --build)
    // dans le container php
    wget https://get.symfony.com/cli/installer -O - | bash
    export PATH="$HOME/.symfony5/bin:$PATH"
    
    cd AppWebSymfonyProject/
    symfony server:start
    v
    http://127.0.0.1:8000/

### Configuration de la base de données
    // mettre les droits d'écriture sur le fichier .env
    sudo chmod 777 .env
    // on utilise SQLite pour la base de données donc dans .env
    DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
    // vérifier la connexion à la base de données
    php bin/console doctrine:schema:validate

### Installations nécessaires
    // dans le container php

    // donner tous les droits (lecture, écriture et exécution) à tous les fichiers et sous-dossiers du répertoire AppWebSymfonyProject
    chmod -R 777 /var/www/AppWebSymfonyProject

    // pour créer un controller
    composer require symfony/maker-bundle --dev

    // pour installer le bundle twig
    composer require symfony/twig-bundle

    // installer le package Doctrine ORM
    composer require symfony/orm-pack

    // pour générer un formulaire
    composer require form validator 

    // pour la gestion de la sécurité
    composer require symfony/security-bundle
    composer require symfony/validator
    composer require symfony/form
    composer require symfony/mailer

    // pour l'envoi de mails va MailJet
    composer require symfony/mailjet-mailer
    composer require symfony/http-client

### Commandes utiles
    // dans le container php

    // pour créer un controller
    php bin/console make:controller AccueilController

    // pour créer une entité
    php bin/console make:entity

    // pour mettre à jour la base de données : générer des migrations
    php bin/console make:migration

    // pour exécuter les migrations
    php bin/console doctrine:migrations:migrate

    //vérif le contenu d'une table (event)
    php bin/console doctrine:query:sql "SELECT * FROM event"

    // générer un formulaire (EventType)
    php bin/console make:form EventType

    // pour créer un formulaire de connexion
    php bin/console make:auth

    // pour créer un formulaire d'inscription
    php bin/console make:registration-form

    // pour voir toutes les tables
    php bin/console doctrine:query:sql "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;"

### Configurtion de la sécurité
    // Dans le fichier config/package/security.yaml

### Configuration des voters
    // Dans le fichier config/services.yaml

### Gestion des mails de validation
    // Utilisation de MailJet
    // Dans la section API Key Management
    // Récupérer l'API KEY et la SECRET KEY
    // Paramétrer l'.env avec ces clées
    // Vérifier que Synmfony utilise correctement la configuration de mailer
    php bin/console debug:config framework mailer