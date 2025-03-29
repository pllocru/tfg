import { createApp } from 'vue'
import App from './App.vue'
import { MotionPlugin } from '@vueuse/motion'
import router from './router'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import './style.css'

const app = createApp(App)

app.use(router)
app.use(MotionPlugin)
app.use(Toast, {
  position: 'top-right',
  timeout: 3000,
})

app.mount('#app')
