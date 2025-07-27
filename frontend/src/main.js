import { createApp } from 'vue'
import App from './App.vue'

import '@mdi/font/css/materialdesignicons.css' // иконки, если нужны
import 'vuetify/styles' // стили Vuetify

import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
})

const app = createApp(App)
app.use(vuetify)
app.mount('#app')
