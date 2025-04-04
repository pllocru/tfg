<template>
    <div>
      <h1>Bienvenido a la página de Administrador</h1>
      <p>Esta es una página exclusiva para administradores.</p>
  
      <!-- Botón de logout -->
      <button @click="logout" class="bg-red-600 text-white p-2 rounded">Cerrar sesión</button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AdminPage',
    methods: {
      logout() {
        // Elimina el token y el rol del localStorage
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        
        // Redirige al login
        this.$router.push({ name: 'Login' });
      }
    }
  }
  </script>
  
  <style scoped>
  /* Aquí puedes agregar tus estilos */
  h1 {
    color: blue;
  }
  </style>
  