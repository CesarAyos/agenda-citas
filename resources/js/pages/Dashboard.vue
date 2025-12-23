<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

// 1. DEFINICIÓN DE INTERFACES
interface Cita {
    id: number;
    nombre: string;
    apellido: string;
    cedula: string;
    cita_hora: string;
    departamento: string;
    token_unique: string;
    numero_ficha?: string; 
}

interface Paciente {
    id: number;
    cedula: string;
    nombre_completo: string;
    numero_history: string; 
    departamento: string;
    numero_historia?: string; 
}

// 2. PROPS
const props = defineProps<{
    citas: Cita[],
    pacientesMaster: Paciente[]
}>();

// 3. ESTADOS REACTIVOS
const search = ref('');
const showModal = ref(false); 
const vistaActual = ref('citas');

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Panel de Control - Central de Citas',
        href: dashboard().url,
    },
];

// 4. FORMULARIO INERTIA CON CSRF
const formNuevo = useForm({
    cedula: '',
    nombre_completo: '',
    numero_historia: '',
    _token: '', // ← AGREGAR ESTE CAMPO
});

// 5. MÉTODOS
const registrarPaciente = () => {
    // Obtener token CSRF del meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (csrfToken) {
        // Asignar el token al formulario
        formNuevo._token = csrfToken;
        
        // Log para debug
        console.log('🔐 Enviando formulario con token CSRF:', csrfToken.substring(0, 20) + '...');
    } else {
        console.error('❌ No se encontró token CSRF en el HTML');
    }

    formNuevo.post('/admin/pacientes/registrar', {
        onSuccess: () => {
            showModal.value = false;
            formNuevo.reset();
            // Restaurar el token después del reset
            const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (newToken) formNuevo._token = newToken;
        },
        onError: (errors) => {
            console.error('❌ Error en registro:', errors);
            
            // Restaurar token después de error
            const currentToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (currentToken) formNuevo._token = currentToken;
        }
    });
};

// 6. LÓGICA DE BÚSQUEDA (COMPUTED)
const citasFiltradas = computed(() => {
    if (!props.citas) return [];
    return props.citas.filter(cita => {
        const busqueda = search.value.toLowerCase();
        return cita.cedula.toLowerCase().includes(busqueda) ||
               cita.nombre.toLowerCase().includes(busqueda);
    });
});

const pacientesFiltradosMaster = computed(() => {
    if (!props.pacientesMaster) return [];
    return props.pacientesMaster.filter(p => {
        const busqueda = search.value.toLowerCase();
        return p.cedula.toLowerCase().includes(busqueda) ||
               (p.nombre_completo && p.nombre_completo.toLowerCase().includes(busqueda));
    });
});

const abrirModal = () => {
    // Obtener token CSFR actual al abrir el modal
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formNuevo._token = csrfToken;
    }
    
    formNuevo.clearErrors();
    showModal.value = true;
};

// 7. INICIALIZAR TOKEN AL MONTAR EL COMPONENTE
onMounted(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        formNuevo._token = csrfToken;
        console.log('✅ Token CSRF inicializado:', csrfToken.substring(0, 20) + '...');
    } else {
        console.warn('⚠️ Token CSRF no encontrado en el HTML');
    }
});
</script>

