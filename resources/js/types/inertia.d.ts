import { PageProps as InertiaPageProps } from '@inertiajs/core';

// Extender los tipos globales de Inertia
declare module '@inertiajs/vue3' {
    export interface RequestPayload {
        _token?: string;
        [key: string]: any;
    }
}

// Para formularios FormData
interface ExtendedFormData extends FormData {
    _token?: string;
}