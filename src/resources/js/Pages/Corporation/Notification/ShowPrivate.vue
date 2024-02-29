<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout/AuthLayout.vue';
import { type AuthLayoutProps } from '@/Layouts/AuthLayout/type';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';

const authLayoutProps: AuthLayoutProps = {
  isAuthRoute: true,
  title: 'チアペイ',
  header: {
    role: RouteRoleName.Corporation,
    text: 'お知らせ',
    href: RouteName.CorporationNotification,
  },
};

const props = defineProps<{
  title: string;
  startAt: string;
  content: string;
}>();
</script>

<template>
  <AuthLayout v-bind="authLayoutProps">
    <div class="p-private-notification-show">
      <Heading2 :text="props.title" class="p-private-notification-heading" />
      <Divider class="p-private-notification-show__divider" />
      <Paragraph class="p-private-notification-show__date">{{ props.startAt }}</Paragraph>
      <Paragraph class="p-private-notification-show__content">{{ props.content }}</Paragraph>
    </div>
  </AuthLayout>
</template>

<style lang="scss">
.p-private-notification-show {
  padding: 0px 20px;
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
