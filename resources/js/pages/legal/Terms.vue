<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import GuestLayout from '@/layouts/GuestLayout.vue';

defineProps<{
    contactEmail: string;
}>();

const sections = [
    'acceptance',
    'service',
    'accounts',
    'third_party',
    'content',
    'acceptable_use',
    'billing',
    'termination',
    'disclaimer',
    'changes',
];
</script>

<template>
    <Head :title="$t('legal.terms.title')" />

    <GuestLayout :title="$t('legal.terms.title')">
        <div class="flex flex-col gap-6" dusk="legal-terms">
            <section v-for="section in sections" :key="section" class="flex flex-col gap-2">
                <h2 class="text-xl font-semibold">{{ $t(`legal.terms.sections.${section}.title`) }}</h2>
                <p class="text-muted-foreground">{{ $t(`legal.terms.sections.${section}.body`) }}</p>
            </section>

            <p class="text-sm text-muted-foreground">
                {{ $t('legal.contact', { email: contactEmail }) }}
            </p>
        </div>
    </GuestLayout>
</template>
