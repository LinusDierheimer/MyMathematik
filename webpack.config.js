var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('base', './assets/js/app.js')
    .addEntry('home', './assets/js/home/home.js')
    .addEntry('videos', './assets/js/videos/videos.js')
    .addEntry('login', './assets/js/account/login.js')
    .addEntry('register', './assets/js/account/register.js')
    .addEntry('me', './assets/js/account/me.js')
    .addEntry('videoconfig', './assets/js/admin/videoconfig.js')
    .addEntry('languageconfig', './assets/js/admin/languageconfig.js')
    .addEntry('contact', './assets/js/information/contact.js')
    .addEntry('cookies', './assets/js/information/cookies.js')
    .addEntry('impressum', './assets/js/information/impressum.js')
    .addEntry('sponsors', './assets/js/information/sponsors.js')
    .addEntry('conditions', './assets/js/information/conditions.js')
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/videos',
        to: 'videos/[path][name].[ext]'
    })
    .cleanupOutputBeforeBuild()
    .enableSassLoader()
    .autoProvidejQuery()
    .disableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
