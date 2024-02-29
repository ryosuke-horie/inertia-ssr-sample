<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import { Link } from '@inertiajs/vue3';

type businessOperatorProps = {
  businessId: number;
  businessName: string;
  totalAmount: number;
  src: string;
};

const props = defineProps<{
  yearMonth: string;
  yearMonthDisplay: string;
  totalPoint: number;
  isExchange: boolean;
  businessOperators: businessOperatorProps[];
}>();
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: props.yearMonthDisplay,
      href: RouteName.CorporationPoint,
    }"
  >
    <div class="p-business-operator-point-staff">
      <div class="p-business-operator-point-staff__total">
        <div>
          <Paragraph class="white p-business-operator-point-staff__total__title">合計ポイント</Paragraph>
          <Paragraph class="white p-business-operator-point-staff__total__exchange" v-if="props.isExchange">
            交換済
          </Paragraph>
        </div>
        <Paragraph class="white">
          <span class="p-business-operator-point-staff__total__value">
            {{ props.totalPoint }}
          </span>
          <span>CP</span>
        </Paragraph>
      </div>
    </div>
    <div class="p-business-operator-point-staff__wrapper">
      <Link
        class="p-business-operator-point-staff__wrapper__list"
        v-for="(item, index) in props.businessOperators"
        :key="index"
        :href="route(RouteName.CorporationPointStaff, { yearMonth: props.yearMonth, businessId: item.businessId })"
      >
        <div class="p-business-operator-point-staff__wrapper__list__item">
          <div class="p-business-operator-point-staff__wrapper__list__item__info">
            <IconImage
              :src="item.src"
              :width="40"
              :height="40"
              class="p-business-operator-point-staff__wrapper__list__item__info__icon"
            />
            <Paragraph class="p-business-operator-point-staff__wrapper__list__item__name">
              {{ item.businessName }}
            </Paragraph>
          </div>
          <Paragraph>{{ item.totalAmount }} CP</Paragraph>
        </div>
        <Divider />
      </Link>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-point-staff {
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
    &__exchange {
      font-size: 12px;
      background-color: #ff0000;
      padding: 3px 3px;
      border-radius: 8px;
      width: 50px;
      text-align: center;
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
        &__info {
          display: flex;
          &__icon {
            margin-right: 15px;
          }
        }
        &__name {
          font-size: 12px;
          width: 140px;
          display: flex;
          align-items: center;
        }
      }
    }
  }
}
</style>
