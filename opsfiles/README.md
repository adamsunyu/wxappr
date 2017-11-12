# After upload the files to server, change the owner and group for the files

# Clone code from git server
git clone wxappr.git

#  Get composer
curl -s http://getcomposer.org/installer | php

# Run composer install
php composer.phar install

# Change folder owner:group
chown -R www-data:www-data app/logs
chown -R www-data:www-data app/cache
chown -R www-data:www-data public/avatars
