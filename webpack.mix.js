const mix = require('laravel-mix');

mix
    .setPublicPath('dist')
    .js('resources/js/filter.js', 'js')
    .sass('resources/sass/filter.scss', 'css')
    .version();

if (mix.inProduction()) {
    mix.sourceMaps();
}
