<script setup>
import AppLayout from './layouts/AppLayout.vue'
import { ref, onMounted } from 'vue'
import api from './services/api'


const vehiculos = ref([])

onMounted(async () => {
  try {
    const res = await api.get('/vehiculos')
    vehiculos.value = res.data
  } catch (error) {
    console.error('Error al conectar con la API:', error)
  }
})
</script>

<template>
  <AppLayout>
    <h2 class="text-2xl font-semibold text-center my-4">Animado 🎉</h2>
    <ul class="text-center space-y-2">
      <motion v-for="v in vehiculos" :key="v.id" :initial="{ opacity: 0, x: -20 }" :enter="{ opacity: 1, x: 0 }">
        <li>{{ v.matricula }} - {{ v.marca }} {{ v.modelo }}</li>
      </motion>
    </ul>
  </AppLayout>
</template>
