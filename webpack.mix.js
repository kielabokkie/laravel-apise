let mix = require('laravel-mix')
require('laravel-mix-postcss-config')

mix.setPublicPath('public')
    .js('resources/js/main.js', 'public')
    .postCss('resources/css/tailwind.css', 'public', [
      require('tailwindcss'),
    ])
    .postCssConfig()

if (mix.inProduction() === false) {
  mix.sourceMaps()
}

if (mix.inProduction() === true) {
  mix.version()
}
