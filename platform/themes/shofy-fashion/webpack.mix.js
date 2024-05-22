const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/themes/${directory}`
const dist = `public/themes/${directory}`

mix
    .sass(`${source}/assets/sass/theme.scss`, `${dist}/css`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/theme.css`, `${source}/public/css`)
}
