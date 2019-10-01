echo installing missing npm packages
npm install
echo install missing composer packages
composer install
echo building assets
npm run build
echo creating zip
zip -q MyMathematik.zip -r config/ public/ src/ templates/ translations/ vendor/ .env .env.test composer.json