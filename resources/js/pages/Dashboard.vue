<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Cita {
    id: number;
    nombre: string;
    apellido: string;
    cedula: string;
    cita_hora: string;
    token_unique: string;
    numero_ficha?: string; // Nuevo campo opcional
}

const props = defineProps<{
    citas: Cita[]
}>();

const search = ref('');

const citasFiltradas = computed(() => {
    if (!props.citas) return [];
    return props.citas.filter(cita => {
        return cita.cedula.toLowerCase().includes(search.value.toLowerCase()) ||
               cita.nombre.toLowerCase().includes(search.value.toLowerCase());
    });
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-900 p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-xl font-bold dark:text-white text-gray-800">Citas Agendadas (Venezuela UTC-4)</h2>
                    
                    <div class="relative w-full md:w-80">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Buscar por cédula o nombre..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="p-3 uppercase font-bold">Paciente</th>
                                <th class="p-3 uppercase font-bold text-blue-600 dark:text-blue-400">N° Historia</th>
                                <th class="p-3 uppercase font-bold">Cédula</th>
                                <th class="p-3 uppercase font-bold">Fecha y Hora</th>
                                <th class="p-3 text-center uppercase font-bold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cita in citasFiltradas" :key="cita.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="p-3 uppercase dark:text-gray-200">
                                    {{ cita.nombre }} {{ cita.apellido }}
                                </td>
                                
                                <td class="p-3">
                                    <span :class="cita.numero_ficha === 'Sin asignar' ? 'text-red-500 italic text-xs' : 'font-bold dark:text-green-400 text-green-700'">
                                        {{ cita.numero_ficha }}
                                    </span>
                                </td>

                                <td class="p-3 font-mono dark:text-gray-200">{{ cita.cedula }}</td>
                                <td class="p-3 font-mono text-xs dark:text-gray-300">
                                    {{ cita.cita_hora }}
                                </td>
                                <td class="p-3 text-center space-x-2">
                                    <Link :href="'/admin/historia/' + cita.cedula" 
                                          class="inline-block px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-xs font-bold">
                                        📂 Historia
                                    </Link>
                                    
                                    <a :href="'/descargar-ticket/' + cita.token_unique" 
                                       target="_blank"
                                       class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs font-bold">
                                        🎫 Ticket
                                    </a>
                                </td>
                            </tr>
                            
                            <tr v-if="citasFiltradas.length === 0">
                                <td colspan="5" class="p-10 text-center text-gray-500 dark:text-gray-400 italic">
                                    No se encontraron resultados.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AppLayout>
</template>