echo "installing missing composer dependencies"
php73 /usr/bin/composer install

echo "install missing npm dependencies"
npm install

scripts/build.sh