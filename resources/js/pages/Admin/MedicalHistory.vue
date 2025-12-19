<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    historia: Object
});

const form = useForm({
    id: props.historia.id,
    observations: props.historia.observations
});

const submit = () => {
    form.post('/admin/historia/actualizar', {
        preserveScroll: true,
        onSuccess: () => alert('¡Guardado con éxito!')
    });
};
</script>

<template>
    <Head title="Expediente Médico" />
    <AppLayout>
        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-8 shadow-xl rounded-lg border">
                    <div class="flex justify-between items-center border-b pb-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-800">{{ historia.full_name }}</h1>
                            <p class="text-gray-500 font-mono">Cédula: {{ historia.dni }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Última actualización:</p>
                            <p class="text-sm font-bold">{{ new Date(historia.updated_at).toLocaleDateString() }}</p>
                        </div>
                    </div>

                    <form @submit.prevent="submit">
                        <label class="block text-lg font-bold text-gray-700 mb-2">Evolución Médica y Observaciones:</label>
                        <textarea 
                            v-model="form.observations" 
                            class="w-full h-96 p-4 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 text-gray-700 font-serif leading-relaxed"
                            placeholder="Escriba aquí los síntomas, diagnóstico y tratamiento..."
                        ></textarea>

                        <div class="mt-6 flex justify-end">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition-all shadow-lg active:scale-95"
                            >
                                {{ form.processing ? 'Guardando...' : '💾 Guardar Historia Clínica' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>