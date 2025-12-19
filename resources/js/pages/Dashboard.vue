<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

// 1. Definir qué datos recibe este componente desde Laravel
defineProps<{
    citas: Array<{
        id: number;
        nombre: string;
        apellido: string;
        cedula: string;
        cita_hora: string;
        token_unique: string;
    }>
}>();

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
                
                <h2 class="text-xl font-bold mb-4">Citas Agendadas (Venezuela UTC-4)</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="p-3">Paciente</th>
                                <th class="p-3">Cédula</th>
                                <th class="p-3">Fecha y Hora</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cita in citas" :key="cita.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="p-3 uppercase">{{ cita.nombre }} {{ cita.apellido }}</td>
                                <td class="p-3">{{ cita.cedula }}</td>
                                <td class="p-3 font-mono text-xs">{{ cita.cita_hora }}</td>
                                <td class="p-3 text-center">
                                    <a :href="'/cita-confirmada/' + cita.token_unique" 
                                       target="_blank"
                                       class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                       Ver Ticket
                                    </a>
                                </td>
                            </tr>
                            
                            <tr v-if="citas.length === 0">
                                <td colspan="4" class="p-4 text-center text-gray-500">No hay citas registradas para hoy.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AppLayout>
</template>