<template>
    <Head title="Dashboard"></Head>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="relative min-h-screen flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-gray-900 p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b pb-4 dark:border-gray-700">
                    <div>
                        <button @click="abrirModal" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 flex items-center gap-2 transition text-sm">
                            <span>+</span> Registrar Paciente
                        </button>
                    </div>
                    
                    <div class="relative w-full md:w-80">
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Buscar..." 
                            class="block w-full pl-4 pr-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white"
                        >
                    </div>
                </div>

                <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
                    <button @click="vistaActual = 'citas'" :class="vistaActual === 'citas' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500'" class="px-6 py-2 font-bold transition">
                        📅 Citas
                    </button>
                    <button @click="vistaActual = 'pacientes'" :class="vistaActual === 'pacientes' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500'" class="px-6 py-2 font-bold transition">
                        👥 Base de Datos
                    </button>
                </div>

                <div v-if="vistaActual === 'citas'" class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 uppercase font-bold text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="p-3">Paciente</th>
                                <th class="p-3">N° Historia</th>
                                <th class="p-3">Cédula</th>
                                <th class="p-3">Departamento</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cita in citasFiltradas" :key="cita.id" class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="p-3 uppercase font-medium dark:text-gray-200">{{ cita.nombre }} {{ cita.apellido }}</td>
                                <td class="p-3 text-blue-600 font-bold">{{ cita.numero_ficha }}</td>
                                <td class="p-3 font-mono dark:text-gray-300">{{ cita.cedula }}</td>
                                <td class="p-3 font-mono dark:text-gray-300">{{ cita.departamento }}</td>
                                <td class="p-3 text-center">
                                    <Link :href="'/admin/historia/' + cita.cedula" class="bg-green-600 text-white px-3 py-1 rounded text-xs font-bold">📂 Historia</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="vistaActual === 'pacientes'" class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 uppercase font-bold text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="p-3">Nombre Completo</th>
                                <th class="p-3">N° Historia</th>
                                <th class="p-3">Cédula</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="paciente in pacientesFiltradosMaster" :key="paciente.id" class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="p-3 uppercase font-medium dark:text-white">{{ paciente.nombre_completo }}</td>
                                <td class="p-3 font-bold text-green-600 dark:text-green-400">{{ paciente.numero_historia || paciente.numero_history }}</td>
                                <td class="p-3 font-mono dark:text-gray-300">{{ paciente.cedula }}</td>
                                
                                <td class="p-3 text-center">
                                    <Link :href="'/admin/historia/' + paciente.cedula" class="text-indigo-600 hover:underline font-bold transition">Editar numero de Historia</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-md p-6 border dark:border-gray-700">
                <div class="flex justify-between items-center mb-4 border-b pb-2 dark:border-gray-700">
                    <h3 class="text-xl font-bold dark:text-white">Nuevo Paciente</h3>
                    <button @click="showModal = false" class="text-gray-400 text-2xl hover:text-gray-600 transition">×</button>
                </div>

                <form @submit.prevent="registrarPaciente" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Cédula</label>
                        <input 
                            v-model="formNuevo.cedula" 
                            type="text" 
                            placeholder="Ej: 12345678"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500"
                            :class="{'border-red-500 ring-1 ring-red-500': formNuevo.errors.cedula}"
                            required
                        >
                        <p v-if="formNuevo.errors.cedula" class="text-red-500 text-xs mt-1 font-semibold">{{ formNuevo.errors.cedula }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nombre Completo</label>
                        <input 
                            v-model="formNuevo.nombre_completo" 
                            type="text" 
                            placeholder="Nombre del paciente"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500"
                            :class="{'border-red-500 ring-1 ring-red-500': formNuevo.errors.nombre_completo}"
                            required
                        >
                        <p v-if="formNuevo.errors.nombre_completo" class="text-red-500 text-xs mt-1 font-semibold">{{ formNuevo.errors.nombre_completo }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">N° Historia</label>
                        <input 
                            v-model="formNuevo.numero_historia" 
                            type="text" 
                            placeholder="N° correlativo"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500"
                            :class="{'border-red-500 ring-1 ring-red-500': formNuevo.errors.numero_historia}"
                            required
                        >
                        <p v-if="formNuevo.errors.numero_historia" class="text-red-500 text-xs mt-1 font-semibold">{{ formNuevo.errors.numero_historia }}</p>
                    </div>

                    <!-- Campo oculto para token CSRF (opcional) -->
                    <input type="hidden" name="_token" :value="formNuevo._token">

                    <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700 mt-2">
                        <button type="button" @click="showModal = false" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2 transition">Cancelar</button>
                        <button type="submit" :disabled="formNuevo.processing" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 disabled:opacity-50 transition shadow-md">
                            {{ formNuevo.processing ? 'Guardando...' : 'Guardar Paciente' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>