echo "installing missing composer dependencies"
composer install

echo "install missing npm dependencies"
npm install

scripts/build.sh