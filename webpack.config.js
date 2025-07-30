const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    
    // PostCSS configuration for Tailwind
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            config: './postcss.config.js'
        };
    });

module.exports = Encore.getWebpackConfig();