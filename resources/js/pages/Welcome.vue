<script setup lang="ts">


import { useForm, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';


interface CustomProps {
    flash: {
        successToken?: string;
        error?: string;
    };
}

interface PageProps {
    flash: {
        successToken?: string;
        error?: string;
    };
    [key: string]: any;
}

const page = usePage<import('@inertiajs/core').PageProps & CustomProps>();


// 3. Capturamos el token del flash data de forma reactiva
const successToken = computed(() => page.props.flash?.successToken);


// 4. Configuración del formulario
const form = useForm({
    nombre: '',
    apellido: '',
    edad: '',
    cedula: '',
    cita_hora: '',
    departamento: '',
});

/**
 * Envío del formulario
 */
const submit = () => {
    form.post('/agendar', {
        preserveScroll: true,
        onSuccess: () => {
            console.log("Cita procesada con éxito");
            form.reset();
        },
        onError: (errors) => {
            console.error("Errores encontrados:", errors);
        }
    });
};

/**
 * Limpiar estado y regresar al formulario
 */
const resetPage = () => {
    window.location.href = '/';
};
</script>

<template>
    <Head title="Agendar Cita" />
    
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg border dark:bg-gray-800 dark:border-gray-700">
        
        <div v-if="successToken" class="text-center animate-in fade-in zoom-in duration-300">
            <div class="mb-4 text-green-600 text-6xl">✅</div>
            <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">¡Cita Reservada!</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Su ticket ha sido generado correctamente.</p>
            
            <a :href="'/descargar-ticket/' + successToken" 
               target="_blank" 
               class="block w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition text-center shadow-md active:scale-95">
                ⬇️ Descargar Ticket PDF
            </a>
            
            <button @click="resetPage" class="mt-6 text-sm text-indigo-600 font-medium underline hover:text-indigo-800 dark:text-indigo-400">
                Agendar otra cita
            </button>
        </div>

        <div v-else>
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white border-b pb-4 dark:border-gray-700">
                Agendar Cita Médica
            </h2>
            
            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nombre</label>
                        <input v-model="form.nombre" type="text" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Apellido</label>
                        <input v-model="form.apellido" type="text" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Edad</label>
                        <input v-model="form.edad" type="number" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Cédula</label>
                        <input v-model="form.cedula" type="text" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Fecha y Hora de Cita</label>
                    <input v-model="form.cita_hora" type="datetime-local" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Departamento</label>
                    <select v-model="form.departamento" class="w-full border rounded-md p-2 dark:bg-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600" required>
                        <option value="" disabled selected>Seleccione un departamento</option>
                        <option value="Cardiología">Ginecologia</option>
                        <option value="ARO">ARO</option>
                        <option value="Pediatria">Pediatría</option>
                        <option value="Medicina Interna jinet">Medicina Interna (jinet)</option>
                        <option value="Medicina Interna hayde">Medicina Interna (Haydee)</option>
                        <option value="Dermatologia">Prenatal</option>
                    </select>
                </div>

                <button type="submit" 
                        :disabled="form.processing" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition-all disabled:opacity-50 active:scale-95">
                    {{ form.processing ? 'Procesando registro...' : 'Reservar Cita Ahora' }}
                </button>

                <div v-if="page.props.flash?.error" class="mt-4 p-3 bg-red-100 text-red-700 text-sm rounded-lg border border-red-200 font-bold text-center">
                    ⚠️ {{ page.props.flash.error }}
                </div>
            </form>
        </div>
    </div>
</template>