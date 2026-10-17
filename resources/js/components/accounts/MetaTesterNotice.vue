<script setup lang="ts">
import { IconInfoCircle } from '@tabler/icons-vue';

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
</script>

<template>
    <Alert dusk="meta-tester-notice">
        <IconInfoCircle class="size-4" />
        <AlertTitle>{{ $t('notices.meta_tester.title') }}</AlertTitle>
        <AlertDescription>
            {{ $t('notices.meta_tester.description') }}
        </AlertDescription>
    </Alert>
</template>
