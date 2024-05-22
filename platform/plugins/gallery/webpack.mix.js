const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .sass(`${source}/resources/sass/gallery.scss`, dist + '/css')
    .sass(`${source}/resources/sass/object-gallery.scss`, dist + '/css')
    .sass(`${source}/resources/sass/admin-gallery.scss`, dist + '/css')
    .js(`${source}/resources/js/gallery.js`, dist + '/js/gallery.js')
    .js(`${source}/resources/js/gallery-admin.js`, `${dist}/js/gallery-admin.js`)
    .js(`${source}/resources/js/object-gallery.js`, `${dist}/js/object-gallery.js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/js/gallery.js`, `${source}/public/js`)
        .copy(`${dist}/js/gallery-admin.js`, `${source}/public/js`)
        .copy(`${dist}/js/object-gallery.js`, `${source}/public/js`)
        .copy(`${dist}/css/gallery.css`, `${source}/public/css`)
        .copy(`${dist}/css/admin-gallery.css`, `${source}/public/css`)
        .copy(`${dist}/css/object-gallery.css`, `${source}/public/css`)
}
