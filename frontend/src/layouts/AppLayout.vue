<script setup>

</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#242424] text-white">
      <header class="bg-[#131212] p-4">
        <h1 class="text-4xl font-bold text-center">🚗 Mi TFG: Gestión de Vehículos</h1>
      </header>
  
      <main class="flex-grow p-6">
        <motion
          :initial="{ opacity: 0, y: 30 }"
          :enter="{ opacity: 1, y: 0 }"
          transition="{ type: 'spring', stiffness: 90 }"
        >
          <slot />
        </motion>
      </main>
  
      <footer class="bg-[#131212] text-center text-sm p-4">
        © 2025 - TFG de Grúas 🚓
      </footer>
    </div>
  </template>
  


<style scoped>
header,
footer {
    background: #2862ba;
    padding: 1rem;
}

main {
    padding: 2rem;
}
</style>
