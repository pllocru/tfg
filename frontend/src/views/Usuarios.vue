<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import api from '@/services/api'
import AppLayout from '@/layouts/AppLayout.vue'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'

const toast = useToast()

const usuarios = ref([])
const loading = ref(true)
const mostrarModal = ref(false)
const procesando = ref(false)

const seleccionados = ref([])


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
  errores.value = { name: '', email: '', password: '', role: '' }
  nuevoUsuario.value = { name: '', email: '', password: '', role: '' }
}

const cargarUsuarios = async () => {
  loading.value = true
  try {
    const res = await api.get('/usuarios')
    usuarios.value = res.data
  } catch (error) {
    toast.error('Error al cargar usuarios')
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
    toast.success('Usuario creado correctamente')
    cerrarModal()
    nuevoUsuario.value = { name: '', email: '', password: '', role: '' }
    cargarUsuarios()
  } catch (error) {
    toast.error('Error al crear usuario')
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

// Se añade una columna para acciones
const columns = [
  {
    label: 'Nombre',
    field: 'name',
    sortable: true,
    filterOptions: {
      enabled: true,
      placeholder: 'Filtrar por nombre...',
      styleClass: 'text-sm',
    },
  },
  {
    label: 'Email',
    field: 'email',
    sortable: true,
    filterOptions: {
      enabled: true,
      placeholder: 'Filtrar por email...',
    },
  },
  {
    label: 'Rol',
    field: 'role',
    sortable: true,
    filterOptions: {
      enabled: true,
      filterDropdownItems: ['Operario', 'Administrador'], // dropdown en lugar de input
      placeholder: 'Seleccionar rol',
    },
  },
  {
    label: 'Acciones',
    field: 'acciones',
  },
]


const paginationOptions = {
  enabled: true,
  perPage: 10,
  mode: 'pages', // Muestra números de página
  nextLabel: 'Siguiente',
  prevLabel: 'Anterior',
  rowsPerPageLabel: 'Filas por página',
  ofLabel: 'de',
}
const searchOptions = {
  enabled: true,
  placeholder: 'Buscar usuario...',
}

// Función para editar un usuario
const editarUsuario = (usuario) => {
  console.log('Editar usuario:', usuario)
  toast.info(`Editar usuario: ${usuario.name}`)
  // Implementa aquí la lógica de edición, por ejemplo, abriendo otro modal con el formulario de edición.
}

// Función para borrar un usuario
const borrarUsuario = (usuario) => {
  if (confirm(`¿Está seguro de eliminar a ${usuario.name}?`)) {
    api.delete(`/usuarios/${usuario.id}`)
      .then(() => {
        toast.success('Usuario eliminado correctamente')
        cargarUsuarios()
      })
      .catch(error => {
        toast.error('Error al eliminar usuario')
        console.error(error)
      })
  }
}

const mostrarDetalles = (usuario) => {
  console.log('👀 Usuario:', usuario)
  toast.info(`${usuario.name} (${usuario.email})`)
}

</script>

<template>
  <AppLayout>
    <div class="overflow-x-auto min-h-[300px]">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-center">Lista de Usuarios</h2>
        <button @click="abrirModal"
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center float-left">
          Añadir
        </button>
        <button @click="eliminarSeleccionados" :disabled="seleccionados.length === 0"
          class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded shadow text-sm">
          🗑️ Eliminar seleccionados
        </button>

      </div>

      <div v-if="loading" class="text-center text-gray-500 py-10 animate-pulse">
        Cargando usuarios...
      </div>

      <div v-else>
        <vue-good-table :columns="columns" :rows="usuarios" :search-options="searchOptions"
          :pagination-options="paginationOptions" styleClass="vgt-table condensed">
          <!-- Slot para personalizar cada fila -->
          <template #table-row="props">
            <td v-if="props.column.field === 'acciones'" class="px-4 py-2">
              <div class="flex gap-2">
                <button @click.stop="mostrarDetalles(props.row)"
                  class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded">
                  Ver
                </button>
                <button @click.stop="editarUsuario(props.row)"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                  Editar
                </button>
                <button @click.stop="borrarUsuario(props.row)"
                  class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                  Borrar
                </button>
              </div>
            </td>

          </template>

        </vue-good-table>
      </div>

      <!-- Modal de creación de usuario -->
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

<style scoped>
:deep(table.vgt-table td) {
  border-bottom: none !important;
}

:deep(.vgt-input) {
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
  padding: 0.25rem 0.5rem;
}

:deep(.vgt-table tbody tr:hover) {
  background-color: #e0f2fe;
  /* azul clarito */
  cursor: pointer;
}
</style>
