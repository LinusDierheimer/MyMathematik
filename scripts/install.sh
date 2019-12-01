echo "installing missing composer dependencies"
php72 /usr/bin/composer install

echo "install missing npm dependencies"
npm install

scripts/build.sh