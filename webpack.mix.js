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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/assets/css')
    .sass('resources/sass/main.scss', 'public/assets/css').version();

mix.js('resources/js/laravel-echo.js', 'public/assets/js/');
// mix.scripts('resources/js/page/app/*.js', 'public/test')

let fs = require('fs');

let getFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

getFiles('./resources/js/page/app/').forEach(function (filepath) {
    mix.js('resources/js/page/app/' + filepath, 'public/assets/js/page/app/' + filepath);
});