<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template> 
    <img src="/images/examen-de-orina.png" alt="logo" :class="className" v-bind="$attrs" />
</template>
