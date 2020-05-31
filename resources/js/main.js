import Vue from 'vue'

Vue.config.productionTip = false

Vue.component('logs', require('./components/Logs.vue').default)

new Vue({
  el: '#app'
})
