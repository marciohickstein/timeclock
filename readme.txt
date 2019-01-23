Ubuntu 14.04 LTS

Activate the mod_rewrite module with:

sudo a2enmod rewrite

sudo service apache2 restart

To use mod_rewrite from within .htaccess files (which is a very common use case), edit the default VirtualHost with

sudo vim /etc/apache2/sites-available/000-default.conf

Below ?DocumentRoot /var/www/html? add the following lines:

<Directory ?/var/www/html?>
AllowOverride All
</Directory>

Create .htaccess on root project

echo 'RewriteEngine On' > .htaccess
echo 'RewriteCond %{REQUEST_FILENAME} !-f'  >> .htaccess
echo 'RewriteCond %{REQUEST_FILENAME} !-d'  >> .htaccess
echo 'RewriteRule ^ index.php [QSA,L]'  >> .htaccess

