<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import api from '@/services/api'
import AppLayout from '@/layouts/AppLayout.vue'

const toast = useToast()

const usuarios = ref([])
const loading = ref(true)
const mostrarModal = ref(false)
const procesando = ref(false)
const nuevoUsuario = ref({ name: '', email: '', password: '', role: 'Operario' })

const abrirModal = () => (mostrarModal.value = true)
const cerrarModal = () => (mostrarModal.value = false)

const cargarUsuarios = async () => {
  loading.value = true
  try {
    const res = await api.get('/usuarios')
    usuarios.value = res.data
  } catch (error) {
    toast.error('Error al cargar usuarios ')
    console.error('Error al cargar usuarios:', error)
  } finally {
    loading.value = false
  }
}

const añadirUsuario = async () => {
  procesando.value = true
  try {
    await api.post('/usuarios', nuevoUsuario.value)
    toast.success('Usuario creado correctamente ')
    cerrarModal()
    nuevoUsuario.value = { name: '', email: '', password: '', role: 'Operario' }
    cargarUsuarios()
  } catch (error) {
    toast.error('Error al crear usuario 😞')
    console.error(error)
  } finally {
    procesando.value = false
  }
}

onMounted(cargarUsuarios)
</script>

<template>
  <AppLayout>
    <div class="overflow-x-auto min-h-[300px]">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-center">👥 Lista de Usuarios</h2>
        <button
          @click="abrirModal"
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
          ➕ Añadir
        </button>
      </div>

      <div v-if="loading" class="text-center text-gray-500 py-10 animate-pulse">
        Cargando usuarios...
      </div>

      <table v-else class="min-w-full bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="text-left px-6 py-3">Nombre</th>
            <th class="text-left px-6 py-3">Email</th>
            <th class="text-left px-6 py-3">Rol</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-blue-100 text-blue-900">
          <tr v-for="usuario in usuarios" :key="usuario.id" class="hover:bg-blue-50 transition">
            <td class="px-6 py-4">{{ usuario.name }}</td>
            <td class="px-6 py-4">{{ usuario.email }}</td>
            <td class="px-6 py-4">{{ usuario.role }}</td>
          </tr>
        </tbody>
      </table>

      

      <!-- Modal con ajustes visuales -->
      <transition name="fade">
        <div v-if="mostrarModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <div class="px-6 py-4 border-b">
              <h2 class="text-xl font-bold text-gray-800">Nuevo Usuario </h2>
            </div>

            <div class="px-6 py-4">
              <input v-model="nuevoUsuario.name" class="w-full p-2 border rounded mb-3 bg-white" placeholder="Nombre" />
              <input v-model="nuevoUsuario.email" class="w-full p-2 border rounded mb-3 bg-white" placeholder="Email" />
              <input
                v-model="nuevoUsuario.password"
                type="password"
                class="w-full p-2 border rounded mb-3 bg-white"
                placeholder="Contraseña" />
              <select v-model="nuevoUsuario.role" class="w-full p-2 border rounded mb-3 bg-white">
                <option>Operario</option>
                <option>Administrador</option>
              </select>
            </div>

            <div class="px-6 py-4 flex justify-end gap-2 border-t">
              <button @click="cerrarModal" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
              <button
                :disabled="procesando"
                @click="añadirUsuario"
                class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50">
                {{ procesando ? 'Procesando...' : 'Guardar' }}
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
