const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/themes/${directory}`
const dist = `public/themes/${directory}`

mix.sass(`${source}/assets/sass/theme.scss`, `${dist}/css`)
    .sass(`${source}/assets/sass/theme-rtl.scss`, `${dist}/css`)
    .js(`${source}/assets/js/theme.js`, `${dist}/js`)
    .js(`${source}/assets/js/ecommerce.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix.copy(`${dist}/css/theme.css`, `${source}/public/css`)
        .copy(`${dist}/css/theme-rtl.css`, `${source}/public/css`)
        .copy(`${dist}/js/theme.js`, `${source}/public/js`)
        .copy(`${dist}/js/ecommerce.js`, `${source}/public/js`)
        .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', `${source}/public/plugins/bootstrap`)
        .copy('node_modules/bootstrap/dist/css/bootstrap.rtl.min.css', `${source}/public/plugins/bootstrap`)
        .copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', `${source}/public/plugins/bootstrap`)
}
