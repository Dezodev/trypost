<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import GuestLayout from '@/layouts/GuestLayout.vue';

defineProps<{
    contactEmail: string;
}>();

const sections = [
    'collect',
    'social',
    'use',
    'sharing',
    'retention',
    'deletion',
    'rights',
    'cookies',
    'changes',
];
</script>

<template>
    <Head :title="$t('legal.privacy.title')" />

    <GuestLayout :title="$t('legal.privacy.title')">
        <div class="flex flex-col gap-6" dusk="legal-privacy">
            <section v-for="section in sections" :key="section" class="flex flex-col gap-2">
                <h2 class="text-xl font-semibold">{{ $t(`legal.privacy.sections.${section}.title`) }}</h2>
                <p class="text-muted-foreground">{{ $t(`legal.privacy.sections.${section}.body`) }}</p>
            </section>

            <p class="text-sm text-muted-foreground">
                {{ $t('legal.contact', { email: contactEmail }) }}
            </p>
        </div>
    </GuestLayout>
</template>
