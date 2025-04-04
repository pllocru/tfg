<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import api from '@/services/api'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import MultiSelect from 'primevue/multiselect'
import Swal from 'sweetalert2'

const toast = useToast()
const usuarios = ref([])
const selectedUsuarios = ref([])
const globalFilter = ref('')
const loading = ref(true)
const mostrarModal = ref(false)
const mostrarModalEditar = ref(false)
const procesando = ref(false)
const usuarioLogeadoNombre = localStorage.getItem('userName');

const nuevoUsuario = ref({ name: '', email: '', password: '', role: '' })
const usuarioEditar = ref({ id: '', name: '', email: '', role: '' })
const errores = ref({ name: '', email: '', password: '', role: '' })


const abrirModal = () => (mostrarModal.value = true)
const cerrarModal = () => {
  mostrarModal.value = false
  errores.value = { name: '', email: '', password: '', role: '' }
  nuevoUsuario.value = { name: '', email: '', password: '', role: '' }
}

const abrirModalEditar = (usuario) => {
  usuarioEditar.value = { ...usuario }
  mostrarModalEditar.value = true
}

const cerrarModalEditar = () => {
  mostrarModalEditar.value = false
  errores.value = { name: '', email: '', password: '', role: '' }
  usuarioEditar.value = { id: '', name: '', email: '', role: '' }
}


const cargarUsuarios = async () => {
  loading.value = true
  try {
    const res = await api.get('/usuarios')
    usuarios.value = res.data.filter(usuario => usuario.name !== usuarioLogeadoNombre);
  } catch (error) {
    toast.error('Error al cargar usuarios')
    console.error(error)
  } finally {
    loading.value = false
  }
}


const añadirUsuario = async () => {
  procesando.value = true
  try {
    if (!validarFormulario()) {
      procesando.value = false
      return
    }
    await api.post('/usuarios', nuevoUsuario.value)
    toast.success('Usuario creado correctamente')
    cerrarModal()
    cargarUsuarios()
  } catch (error) {
    toast.error('Error al crear usuario')
    console.error(error)
  } finally {
    procesando.value = false
  }
}

const editarUsuario = async () => {
  procesando.value = true
  try {
    if (!validarFormularioEditar()) {
      procesando.value = false
      return
    }
    await api.put(`/usuarios/${usuarioEditar.value.id}`, usuarioEditar.value)
    toast.success('Usuario editado correctamente')
    cerrarModalEditar()
    cargarUsuarios()
  } catch (error) {
    toast.error('Error al editar usuario')
    console.error(error)
  } finally {
    procesando.value = false
  }
}

function validarFormulario() {
  let valido = true

  if (!nuevoUsuario.value.name) {
    errores.value.name = 'El nombre es obligatorio'
    valido = false
  } else errores.value.name = ''

  if (!nuevoUsuario.value.email) {
    errores.value.email = 'El email es obligatorio'
    valido = false
  } else if (!/\S+@\S+\.\S+/.test(nuevoUsuario.value.email)) {
    errores.value.email = 'El email no es válido'
    valido = false
  } else errores.value.email = ''

  if (!nuevoUsuario.value.password) {
    errores.value.password = 'La contraseña es obligatoria'
    valido = false
  } else if (nuevoUsuario.value.password.length < 6) {
    errores.value.password = 'La contraseña debe tener al menos 6 caracteres'
    valido = false
  } else errores.value.password = ''

  if (!nuevoUsuario.value.role) {
    errores.value.role = 'El rol es obligatorio'
    valido = false
  } else errores.value.role = ''

  if (!valido) toast.error('Completa todos los campos correctamente.')
  return valido
}

