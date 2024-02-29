<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';

const props = defineProps<{
  title: string;
  startAt: string;
  content: string;
  file: string;
}>();
</script>

<template>
  <BaseLayout
    title="お知らせ"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      role: RouteRoleName.User,
      text: 'お知らせ',
      href: RouteName.UserNotification,
    }"
  >
    <div class="p-notification-show">
      <Heading2 :text="props.title" class="p-notification-heading" />
      <Divider class="p-notification-show__divider" />
      <Paragraph class="p-notification-show__date">{{ props.startAt }}</Paragraph>
      <img
        v-if="props.file"
        src="https://www.mamiya-its.co.jp/public/images/1_top/topimage_sp_b.jpg"
        class="p-notification-show__image"
      />
      <Paragraph class="p-notification-show__content">{{ props.content }}</Paragraph>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-notification-show {
  padding: 0px 20px;
  &__image {
    width: 100%;
  }
  &__divider {
    border-bottom: 2px solid rgb(206, 206, 206);
  }
  &__date {
    text-align: right;
    margin: 5px 0px;
  }
  &__content {
    white-space: break-spaces;
  }
}
</style>
