var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/global.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
