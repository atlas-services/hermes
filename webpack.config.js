var Encore = require('@symfony/webpack-encore');
// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .addLoader({
        test: /\.(js|jsx)$/,
        loader: "babel-loader",
        options: {
            presets: ["@babel/preset-react", "@babel/preset-env"]
        }
    })
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
     .addEntry('hermes_admin', './assets/admin/js/app.js')
     .addEntry('hermes_front', './assets/front/js/hermes/app.js')
     .addEntry('hermes_one_page_1', './assets/front/js/hermes/app_one_page_1.js')
     .addEntry('hermes_one_page_2', './assets/front/js/hermes/app_one_page_2.js')
     .addEntry('hermes_one_page_3', './assets/front/js/hermes/app_one_page_3.js')
     .addEntry('hermes_nav_base', './assets/front/js/navbar/nav_base.js')
     .addEntry('hermes_nav_left', './assets/front/js/navbar/nav_left.js')
     .addEntry('hermes_nav_full', './assets/front/js/navbar/nav_full.js')
    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    .enableReactPreset()
    .configureBabel((babelConfig) => {
        if (Encore.isProduction()) {
            babelConfig.plugins.push(
                'transform-react-remove-prop-types'
            );
        }
    })

;
var config = Encore.getWebpackConfig();

// add an extension
// config.resolve.alias["isotope"] = 'isotope-layout';
//     config.resolve.alias: {
//         'imagesLoaded': 'imagesloaded',
//         'isotope': 'isotope-layout',
//     }

// export the final config
module.exports = config;
// module.exports = Encore.getWebpackConfig();
