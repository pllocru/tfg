import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import PrimeVue from 'primevue/config'
import Toast from 'vue-toastification'
import { MotionPlugin } from '@vueuse/motion'

import 'primevue/resources/themes/saga-blue/theme.css'; // Usar este tema

import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'

import 'vue-toastification/dist/index.css'
import './style.css'

// ✅ Componentes de PrimeVue
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import MultiSelect from 'primevue/multiselect'
import Dropdown from 'primevue/dropdown'
import Dialog from 'primevue/dialog'
import Checkbox from 'primevue/checkbox'
import Toolbar from 'primevue/toolbar'
import ConfirmDialog from 'primevue/confirmdialog'
import ConfirmationService from 'primevue/confirmationservice'



// ✅ Inicializa app
const app = createApp(App)

app.use(router)
app.use(MotionPlugin)
app.use(PrimeVue)
app.use(Toast, {
  position: 'top-right',
  timeout: 3000,
})
app.use(ConfirmationService)

// ✅ Registra componentes globales
app.component('DataTable', DataTable)
app.component('Column', Column)
app.component('InputText', InputText)
app.component('Button', Button)
app.component('MultiSelect', MultiSelect)
app.component('Dropdown', Dropdown)
app.component('Dialog', Dialog)
app.component('Checkbox', Checkbox)
app.component('Toolbar', Toolbar)
app.component('ConfirmDialog', ConfirmDialog)

app.mount('#app')