function validarFormularioEditar() {
  let valido = true;

  // Validación del nombre
  if (!usuarioEditar.value.name) {
    errores.value.name = 'El nombre es obligatorio';
    valido = false;
  } else {
    errores.value.name = '';
  }

  // Validación del email
  if (!usuarioEditar.value.email) {
    errores.value.email = 'El email es obligatorio';
    valido = false;
  } else if (!/\S+@\S+\.\S+/.test(usuarioEditar.value.email)) {
    errores.value.email = 'El email no es válido';
    valido = false;
  } else {
    errores.value.email = '';
  }

  // Validación de la contraseña solo si se proporciona
  if (usuarioEditar.value.password && usuarioEditar.value.password.length < 6) {
    errores.value.password = 'La contraseña debe tener al menos 6 caracteres';
    valido = false;
  } else {
    errores.value.password = ''; // No mostrar el error si el campo está vacío o válido
  }

  // Validación del rol
  if (!usuarioEditar.value.role) {
    errores.value.role = 'El rol es obligatorio';
    valido = false;
  } else {
    errores.value.role = '';
  }

  if (!valido) toast.error('Completa todos los campos correctamente.');
  return valido;
}


const mostrarDetalles = (usuario) => {
  toast.info(`${usuario.name} (${usuario.email})`)
}

