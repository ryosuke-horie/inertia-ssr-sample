<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import TotalCountSection from '@/Components/organizms/count/TotalCountSection/TotalCountSection.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import PointListItem from '@/Components/molecules/list/PointListItem/PointListItem.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { ref, computed } from 'vue';
import { PointListProps } from '@/Components/organizms/list/PointList/type';

const props = defineProps<{
  staffId: number;
  pointHistories: { transactionType: 1 | 2; pointChange: number; createdAt: string }[];
  points: number;
}>();

const offset = ref<number>(3);
const computedIsButton = computed<boolean>(() => props.pointHistories.length > offset.value);
const computedPointList = computed<PointListProps['list']>(() => {
  return props.pointHistories.slice(0, offset.value).map((history) => {
    return {
      category: history.transactionType,
      count: history.pointChange,
      date: history.createdAt,
    };
  });
});

const onIncrementOffset = (): void => {
  offset.value += 3;
};
</script>

<template>
  <BaseLayout
    title="チアポイント"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'チアポイント',
      href: RouteName.BusinessOperatorStaffShow,
      params: {
        staffId: props.staffId,
      },
    }"
  >
    <div class="p-point-history">
      <Heading2 text="ポイント残高" />
      <Divider />
      <TotalCountSection :count="props.points" label="残高合計" unit="CP" />
      <Divider />
      <ul v-if="computedPointList.length">
        <PointListItem
          v-for="(item, index) of computedPointList"
          :key="index"
          :category="item.category"
          :count="item.count"
          :date="item.date"
        />
      </ul>
      <EmptySection v-else text="ポイント履歴がありません。" />
      <Divider />
      <div v-if="computedIsButton" class="p-point-history__add">
        <Button @click="onIncrementOffset" text="もっと見る" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-point-history {
  &__add {
    display: flex;
    flex-direction: column;
    padding: 16px;
  }
}
</style>
