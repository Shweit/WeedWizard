const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('leafletConfig', './assets/script/leafletConfig.js')
    .addEntry('login', './assets/script/togglePasswordVisibility.js')
    .addEntry('register', './assets/script/togglePasswordVisibility.js')
    .addEntry('cannabis_club', './assets/script/cannabisVerein.js')
    .addEntry('cannadose_calculator', './assets/script/CannaDoseCalculator.js')
    .addEntry('bud_bash_locator', './assets/script/BudBashLocator.js')
    .addEntry('cannaConsultant', './assets/script/cannaConsultant.js')
    .addEntry('qrScanner', './assets/script/qrCodeScanner.js')
    .addEntry('chartJsConfig', './assets/script/chartJsConfig.js')
    .addEntry('blog', './assets/script/blog.js')
    .addEntry('blogEntry', './assets/script/blogEntry.js')
    .addEntry('editUserProfile', './assets/script/editUserProfile.js')
    .addEntry('cannastrain_library', './assets/script/cannastrainLibrary.js')
    .addEntry('growMate', './assets/script/growMate.js')
    .addEntry('knowledgebase_entry', './assets/script/knowledgebase_entry.js')

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

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()
    .enablePostCssLoader()
    .enableStimulusBridge('./assets/controllers.json')

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]',
    })
;

module.exports = Encore.getWebpackConfig();