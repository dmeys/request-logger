const mix = require('laravel-mix');
const path = require('path');

mix
    .setPublicPath('public')
    .js('resources/js/jsonTree.js', 'public/jsonTree.js')
    .js('resources/js/app.js', 'public/app.js')
    .css('resources/css/app.css', 'public/app.css')
    .css('resources/css/jsonTree.css', 'public/jsonTree.css')
    .css('node_modules/bootstrap/dist/css/bootstrap.css', 'public/bootstrap.css')
    .copy('resources/css/icons.svg', 'public/icons.svg')
    .version()
    .webpackConfig({
        resolve: {
            symlinks: false,
            alias: {
                '@': path.resolve(__dirname, 'resources/js/'),
            },
        }
    });
