scripts/build.sh

echo creating zip
zip -q MyMathematik.zip -r config/ public/ src/ templates/ translations/ vendor/ .env .env.test composer.json