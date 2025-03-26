<template>
    <div>
      <header>
        <h1>🚗 Mi TFG: Gestión de Vehículos</h1>
        <nav>
          <!-- podrías poner router-links aquí -->
        </nav>
      </header>
  
      <main>
        <slot /> <!-- Aquí se cargan las vistas como en @yield -->
      </main>
  
      <footer>
        <p>&copy; 2025 - TFG de Grúas 🚓</p>
      </footer>
    </div>
  </template>
  
  <style scoped>
  header, footer {
    background: #131212;
    padding: 1rem;
  }
  main {
    padding: 2rem;
  }
  </style>
  