const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
var node_path = 'node_modules/';

mix.setPublicPath('public/');
mix.setResourceRoot('../');

mix.js('resources/js/app.js','public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({ processCssUrls: false })
    .scripts([
        node_path+'jquery/dist/jquery.min.js',
        node_path+'jquery-ui/ui/widgets/datepicker.js',
        node_path+'bootstrap/dist/js/bootstrap.min.js',
    ], 'public/js/vendor.js')
    .styles([
    ], 'public/css/vendor.css');
