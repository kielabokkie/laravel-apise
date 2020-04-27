module.exports = {
  theme: {},
  variants: {
    backgroundColor: ['hover', 'dark', 'dark-hover', 'dark-group-hover'],
    borderColor: ['hover', 'dark', 'dark-focus', 'dark-focus-within'],
    textColor: ['hover', 'dark', 'dark-hover', 'dark-active'],
    opacity: ['hover', 'dark', 'dark-hover', 'dark-active']
  },
  plugins: [
    require('tailwindcss-dark-mode')()
  ]
}
