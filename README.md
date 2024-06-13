# AppWebSymfony

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
    sudo docker exec -it project_symfony_php bash
    composer create-project symfony/skeleton AppWebSymfonyProject
    
    (pour le supprimer : rm -rf /var/www/AppWebSymfonyProject)

### Lancer le serveur web local de Symfony
    sudo docker exec -it project_symfony_php bash
    wget https://get.symfony.com/cli/installer -O - | bash
    export PATH="$HOME/.symfony5/bin:$PATH"
    
    cd AppWebSymfonyProject/
    symfony server:start
    v
    http://127.0.0.1:8000/

### Configuration de la base de données
    // Mettre les droits d'écriture sur le fichier .env
    sudo chmod 777 .env
    DATABASE_URL="mysql://root:root@mysql:3306/symfonyprojectdb"