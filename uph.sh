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

echo -e "\e[35mdeleting var/cache/ if exist... You may need to provide your password\e[0m"
sudo rm -rf var/cache/

echo -e "\e[35minstalling npm packages. This requires globally installed npm...\e[0m"
npm install

echo -e "\e[35mupdating npm dependencies...\e[0m"
npm update

echo -e "\e[35mtrying to fix possible security issues...\e[0m"
npm audit fix

echo -e "\e[35mInstalling composer packages. This requires globally installed composer and php... You may need to provide your password\e[0m"
sudo chmod 777 -R ~/.composer/cache/
composer install

echo -e "\e[35mupdating composer dependencies...\e[0m"
composer update

echo -e "\e[35mdeleting files that were created by composer but are not needed..\e[0m"
rm -rf assets/css/
rm -f templates/base.html.twig

echo -e "\e[35mbuilding frontend with updated packages in dev mode. For production mode run 'npm run build' afterwards...\e[0m"
npm run dev

echo -e "\e[35mwarming up cache... You may need to provide your password\e[0m"
php bin/console cache:warmup

echo -e "\e[35msetting up file permissions... You may need to provide your password\e[0m"
sudo chmod 777 -R var/cache/

echo -e "\e[35mfinished updating packages\e[0m"