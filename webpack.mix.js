const mix = require('laravel-mix');


mix.setPublicPath('public');
mix.setResourceRoot('../');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/auth.scss', 'public/css')
    .sass('resources/sass/dts.scss', 'public/css').version();

mix.scripts([
    'resources/assets/vendor/jquery/dist/jquery.min.js',
    'resources/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js',
    'resources/assets/vendor/js-cookie/js.cookie.js',
    'resources/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js',
    'resources/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js',
    'resources/assets/vendor/chart.js/dist/Chart.min.js',
    'resources/assets/vendor/chart.js/dist/Chart.extension.js',
], 'public/js/scripts2.js').version();

mix.js('resources/js/argon.js', 'public/js/argon.js').version()
    .browserSync({
        proxy: "http://task-manager.test",
        browser: "Microsoft Edge",
        open: false
    });
