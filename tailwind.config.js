module.exports = {
  theme: {},
  variants: {
    backgroundColor: ['dark', 'dark-hover', 'dark-group-hover'],
    borderColor: ['dark', 'dark-focus', 'dark-focus-within'],
    textColor: ['dark', 'dark-hover', 'dark-active'],
    opacity: ['dark', 'dark-hover', 'dark-active']
  },
  plugins: [
    require('tailwindcss-dark-mode')()
  ]
}