const borrarUsuario = (usuario) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: `¿Eliminar a ${usuario.name}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    didOpen: () => {
      // Eliminar padding-right y overflow cuando el modal se abre
      document.body.style.overflow = 'hidden';
      document.body.style.paddingRight = '0px';
    },
    willClose: () => {
      // Restaurar el overflow y padding-right al cerrar el modal
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
    },
    customClass: {
      confirmButton: 'bg-red-500 text-white hover:bg-red-600 border border-red-600', // Botón Confirmar (eliminar)
      cancelButton: 'bg-gray-500 text-white hover:bg-gray-600 border border-gray-600' // Botón Cancelar
    },
    buttonsStyling: false  // Desactivar los estilos por defecto de SweetAlert2
  }).then((result) => {
    if (result.isConfirmed) {
      api.delete(`/usuarios/${usuario.id}`)
        .then(() => {
          toast.success('Usuario eliminado')
          cargarUsuarios()
        })
        .catch(error => {
          toast.error('Error al eliminar')
          console.error(error)
        })
    }
  })
}


const eliminarSeleccionados = async () => {
  // Usamos SweetAlert2 para confirmar la eliminación de usuarios seleccionados
  Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Eliminar usuarios seleccionados?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    didOpen: () => {
      // Eliminar padding-right y overflow cuando el modal se abre
      document.body.style.overflow = 'hidden';
      document.body.style.paddingRight = '0px';
    },
    willClose: () => {
      // Restaurar el overflow y padding-right al cerrar el modal
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
    },
    customClass: {
      confirmButton: 'bg-red-500 text-white hover:bg-red-600 border border-red-600', // Botón Confirmar (eliminar)
      cancelButton: 'bg-gray-500 text-white hover:bg-gray-600 border border-gray-600' // Botón Cancelar
    },
    buttonsStyling: false  // Desactivar los estilos por defecto de SweetAlert2
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        // Eliminamos los usuarios seleccionados
        for (const usuario of selectedUsuarios.value) {
          await api.delete(`/usuarios/${usuario.id}`)
        }
        toast.success('Usuarios eliminados')
        selectedUsuarios.value = [] // Limpiar la selección
        cargarUsuarios() // Recargar la lista de usuarios
      } catch (error) {
        toast.error('Error al eliminar')
        console.error(error)
      }
    }
  })
}


onMounted(cargarUsuarios)
</script>


<template>
  <AppLayout>
    <div class="h-full flex flex-col">
      <Toolbar class="mb-4">
        <template #start>
          <div class="flex gap-3 flex-wrap">
            <Button label="Añadir" icon="pi pi-plus"
              class="p-button-sm bg-green-600 text-white hover:bg-green-400 gap-2" @click="abrirModal" />
            <Button label="Eliminar seleccionados" icon="pi pi-trash"
              class="p-button-sm bg-red-500 text-white hover:bg-red-600" :disabled="!selectedUsuarios.length"
              @click="eliminarSeleccionados" />
          </div>
        </template>

        <template #end>
          <div class="w-full sm:w-[400px] mt-2 sm:mt-0">
            <div class="p-float-label">
              <div class="p-inputgroup">
                <!-- Ícono de la lupa a la izquierda -->
                <span class="p-inputgroup-addon">
                  <i class="pi pi-search"></i>
                </span>
                <InputText id="globalFilter" v-model="globalFilter" class="w-full p-inputtext-lg"
                  placeholder=" search ..." />
              </div>
            </div>
          </div>
        </template>
      </Toolbar>

      <div class="h-full overflow-auto">
        <DataTable :value="usuarios" v-model:selection="selectedUsuarios" dataKey="id" :paginator="true" :rows="10"
          :globalFilterFields="['name', 'email', 'role']" :loading="loading"
          :filters="{ global: { value: globalFilter } }" selectionMode="multiple" responsiveLayout="scroll"
          class="w-full h-full" :tableStyle="{ width: '100%', height: '100%' }">

          <Column selectionMode="multiple" headerStyle="width: 2rem" />
          <Column field="name" header="Nombre" sortable filter filterPlaceholder="Filtrar por nombre" />
          <Column field="email" header="Email" sortable filter filterPlaceholder="Filtrar por email" />
          <Column field="role" header="Rol" sortable filter filterPlaceholder="Filtrar por rol" />
          <Column header="Acciones">
            <template #body="{ data }">
              <div class="flex gap-2 flex-wrap">
                <!-- Botones más pequeños pero no demasiado -->
                <Button label="Ver" icon="pi pi-eye" @click="mostrarDetalles(data)"
                  class="p-button-sm bg-gray-200 text-gray-800 hover:bg-gray-300 border border-gray-300 text-sm" />

                <Button label="Editar" icon="pi pi-pencil" @click="abrirModalEditar(data)"
                  class="p-button-sm bg-blue-500 text-white hover:bg-blue-600 text-sm" />

                <Button label="Borrar" icon="pi pi-trash" @click="borrarUsuario(data)"
                  class="p-button-sm bg-red-500 text-white hover:bg-red-600 text-sm" />
              </div>
            </template>
          </Column>
        </DataTable>


      </div>
    </div>

    <!-- Modal para añadir usuario -->
    <transition name="fade">
      <div v-if="mostrarModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
          <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-gray-800">Nuevo Usuario</h2>
          </div>

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

    <!-- Modal para editar usuario -->
    <transition name="fade">
      <div v-if="mostrarModalEditar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
          <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-gray-800">Editar Usuario</h2>
          </div>

          <form @submit.prevent="editarUsuario" class="px-6 py-4">
            <input v-model="usuarioEditar.name" class="w-full p-2 border rounded mb-3 bg-white" placeholder="Nombre" />
            <p v-if="errores.name" class="text-red-500 text-sm mb-2">{{ errores.name }}</p>

            <input v-model="usuarioEditar.email" autocomplete="email" class="w-full p-2 border rounded mb-3 bg-white"
              placeholder="Email" />
            <p v-if="errores.email" class="text-red-500 text-sm mb-2">{{ errores.email }}</p>

            <input v-model="usuarioEditar.password" type="password" autocomplete="new-password"
              class="w-full p-2 border rounded mb-3 bg-white" placeholder="Contraseña" />
            <p v-if="errores.password" class="text-red-500 text-sm mb-2">{{ errores.password }}</p>

            <select v-model="usuarioEditar.role" class="w-full p-2 border rounded mb-3 bg-white">
              <option value="">Seleccione rol</option>
              <option value="Operario">Operario</option>
              <option value="Administrador">Administrador</option>
            </select>
            <p v-if="errores.role" class="text-red-500 text-sm">{{ errores.role }}</p>

            <div class="flex justify-end gap-2 border-t pt-4">
              <button type="button" @click="cerrarModalEditar" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
              <button :disabled="procesando" type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50">
                {{ procesando ? 'Procesando...' : 'Guardar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </AppLayout>
</template>
