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

// Inicializa role como una cadena vacía
const nuevoUsuario = ref({ name: '', email: '', password: '', role: '' })

const errores = ref({
  name: '',
  email: '',
  password: '',
  role: '',
})

const abrirModal = () => (mostrarModal.value = true)
const cerrarModal = () => {
  mostrarModal.value = false

  errores.value = {
    name: '',
    email: '',
    password: '',
    role: '',
  }

  nuevoUsuario.value = { name: '', email: '', password: '', role: '' }

}

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
    if (!validarFormilariorio()) {
      procesando.value = false
      return
    }
    await api.post('/usuarios', nuevoUsuario.value)
    toast.success('Usuario creado correctamente ')
    cerrarModal()
    nuevoUsuario.value = { name: '', email: '', password: '', role: '' }
    cargarUsuarios()
  } catch (error) {
    toast.error('Error al crear usuario ')
    console.error(error)
  } finally {
    procesando.value = false
  }
}

function validarFormilariorio() {
  let valido = true

  if (!nuevoUsuario.value.name) {
    errores.value.name = 'El nombre es obligatorio'
    valido = false
  } else {
    errores.value.name = ''
  }

  if (!nuevoUsuario.value.email) {
    errores.value.email = 'El email es obligatorio'
    valido = false
  } else if (!/\S+@\S+\.\S+/.test(nuevoUsuario.value.email)) {
    errores.value.email = 'El email no es válido'
    valido = false
  } else {
    errores.value.email = ''
  }

  if (!nuevoUsuario.value.password) {
    errores.value.password = 'La contraseña es obligatoria'
    valido = false
  } else if (nuevoUsuario.value.password.length < 6) {
    errores.value.password = 'La contraseña debe tener al menos 6 caracteres'
    valido = false
  } else {
    errores.value.password = ''
  }

  if (!nuevoUsuario.value.role) {
    errores.value.role = 'El rol es obligatorio'
    valido = false
  } else {
    errores.value.role = ''
  }

  if (!valido) toast.error('Por favor, completa todos los campos correctamente.')
  return valido
}

onMounted(cargarUsuarios)
</script>

<template>
  <AppLayout>
    <div class="overflow-x-auto min-h-[300px]">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-center">👥 Lista de Usuarios</h2>
        <button @click="abrirModal"
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

      <!-- Modal -->
      <transition name="fade">
        <div v-if="mostrarModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <div class="px-6 py-4 border-b">
              <h2 class="text-xl font-bold text-gray-800">Nuevo Usuario</h2>
            </div>

            <!-- Formulario -->
            <form @submit.prevent="añadirUsuario" class="px-6 py-4">
              <input v-model="nuevoUsuario.name" class="w-full p-2 border rounded mb-3 bg-white" placeholder="Nombre" />
              <p v-if="errores.name" class="text-red-500 text-sm mb-2">{{ errores.name }}</p>

              <input v-model="nuevoUsuario.email" autocomplete="email" class="w-full p-2 border rounded mb-3 bg-white"
                placeholder="Email" />
              <p v-if="errores.email" class="text-red-500 text-sm mb-2">{{ errores.email }}</p>

              <input v-model="nuevoUsuario.password" type="password" autocomplete="new-password"
                class="w-full p-2 border rounded mb-3 bg-white" placeholder="Contraseña" />
              <p v-if="errores.password" class="text-red-500 text-sm mb-2">{{ errores.password }}</p>

              <select v-model="nuevoUsuario.role" class="w-full p-2 border rounded mb-3 bg-white">
                <option value="">Seleccione rol</option>
                <option value="Operario">Operario</option>
                <option value="Administrador">Administrador</option>
              </select>
              <p v-if="errores.role" class="text-red-500 text-sm">{{ errores.role }}</p>

              <div class="flex justify-end gap-2 border-t pt-4">
                <button type="button" @click="cerrarModal" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <button :disabled="procesando" type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50">
                  {{ procesando ? 'Procesando...' : 'Guardar' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </transition>
    </div>
  </AppLayout>
</template>