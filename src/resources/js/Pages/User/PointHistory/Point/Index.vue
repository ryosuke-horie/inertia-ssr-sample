<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Button from '@/Components/atom/button/Button/Button.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import PointBuyListItem from '@/Components/molecules/list/PointBuyListItem/PointBuyListItem.vue';
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

const props = defineProps<{ pointHistoryList: pointHistory[] }>();

const offset = ref<number>(3);
const computedIsButton = computed<boolean>(() => props.pointHistoryList.length > offset.value);
const computedPointHistoryList = computed<pointHistory[]>(() => {
  return props.pointHistoryList.slice(0, offset.value);
});

const onIncrementOffset = (): void => {
  offset.value += 3;
};
</script>

<template>
  <BaseLayout
    title="ポイント履歴"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'ポイント履歴',
      href: RouteName.UserPointHistory,
    }"
  >
    <div class="p-user-point-history-point">
      <template v-if="computedPointHistoryList.length">
        <PointBuyListItem
          v-for="item of computedPointHistoryList"
          :key="item.buyId"
          :date="item.createdAt"
          :points="item.buyPoints"
          :category="item.isPaid ? 1 : 2"
          :expired-at="item.expirationDate"
        />
      </template>
      <EmptySection v-else text="表示するデータが存在しません" />
      <Divider />
      <div class="p-user-point-history-point__add">
        <Button v-if="computedIsButton" @click="onIncrementOffset" text="もっと見る" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-point-history-point {
  &__add {
    display: flex;
    flex-direction: column;
    padding: 16px;
  }
}
</style>
