<!-- Home.vue -->
<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import AppLayout from '@/layouts/AppLayout.vue'

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
    <h2 class="text-2xl font-semibold text-center mb-6">Animado 🎉</h2>
    <ul class="text-center space-y-2">
      <li v-for="v in vehiculos" :key="v.id">
        {{ v.matricula }} - {{ v.marca }} {{ v.modelo }}
      </li>
    </ul>
  </AppLayout>
</template>
