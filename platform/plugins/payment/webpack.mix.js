const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = 'platform/plugins/' + directory
const dist = 'public/vendor/core/plugins/' + directory

mix
    .js(`${source}/resources/js/payment.js`, `${dist}/js/payment.js`)
    .js(`${source}/resources/js/payment-methods.js`, `${dist}/js/payment-methods.js`)
    .js(`${source}/resources/js/payment-detail.js`, `${dist}/js/payment-detail.js`)
    .sass(`${source}/resources/sass/payment.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/payment-setting.scss`, `${dist}/css`)

if (mix.inProduction()) {
    mix
        .copyDirectory(`${dist}/js`, `${source}/public/js`)
        .copyDirectory(`${dist}/css`, `${source}/public/css`)
}
