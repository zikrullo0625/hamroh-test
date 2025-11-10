import './bootstrap.js'
import '../css/app.css'
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'leaflet/dist/leaflet.css';
import { createApp } from 'vue'
import { router } from './router'
import App from './App.vue'
import api from './plugins/api.js'
import geoPlugin from './plugins/geolocation'
import eventBus from './plugins/eventBus'
import './echo.js';

const app = createApp(App)

app.config.globalProperties.api = api


app.use(geoPlugin)
app.use(eventBus)
app.use(router)
app.mount('#app')
