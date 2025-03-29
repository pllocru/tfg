import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Home from '@/views/Home.vue'
import Usuarios from '@/views/Usuarios.vue'

const routes = [
  { path: '/usuarios', name: 'Usuarios', component: Usuarios },
  { path: '/login', name: 'Login', component: Login },
  { path: '/', name: 'Home', component: Home }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
