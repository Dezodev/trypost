<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import GuestLayout from '@/layouts/GuestLayout.vue';

defineProps<{
    confirmationCode: string;
}>();
</script>

<template>
    <Head :title="$t('legal.data_deletion.title')" />

    <GuestLayout :title="$t('legal.data_deletion.title')">
        <div class="flex flex-col gap-4" dusk="data-deletion-status">
            <p class="text-muted-foreground">{{ $t('legal.data_deletion.description') }}</p>
            <p class="text-sm font-medium">
                {{ $t('legal.data_deletion.confirmation_code', { code: confirmationCode }) }}
            </p>
        </div>
    </GuestLayout>
</template>
