<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';

const props = defineProps<{
  requestId: number;
  businessName: string;
  transactionPeriodFrom: string;
  transactionPeriodTo: string;
  allocateAmount: string;
  staffList: {
    staffName: string;
    allocateAmount: string;
    src: string;
  }[];
}>();
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: props.businessName + '\n' + props.transactionPeriodFrom + '～' + props.transactionPeriodTo + '分',
      href: RouteName.CorporationDepositDetailsBusiness,
      params: { requestId: props.requestId },
    }"
  >
    <div class="p-business-operator-deposit-details-staff">
      <div class="p-business-operator-deposit-details-staff__total">
        <div>
          <Paragraph class="white p-business-operator-deposit-details-staff__total__title"> 売上入金合計 </Paragraph>
        </div>
        <div class="p-business-operator-deposit-details-staff__total__value">
          <Paragraph class="white">
            <span class="p-business-operator-deposit-details-staff__total__value__amount">{{
              props.allocateAmount
            }}</span>
            <span>円</span>
          </Paragraph>
        </div>
      </div>
    </div>
    <div class="p-business-operator-deposit-details-staff__wrapper">
      <div
        v-for="(item, index) in props.staffList"
        :key="index"
        class="p-business-operator-deposit-details-staff__wrapper__list"
      >
        <div class="p-business-operator-deposit-details-staff__wrapper__list__item">
          <div class="p-business-operator-deposit-details-staff__wrapper__list__item__info">
            <IconImage
              :src="item.src"
              :width="40"
              :height="40"
              class="p-business-operator-deposit-details-staff__wrapper__list__item__info__icon"
            />
            <Paragraph class="p-business-operator-deposit-details-staff__wrapper__list__item__name">
              {{ item.staffName }}
            </Paragraph>
          </div>
          <Paragraph>{{ item.allocateAmount }}円</Paragraph>
        </div>
        <Divider />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-deposit-details-staff {
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
      display: flex;
      align-items: center;
      &__amount {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.015em;
      }
      &__button {
        display: inline;
        color: white;
        border: 1px solid;
        font-size: 14px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        margin-left: 5px;
      }
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
  &__modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    &__overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.4);
    }
    &__content {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 250px;
      transform: translate(-50%, -50%);
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px #00000050;
      background-color: var(--white);
      display: grid;
      justify-content: center;
      &__title {
        text-align: center;
        padding: 20px 0;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.015em;
      }
      & > table {
        text-align: right;
        font-size: 18px;
        font-weight: 500;
        letter-spacing: -0.015em;
        border-collapse: collapse;
      }
      &__divider {
        background-color: black;
        margin-bottom: 10px;
      }
      &__total {
        text-align: right;
        &__value {
          font-size: 24px;
          font-weight: 500;
          letter-spacing: -0.015em;
        }
      }
      &__button {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.015em;
        padding: 10px 0;
        color: #1d9bf0;
      }
    }
  }
}
</style>
