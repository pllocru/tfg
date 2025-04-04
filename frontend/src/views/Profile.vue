<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <!-- Sección de editar nombre y correo electrónico -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-2xl font-bold mb-4">Editar Nombre y Correo Electrónico</h2>
                <form @submit.prevent="guardarNombreYCorreo">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nuevo Nombre:</label>
                        <input v-model="nuevoNombre" type="text"
                            class="w-full p-2 border border-gray-300 rounded-md mt-1" placeholder="Nuevo nombre" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nuevo Correo Electrónico:</label>
                        <input v-model="nuevoCorreo" type="email"
                            class="w-full p-2 border border-gray-300 rounded-md mt-1"
                            placeholder="Nuevo correo electrónico" />
                    </div>

                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Guardar
                        Cambios</button>
                </form>
            </div>

            <!-- Sección de cambio de contraseña -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-2xl font-bold mb-4">Cambiar Contraseña</h2>
                <form @submit.prevent="cambiarContraseña">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nueva Contraseña:</label>
                        <input v-model="nuevaContraseña" type="password"
                            class="w-full p-2 border border-gray-300 rounded-md mt-1" placeholder="Nueva contraseña" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña:</label>
                        <input v-model="confirmarContraseña" type="password"
                            class="w-full p-2 border border-gray-300 rounded-md mt-1"
                            placeholder="Confirmar nueva contraseña" />
                    </div>

                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Confirmar</button>
                </form>
            </div>

            <!-- Sección de eliminar cuenta -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4 text-red-600">Eliminar Cuenta</h2>
                <p class="text-sm text-gray-700 mb-4">Si deseas eliminar tu cuenta, ten en cuenta que esta acción no se
                    puede deshacer.</p>
                <button @click="confirmarEliminarCuenta"
                    class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Eliminar Cuenta</button>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import api from '@/services/api';  // Importa la configuración de Axios

export default {
    components: {
        AppLayout
    },
    data() {
        return {
            user: {
                name: 'admin', // Datos del usuario, deberías obtenerlos del backend o localStorage
                email: 'user@example.com',
            },
            nuevoNombre: '',
            nuevoCorreo: '',
            nuevaContraseña: '',
            confirmarContraseña: '',
        };
    },
    methods: {
        async guardarNombreYCorreo() {
            try {
                // Hacemos la solicitud para guardar los cambios de nombre y correo electrónico
                const response = await api.put('/me', {
                    name: this.nuevoNombre,
                    email: this.nuevoCorreo
                });

                // Mostrar éxito
                console.log(response.data);
                alert('Nombre y Correo guardados');
            } catch (error) {
                console.error('Error al guardar nombre y correo:', error);
                alert('Error al guardar nombre y correo');
            }
        },

        async guardarCambios() {
            try {
                // Hacemos la solicitud para guardar los cambios de nombre y contraseña
                const response = await api.put('/me', {
                    name: this.nuevoNombre,
                    password: this.nuevaContraseña
                });

                // Mostrar éxito
                console.log(response.data);
                alert('Cambios guardados');
            } catch (error) {
                console.error('Error al guardar cambios:', error);
                alert('Error al guardar cambios');
            }
        },

        async cambiarContraseña() {
            if (this.nuevaContraseña !== this.confirmarContraseña) {
                alert('Las contraseñas no coinciden');
                return;
            }

            try {
                // Hacemos la solicitud para cambiar la contraseña
                const response = await api.put('/me/password', {
                    password: this.nuevaContraseña
                });

                // Mostrar éxito
                console.log(response.data);
                alert('Contraseña cambiada');
            } catch (error) {
                console.error('Error al cambiar la contraseña:', error);
                alert('Error al cambiar la contraseña');
            }
        },

        async confirmarEliminarCuenta() {
            const confirmar = confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');
            if (confirmar) {
                try {
                    // Hacemos la solicitud para eliminar la cuenta
                    const response = await api.delete('/me');

                    // Mostrar éxito
                    console.log(response.data);
                    alert('Cuenta eliminada');
                    this.$router.push('/login'); // Redirigir al login después de eliminar la cuenta
                } catch (error) {
                    console.error('Error al eliminar la cuenta:', error);
                    alert('Error al eliminar la cuenta');
                }
            }
        }
    }
};
</script>


<style scoped>
/* Estilos específicos para el perfil */
.container {
    max-width: 800px;
}

button {
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2b2d42;
}
</style>