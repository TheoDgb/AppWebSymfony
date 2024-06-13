# projet : ~/Documents/GitHub/AppWebSymphony/my_project_name

# https://bright-caption-38d.notion.site/D-veloppement-d-une-application-web-Symfony-892a5cb49a824525b3adf1d1660af9cf

# mettre les droits à un fchier :
# chmod 777 /home/theo/PhpstormProjects/untitled/my_project_name/src/DataFixtures/ProductFixtures.php

# si ca lance pas :
# sudo lsof -i -P -n | grep LISTEN | grep 3306
# sudo service mysql stop

# dans untitled :
# sudo docker compose up -d --build
# sudo docker exec -it projet_symfony_php bash
# wget https://get.symfony.com/cli/installer -O - | bash
# export PATH="$HOME/.symfony5/bin:$PATH"
# dans root@7e81d466a1a1:/var/www/my_project_name# (cd my_project_name/)
# symfony server:start

# pour pouvoir créer un controlleur :
# composer require symfony/maker-bundle --dev

# pour installer le bundle twig :
# composer require symfony/twig-bundle

# installer le package Doctrine ORM :
# composer require symfony/orm-pack

# vérifier la connexion à la bdd (param dans docker-compose.yml)
# php bin/console doctrine:schema:validate


# migration si modif table :
# php bin/console make:migration

# charger fixtures :
# php bin/console doctrine:fixtures:load
# php bin/console doctrine:migrations:migrate

# php bin/console doctrine:fixtures:load

# vérif le contenu de la table product :
# php bin/console doctrine:query:sql "SELECT * FROM product"

# modifier le mdp de root (car je l'avais oublié hihi MAIS OSEF)
# UPDATE mysql.user SET authentication_string = '3680' WHERE User = 'root';
# FLUSH PRIVILEGES;




# si peut pas créer de fichiers alors hors docker : sudo chown -R nbasquin:nbasquin /home/nbasquin/PhpstormProjects/untitled/projet1/src/Controller


# docker/install-composer.sh

#
# https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
#

EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

echo "Installer signature is correct: $ACTUAL_SIGNATURE"

php composer-setup.php --version=1.10.8 && mv composer.phar /usr/local/bin/composer
RESULT=$?
rm composer-setup.php
exit $RESULT