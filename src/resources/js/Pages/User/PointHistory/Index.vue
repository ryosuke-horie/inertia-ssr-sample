<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import PointBuyListItem from '@/Components/molecules/list/PointBuyListItem/PointBuyListItem.vue';
import PointExpiredListItem from '@/Components/molecules/list/PointExpiredListItem/PointExpiredListItem.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';

type pointHistory = {
  buyId: number;
  buyPoints: number;
  remainingPoints: number;
  isPaid: boolean;
  createdAt: string;
  expirationDate: string;
};

const props = defineProps<{
  totalPoints: number;
  paidPoints: number;
  freePoints: number;
  pointHistoryList: pointHistory[];
  pointExpiredList: pointHistory[];
}>();
</script>

<template>
  <BaseLayout
    title="チアポイント"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'チアポイント',
      href: RouteName.UserMypage,
    }"
  >
    <Heading2 text="ポイント残高" />
    <Divider />
    <div class="p-point-history">
      <div class="p-point-history__point">
        <div class="p-point-history__point-total">
          <span class="p-point-history__point-total-label">残高合計</span>
          <span class="p-point-history__point-total-num">{{ props.totalPoints.toLocaleString() }}</span>
          <span class="p-point-history__point-total-unit">CP</span>
        </div>
        <div class="p-point-history__point-paid">
          <span class="p-point-history__point-paid-label">有償ポイント</span>
          <span class="p-point-history__point-paid-num">{{ props.paidPoints.toLocaleString() }}</span>
          <span class="p-point-history__point-paid-unit">CP</span>
        </div>
        <div class="p-point-history__point-paid">
          <span class="p-point-history__point-paid-label">無償ポイント</span>
          <span class="p-point-history__point-paid-num">{{ props.freePoints.toLocaleString() }}</span>
          <span class="p-point-history__point-paid-unit">CP</span>
        </div>
      </div>
      <Divider />
      <ColorSection class="p-point-history__button">
        <AnkerButton variant="warning" :href="route(RouteName.UserPointCharge)" text="ポイント購入" />
      </ColorSection>
      <Divider />
      <Heading2 text="ポイント履歴" />
      <Divider />
      <template v-if="props.pointHistoryList.length">
        <PointBuyListItem
          v-for="item of props.pointHistoryList"
          :key="item.buyId"
          :date="item.createdAt"
          :points="item.buyPoints"
          :category="item.isPaid ? 1 : 2"
          :expired-at="item.expirationDate"
        />
      </template>
      <EmptySection v-else text="表示するデータが存在しません" />
      <Divider />
      <div class="p-point-history__button">
        <AnkerButton variant="secondary" :href="route(RouteName.UserPointHistoryPoint)" text="ポイント履歴一覧" />
      </div>
      <Divider />
      <Heading2 text="ポイント有効期限" />
      <Divider />
      <template v-if="props.pointExpiredList.length">
        <PointExpiredListItem
          v-for="item of props.pointExpiredList"
          :key="item.buyId"
          :points="item.remainingPoints"
          :category="item.isPaid ? 1 : 2"
          :expired-at="item.expirationDate"
        />
      </template>
      <EmptySection v-else text="表示するデータが存在しません" />
      <Divider />
      <div class="p-point-history__button">
        <AnkerButton variant="secondary" :href="route(RouteName.UserPointHistoryExpired)" text="ポイント有効期限一覧" />
      </div>
      <Divider />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-point-history {
  &__point {
    padding: 16px;
    box-shadow: 0 4 30 0 #00000010;
    font-weight: var(--bold);
    &-total {
      display: flex;
      align-items: center;
      &-label {
        font-size: 17px;
      }
      &-num {
        font-size: 37px;
        margin-left: auto;
      }
      &-unit {
        font-size: 16px;
        margin-top: 10px;
        margin-left: 4px;
      }
    }
    &-paid {
      display: flex;
      align-items: center;
      &-label {
        font-size: 14px;
      }
      &-num {
        font-size: 18px;
        margin-left: auto;
      }
      &-unit {
        font-size: 18px;
        margin-left: 4px;
      }
    }
  }
  &__button {
    display: flex;
    flex-direction: column;
    padding: 16px;
  }
}
</style>
