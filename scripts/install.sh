echo "installing missing composer dependencies"
php73 /usr/bin/composer install

echo "install missing npm dependencies"
npm install

rm -r node_modules/function-plot/.git/

echo -e "\e[35mtrying to fix possible security issues...\e[0m"
npm audit fix

scripts/build.sh