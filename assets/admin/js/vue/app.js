import { createApp } from 'vue'
import HermesTemplate from './components/HermesTemplate.vue' // Import du Composant Hermestemplate
import ListSheet from './components/Sheet/ListSheet.vue'
import ListMenu from './components/Menu/ListMenu.vue'

const app = createApp(HermesTemplate)
const admin_list_sheet = createApp(ListSheet)
const admin_list_menu = createApp(ListMenu)

app.config.globalProperties.$log = console.log

app.mount('#vue_templates_hermes')
admin_list_sheet.mount('#vue_admin_list_sheet')
admin_list_menu.mount('#vue_admin_list_menu')
