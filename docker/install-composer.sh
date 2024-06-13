# si ca lance pas :
# sudo lsof -i -P -n | grep LISTEN | grep 3306
# sudo service mysql stop

###############################

# charger fixtures :
# php bin/console doctrine:fixtures:load

# vérif le contenu de la table product :
# php bin/console doctrine:query:sql "SELECT * FROM product"





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