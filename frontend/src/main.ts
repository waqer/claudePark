import { createApp } from 'vue'
import router from './router'
import App from './App.vue'
import './styles/theme.css'   // ← global, never scoped

createApp(App).use(router).mount('#app')
