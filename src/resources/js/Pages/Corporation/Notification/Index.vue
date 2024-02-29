<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import NotificationListItem from '@/Components/molecules/list/NotificationListItem/NotificationListItem.vue';

interface NotificationItems {
  notificationId: number;
  isRead: boolean;
  title: string;
  startAt: string;
  url: string;
  fileType: string;
  fileName: string;
  privateFlag: boolean;
}

const props = defineProps<{ notifications: NotificationItems[] }>();

const getRouteName = (flag: boolean, fileType: string): RouteName => {
  if (flag) {
    return RouteName.CorporationNotificationShowPrivate;
  }

  if (fileType === 'pdf') {
    return RouteName.CorporationPdf;
  }

  return RouteName.CorporationNotificationShow;
};
</script>

<template>
  <BaseLayout
    title="お知らせ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: 'お知らせ',
      href: RouteName.CorporationMypage,
    }"
  >
    <div class="p-notification-index">
      <ul>
        <NotificationListItem
          v-for="(item, index) of props.notifications"
          :key="index"
          :is-read="item.isRead"
          :title="item.title"
          :start-at="item.startAt"
          :url="route(getRouteName(item.privateFlag, item.fileType), { notificationId: item.notificationId })"
        />
      </ul>
    </div>
  </BaseLayout>
</template>

<style lang="scss"></style>
