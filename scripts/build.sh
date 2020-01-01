echo "removing /var/cache/ if exists..."
sudo rm -rf /var/cache/

APP_ENV=$(grep APP_ENV .env.local | xargs)
IFS='=' read -ra APP_ENV <<< "$APP_ENV"
APP_ENV=${APP_ENV[1]}

if [ $APP_ENV = "prod" ]; then
    echo "building frontend in production mode"
    npm run build
elif [ $APP_ENV = "dev" ]; then
    echo "building frontend in dev mode"
    npm run dev
else
    echo "Warning, unrecognized env! using dev mode for npm build and cache with dev"
    npm run dev
    php73 bin/console cache:warmup --env=dev
fi

echo "warming up cache... You may need to provide your password"
php73 bin/console cache:warmup

echo "setting up file permissions... You may need to provide your password"
sudo chmod 777 -R var/cache/