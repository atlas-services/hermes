import { createApp } from 'vue'
import HermesTemplate from './components/HermesTemplate.vue' // Import du Composant Hermestemplate

// const app = createApp(CountButton).mount('#app')
const app = createApp(HermesTemplate)
app.config.globalProperties.$log = console.log
instanceComposant = app.mount('#templates_hermes')
