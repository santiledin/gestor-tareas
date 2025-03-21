import Vue from 'vue'
import { PiniaVuePlugin } from 'pinia'
import App from './App.vue'
import router from './router'
import pinia from './pinia'

Vue.use(PiniaVuePlugin)

new Vue({
  router,
  pinia,
  render: (h) => h(App)
}).$mount('#app')
