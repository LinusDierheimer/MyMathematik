var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .addEntry('home', './assets/js/home/home.js')
    .addEntry('videos', './assets/js/videos/videos.js')
    .addEntry('login', './assets/js/account/login.js')
    .addEntry('register', './assets/js/account/register.js')
    .addEntry('me', './assets/js/account/me.js')
    .addEntry('videoconfig', './assets/js/admin/videoconfig.js')
    .cleanupOutputBeforeBuild()
    .enableSingleRuntimeChunk()
    .splitEntryChunks()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
