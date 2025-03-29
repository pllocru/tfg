<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const login = async () => {
    loading.value = true
    error.value = ''

    try {
        const res = await api.post('/login', {
            email: email.value,
            password: password.value
        })

        localStorage.setItem('token', res.data.token)
        api.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`
        router.push('/')
    } catch (err) {
        if (err.response?.status === 422) {
            error.value = 'Por favor, completa todos los campos correctamente.'
        } else if (err.response?.status === 401) {
            error.value = 'Credenciales incorrectas.'
        } else {
            error.value = 'Ocurrió un error inesperado.'
        }
    } finally {
        loading.value = false // ✅ SIEMPRE se ejecuta, pase lo que pase
    }
}
</script>



<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md">

            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <h1 class="text-3xl font-bold text-center mb-6">Iniciar Sesión</h1>
                <form @submit.prevent="login" class="space-y-4">
                    <input v-model="email" type="email" placeholder="Correo electrónico"
                        class="w-full p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input v-model="password" type="password" placeholder="Contraseña"
                        class="w-full p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button :disabled="loading" type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold p-3 rounded-xl transition disabled:opacity-50">
                        {{ loading ? 'Entrando...' : 'Entrar' }}
                    </button>

                    <p v-if="error" class="text-red-500 text-center mt-2">{{ error }}</p>
                </form>
            </div>

        </div>
    </div>
</template>
