# Budgetary

## Requirements

1. LAMP stack (Any OS + Apache + PHP + MySQL)
2. [Compose](https://getcomposer.org/download/)
3. [Firebase account](http://firebase.google.com/)
4. (OPTIONAL) [npm](https://nodejs.org/)

## Steps

1. Clone repo 
2. Run `php composer.phar install` to get EasyDB and Firebase SDK
3. Get `service account` key from Firebase and place it within `../../secret/service-account.json` of directory
4. Import `sql/manager.sql` into MySQL DB
5. (OPTIONAL) Get database credentials(`dbhost`,`dbuser`,`dbpass`,`accdb`) and place it within `../../secret/budgetary-dbconfig.php` of directory
6. (OPTIONAL) Run `npm install` to get development packages

## Setup webhook

Refer to https://github.com/markomarkovic/simple-php-git-deploy

Run `sudo -u www-data ssh -T git@github.com` to add github to list of known hosts.

## References

Commands for db: https://github.com/paragonie/easydb
