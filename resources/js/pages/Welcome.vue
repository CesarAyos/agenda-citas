<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

// Captura el token del flash data (con protección ?. para que no dé error al cargar)
const successToken = computed(() => page.props.flash?.successToken);


const form = useForm({
    nombre: '',
    apellido: '',
    edad: '',
    cedula: '',
    cita_hora: '',
});

// Welcome.vue - Cambia la función submit por esta:
const submit = () => {
    form.post('/agendar', {
        preserveScroll: true,
        onSuccess: (page) => {
            // Esto fuerza a Vue a ver el token si el redirect no lo hizo solo
            console.log("Respuesta recibida:", page.props.flash);
            form.reset();
        },
        onError: (errors) => {
            console.error("Errores de validación:", errors);
        }
    });
};

const resetPage = () => {
    // Esto recarga la página correctamente sin errores de "undefined"
    window.location.href = '/';
};
</script>

<template>
    <Head title="Agendar Cita" />
    
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg border dark:bg-gray-800">
        
        <div v-if="successToken" class="text-center">
            <div class="mb-4 text-green-600 text-5xl">✅</div>
            <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">¡Cita Reservada!</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Su ticket ha sido generado correctamente.</p>
            
            <a :href="'/descargar-ticket/' + successToken" 
               target="_blank" 
               class="block w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition text-center shadow-md">
                ⬇️ Descargar Ticket PDF
            </a>
            
            <button @click="resetPage" class="mt-4 text-sm text-blue-600 underline hover:text-blue-800">
    Agendar otra cita
</button>
        </div>

        <div v-else>
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">Agendar Cita Médica</h2>
            
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input v-model="form.nombre" type="text" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                    <input v-model="form.apellido" type="text" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Edad</label>
                        <input v-model="form.edad" type="number" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cédula</label>
                        <input v-model="form.cedula" type="text" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha y Hora</label>
                    <input v-model="form.cita_hora" type="datetime-local" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white" required>
                </div>

                <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700 shadow-lg transition">
                    {{ form.processing ? 'Procesando...' : 'Reservar Cita' }}
                </button>

                <div v-if="form.errors.error" class="mt-4 p-2 bg-red-100 text-red-700 text-sm rounded border border-red-200 font-semibold">
                    {{ form.errors.error }}
                </div>
            </form>
        </div>
    </div>
</template>