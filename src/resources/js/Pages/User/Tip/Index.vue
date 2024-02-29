<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import TipListItem from '@/Components/molecules/list/TipListItem/TipListItem.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import { computed } from 'vue';

const props = defineProps<{
  userTips: {
    tipId: number;
    image?: string | null;
    staffName: string;
    points: number;
    createdAt: string;
    isRead: boolean;
  }[];
}>();

const hasUnRead = computed(() => props.userTips.some((tip) => !tip.isRead));
</script>

<template>
  <BaseLayout
    title="応援履歴一覧"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      role: RouteRoleName.User,
      text: '応援履歴',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-point-history">
      <template v-if="hasUnRead">
        <div class="p-point-history__unread">未読メッセージがあります</div>
        <Divider />
      </template>
      <ul v-if="props.userTips.length">
        <TipListItem
          v-for="(item, index) of props.userTips"
          type="user"
          :key="item.staffName + index"
          :href="route(RouteName.UserTipsShow, { tipId: item.tipId })"
          :src="item.image || ''"
          :point="item.points"
          :name="item.staffName"
          :date="item.createdAt"
          :is-read="item.isRead"
        />
      </ul>
      <EmptySection v-else text="表示するデータが存在しません" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-point-history {
  &__unread {
    padding: 8px 16px;
    font-size: 16px;
    font-weight: var(--bold);
  }
}
</style>
