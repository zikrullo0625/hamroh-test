import './bootstrap.js'
import '../css/app.css'
import '@fortawesome/fontawesome-free/css/all.min.css';
import { createApp } from 'vue'
import { router } from './router'
import App from './App.vue'
import api from './api.js'

const app = createApp(App)

app.config.globalProperties.api = api

app.use(router)
app.mount('#app')
