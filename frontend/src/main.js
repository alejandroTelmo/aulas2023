import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import VueAxios from 'vue-axios'
import custom_axios from './plugins/axios'
import router from './router'

Vue.config.productionTip = false

Vue.use(VueAxios, custom_axios)

new Vue({
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app')
