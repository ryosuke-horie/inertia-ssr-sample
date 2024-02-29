<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Button from '@/Components/atom/button/Button/Button.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import PointExpiredListItem from '@/Components/molecules/list/PointExpiredListItem/PointExpiredListItem.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import { ref } from 'vue';
import { computed } from 'vue';

type pointHistory = {
  buyId: number;
  buyPoints: number;
  remainingPoints: number;
  isPaid: boolean;
  createdAt: string;
  expirationDate: string;
};

const props = defineProps<{ pointExpiredList: pointHistory[] }>();

const offset = ref<number>(3);
const computedIsButton = computed<boolean>(() => props.pointExpiredList.length > offset.value);
const computedExpiredList = computed<pointHistory[]>(() => {
  return props.pointExpiredList.slice(0, offset.value);
});

const onIncrementOffset = (): void => {
  offset.value += 3;
};
</script>

<template>
  <BaseLayout
    title="ポイント有効期限"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'ポイント有効期限',
      href: RouteName.UserPointHistory,
    }"
  >
    <div class="p-user-point-history-expired">
      <template v-if="computedExpiredList.length">
        <PointExpiredListItem
          v-for="item of computedExpiredList"
          :key="item.buyId"
          :points="item.remainingPoints"
          :category="item.isPaid ? 1 : 2"
          :expired-at="item.expirationDate"
        />
      </template>
      <EmptySection v-else text="表示するデータが存在しません" />
      <Divider />
      <div class="p-user-point-history-expired__add">
        <Button v-if="computedIsButton" @click="onIncrementOffset" text="もっと見る" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-point-history-expired {
  &__add {
    display: flex;
    flex-direction: column;
    padding: 16px;
  }
}
</style>
