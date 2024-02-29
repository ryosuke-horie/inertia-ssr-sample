<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  totalPoint: number;
  businessId: number;
  yearMonthList: {
    yearMonth: string;
    totalAmount: number;
    isExchange: boolean;
  }[];
}>();
</script>

<template>
  <BaseLayout
    title="ポイント集計"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'ポイント集計',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-business-operator-point">
      <div class="p-business-operator-point__total">
        <Paragraph class="white p-business-operator-point__total__title">未交換ポイント</Paragraph>
        <Paragraph class="white">
          <span class="p-business-operator-point__total__value">{{ props.totalPoint }}</span
          ><span>CP</span>
        </Paragraph>
      </div>
    </div>
    <div class="p-business-operator-point__wrapper">
      <Link
        v-for="(item, index) in props.yearMonthList"
        :key="index"
        :href="route(RouteName.BusinessOperatorPointStaff, { yearMonth: item.yearMonth })"
        class="p-business-operator-point__wrapper__list"
      >
        <div class="p-business-operator-point__wrapper__list__item">
          <Paragraph class="p-business-operator-point__wrapper__list__item__date">
            {{ item.yearMonth
            }}<span class="p-business-operator-point__wrapper__list__item__date__exchange" v-if="item.isExchange"
              >交換済</span
            >
          </Paragraph>
          <Paragraph>{{ item.totalAmount }} CP</Paragraph>
        </div>
        <Divider />
      </Link>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-point {
  &__total {
    background-color: #517dbc;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    &__title {
      font-size: 17px;
      font-weight: 700;
    }
    &__value {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
  }
  &__wrapper {
    &__list {
      font-size: 18px;
      font-weight: 500;
      letter-spacing: -0.015em;
      &__item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        &__date {
          font-size: 14px;
          &__exchange {
            font-size: 12px;
            background-color: #ff0000;
            color: white;
            padding: 3px 6px;
            border-radius: 8px;
            margin-left: 8px;
          }
        }
      }
    }
  }
}
</style>
