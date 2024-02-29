<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Notification from '@/Components/organizms/notification/Notification/Notification.vue';

const props = defineProps<{
  title: string;
  startAt: string;
  content: string;
  file: string;
}>();
</script>

<template>
  <BaseLayout
    title="お知らせ詳細"
    :is-auth="true"
    :role="RouteRoleName.Staff"
    :auth-header="{
      text: 'お知らせ',
      href: RouteName.StaffNotification,
    }"
  >
    <div class="p-notification-show">
      <Notification :title="props.title" :start-at="props.startAt" :content="props.content" :src="props.file" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-notification-show {
  padding: 20px;
}
</style>
