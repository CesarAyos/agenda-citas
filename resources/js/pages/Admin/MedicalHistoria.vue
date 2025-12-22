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
    <div>
        <label class="block font-bold">Asignar N° de Historia:</label>
        <input 
            v-model="form.numero_historia" 
            type="text" 
            class="border rounded p-2 w-full text-gray-800"
            placeholder="Ej: A-101"
        >
    </div>

    <textarea v-model="form.observations" class="w-full mt-4 border p-2"></textarea>

    <button 
        @click="submit" 
        :disabled="form.processing"
        class="bg-blue-600 text-white px-4 py-2 mt-4 rounded font-bold"
    >
        {{ form.processing ? 'Guardando...' : 'Guardar Ficha' }}
    </button>
</template>