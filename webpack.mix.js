const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/custom.js", "public/js/web")
    .js("resources/js/drag_drop.js", "public/js/web")
    .postCss("resources/css/about.css", "public/css/web")
    .postCss("resources/css/card.css", "public/css/web")
    .postCss("resources/css/contact.css", "public/css/web")
    .postCss("resources/css/edit-profile.css", "public/css/web")
    .postCss("resources/css/employees.css", "public/css/web")
    .postCss("resources/css/home.css", "public/css/web")
    .postCss("resources/css/login.css", "public/css/web")
    .postCss("resources/css/profile.css", "public/css/web")
    .postCss("resources/css/profileCard.css", "public/css/web")
    .postCss("resources/css/profiles.css", "public/css/web")
    .postCss("resources/css/modal.css", "public/css/web")
    .postCss("resources/css/likely-custom.css", "public/css/web")
    .postCss("resources/css/side-nav.css", "public/css/web")
    .postCss("resources/css/style.css", "public/css/web")
    .postCss("resources/css/ltr/style.css", "public/css/web/ltr")
    .postCss("resources/css/rtl/style.css", "public/css/web/rtl");
