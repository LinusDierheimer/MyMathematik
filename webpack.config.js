var Encore = require("@symfony/webpack-encore");

Encore
    .setOutputPath("public/build/")
    .setPublicPath("/build")
    .addEntry("app", "./assets/js/app.js")
    .cleanupOutputBeforeBuild()
    .enableSassLoader()
    .autoProvidejQuery()
    .disableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
