<script lang="ts" setup>
import NotificationPDF from '@/Components/organizms/notification/NotificationPDF/NotificationPDF.vue';
const props = defineProps<{ path: string }>();
</script>

<template>
  <NotificationPDF :path="props.path" />
</template>
