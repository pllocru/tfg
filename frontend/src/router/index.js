import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Home from '@/views/Home.vue'
import Usuarios from '@/views/Usuarios.vue'
import Profile from '@/views/Profile.vue';  // Importar el componente de perfil


const routes = [
  { path: '/usuarios', name: 'Usuarios', component: Usuarios, meta: { requiresAuth: true, requiresRole: 'Administrador' } },
  { path: '/login', name: 'Login', component: Login },
  { path: '/', name: 'Home', component: Home, meta: { requiresAuth: true, requiresRole: 'Administrador' } }, // Solo administradores
  {
    path: '/perfil',
    name: 'Profile',
    component: Profile,  // Ruta para el perfil
    meta: {
      requiresAuth: true,  // Solo accesible si el usuario está autenticado
    },
  },
  
]

const router = createRouter({
  history: createWebHistory(),
  routes
})
 
// Verifica si el usuario está autenticado y tiene el rol adecuado
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const role = localStorage.getItem('role');
  
  if (to.meta.requiresAuth && !token) {
      next({ name: 'Login' });
  } else if (to.meta.requiresRole && to.meta.requiresRole !== role) {
      next('/'); // Redirige al inicio si el usuario no tiene el rol adecuado
  } else {
      next();
  }
});

export default router;
