var Encore = require("@symfony/webpack-encore");

Encore
    .setOutputPath("public/build/")
    .setPublicPath("/build")
    .addEntry("app", "./assets/js/app.js")
    .addEntry("plotter", "./assets/js/plotter.js")
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
