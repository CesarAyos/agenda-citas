<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    historia: Object
});

// Iniciamos el formulario con los datos que ya existen en la base de datos
const form = useForm({
    id: props.historia.id,
    numero_historia: props.historia.numero_historia || '', 
    observations: props.historia.observations || ''
});

// En MedicalHistoria.vue
const submit = () => {
    form.post('/admin/historia/actualizar', { // <--- URL manual
        preserveScroll: true,
        onSuccess: () => alert('¡Guardado con éxito!')
    });
};
</script>

<template>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl mt-7 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b dark:border-gray-700">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Asignación de Expediente
            </h3>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1 ml-1">
                    N° de Historia Médica
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <span class="text-sm">#</span>
                    </span>
                    <input 
                        v-model="form.numero_historia" 
                        type="text" 
                        class="block w-full pl-8 pr-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-gray-400 text-sm"
                        placeholder="Ej: A-101"
                    >
                </div>
                <p v-if="form.errors?.numero_historia" class="text-red-500 text-xs mt-1 font-medium italic">
                    {{ form.errors.numero_historia }}
                </p>
            </div>

            <button 
                @click="submit" 
                :disabled="form.processing"
                class="w-full flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white px-4 py-3 rounded-lg font-bold text-sm transition-all shadow-lg shadow-indigo-200 dark:shadow-none active:transform active:scale-95"
            >
                <template v-if="form.processing">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Procesando...
                </template>
                <template v-else>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Ficha
                </template>
            </button>
        </div>
    </div>
</template>