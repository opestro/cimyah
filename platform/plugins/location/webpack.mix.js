const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .js(`${source}/resources/js/location.js`, `${dist}/js`)
    .js(`${source}/resources/js/bulk-import.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/js/location.js`, `${source}/public/js`)
        .copy(`${dist}/js/bulk-import.js`, `${source}/public/js`)
}
