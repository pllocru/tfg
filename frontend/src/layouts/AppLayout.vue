<template>
  <div class="flex min-h-screen bg-white text-blue-900 font-sans">
    <!-- Sidebar con estado persistente -->
    <aside :style="{ width: sidebarCollapsed ? '4rem' : '16rem' }"
      class="bg-blue-700 text-white p-4 transition-all duration-300 ease-in-out overflow-hidden">
      <div class="flex items-center mb-6 gap-3">
        <h2 v-if="!sidebarCollapsed"
          class="text-xl font-bold text-white opacity-90 border-b pb-2 border-blue-500 flex-1">
          Panel de control
        </h2>
        <button @click="toggleSidebar" class="p-2 hover:bg-blue-700/30 transition-colors bg-transparent"
          title="Expandir/Colapsar menú">
          <Menu class="w-5 h-5 text-white" />
        </button>
      </div>

      <nav class="space-y-3 mt-2">
        <router-link to="/" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/20 hover:scale-[1.05] transition"
          :class="{ 'bg-white/20': $route.path === '/' }">
          <Home class="w-5 h-5 text-white" />
          <span v-if="!sidebarCollapsed">Inicio</span>
        </router-link>

        <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/20 hover:scale-[1.05] transition">
          <Car class="w-5 h-5 text-white" />
          <span v-if="!sidebarCollapsed">Vehículos</span>
        </a>

        <router-link to="/usuarios"
          class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/20 hover:scale-[1.05] transition"
          :class="{ 'bg-white/20': $route.path === '/usuarios' }">
          <Users class="w-5 h-5 text-white" />
          <span v-if="!sidebarCollapsed">Usuarios</span>
        </router-link>

        <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/20 hover:scale-[1.05] transition">
          <ClipboardList class="w-5 h-5 text-white" />
          <span v-if="!sidebarCollapsed">Historial</span>
        </a>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
          <h1 class="text-2xl font-bold flex items-center gap-3">
            <span class="text-3xl"></span> Gestión de Vehículos
          </h1>

          <div class="relative" @click="toggleProfileMenu">
            <div class="flex items-center gap-2 cursor-pointer hover:bg-blue-700/30 px-3 py-2 rounded transition">
              <User class="w-6 h-6 text-white" />
              <span class="font-medium text-white/90 hidden md:inline">Admin</span>
              <ChevronDown v-if="!profileMenuOpen" class="w-5 h-5 text-white" />
              <ChevronUp v-else class="w-5 h-5 text-white" />
            </div>

            <div v-if="profileMenuOpen"
              class="absolute right-0 mt-2 w-40 bg-white text-blue-900 rounded shadow-lg z-10 overflow-hidden">
              <a href="#" class="block px-4 py-2 hover:bg-blue-100 transition">Perfil</a>
              <a href="#" class="block px-4 py-2 hover:bg-blue-100 transition">Cerrar sesión</a>
            </div>
          </div>
        </div>
      </header>

      <main class="flex-1 bg-gradient-to-br from-blue-50 to-white p-8 overflow-y-auto">
        <slot />
      </main>

      <footer class="bg-gradient-to-r from-blue-600 to-blue-800 text-white text-center p-4 ">
        <p>© 2025 Sistema de Gestión de Vehículos </p>
      </footer>
    </div>
  </div>
</template>

<script>

import { Home, Car, Users, ClipboardList, Menu, User, ChevronDown, ChevronUp } from 'lucide-vue-next'

export default {
  components: { Home, Car, Users, ClipboardList, Menu, User, ChevronDown, ChevronUp },
  data() {
    return {
      sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
      profileMenuOpen: false
    }
  },
  mounted() {
    window.addEventListener('resize', this.checkScreen)
    document.addEventListener('click', this.closeProfileMenu)
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.checkScreen)
    document.removeEventListener('click', this.closeProfileMenu)
  },
  methods: {
    toggleSidebar() {
      this.sidebarCollapsed = !this.sidebarCollapsed
      localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed)
    },
    checkScreen() {
      this.sidebarCollapsed = window.innerWidth < 768
      localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed)
    },
    toggleProfileMenu(e) {
      e.stopPropagation()
      this.profileMenuOpen = !this.profileMenuOpen
    },
    closeProfileMenu(e) {
      if (!e.target.closest('.relative')) this.profileMenuOpen = false
    }
  }
}
</script>
