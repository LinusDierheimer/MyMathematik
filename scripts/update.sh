echo -e "\e[35mdeleting node_modules/ if exist...\e[0m"
rm -rf node_modules/

echo -e "\e[35mdeleting package-lock.json if exist...\e[0m"
rm -f package-lock.json

echo -e "\e[35mdeleting vendor/ if exist...\e[0m"
rm -rf vendor/

echo -e "\e[35mdeleting composer.lock if exist...\e[0m"
rm -f composer.lock

echo -e "\e[35mdeleting symfony.lock if exist...\e[0m"
rm -f symfony.lock

echo -e "\e[35mupdating and installing npm dependencies...\e[0m"
npm install
npm update

echo -e "\e[35mtrying to fix possible security issues...\e[0m"
npm audit fix

echo -e "\e[35mupdating and installing composer dependencies...\e[0m"
php72 /usr/bin/composer update

echo -e "\e[35mdeleting files that were created by composer but are not needed..\e[0m"
rm -rf assets/css/
rm -f templates/base.html.twig

scripts/build.sh