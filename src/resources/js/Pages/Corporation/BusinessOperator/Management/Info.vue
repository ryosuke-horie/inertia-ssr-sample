<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { computed } from 'vue';

const props = defineProps<{
  businessApplicationId: number;
  businessName: string;
  applicantName: string;
  applicantNameKana: string;
  zipCode: string;
  pref: string;
  city: string;
  email: string;
  phone: number;
}>();

const zipCodeFormat = computed(() => {
  return props.zipCode.slice(0, 3) + '-' + props.zipCode.slice(3);
});
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '登録情報',
      href: RouteName.CorporationBusinessOperatorManagementShow,
      params: { businessApplicationId: props.businessApplicationId },
    }"
  >
    <div class="p-corporation-business-management-info">
      <div class="p-corporation-business-management-info__wrapper">
        <div class="p-corporation-business-management-info__wrapper__container">
          <Paragraph class="p-corporation-business-management-info__wrapper__container__title">店舗名</Paragraph>
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value">{{
            props.businessName
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-info__wrapper__container">
          <Paragraph class="p-corporation-business-management-info__wrapper__container__title">担当者氏名</Paragraph>
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value">{{
            props.applicantName
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-info__wrapper__container">
          <Paragraph class="p-corporation-business-management-info__wrapper__container__title">
            担当者氏名（カナ）
          </Paragraph>
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value">{{
            props.applicantNameKana
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-info__wrapper__container">
          <Paragraph class="p-corporation-business-management-info__wrapper__container__title">住所</Paragraph>
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value"
            >〒{{ zipCodeFormat }}</Paragraph
          >
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value"
            >{{ props.pref }}{{ props.city }}</Paragraph
          >
          <Divider />
        </div>
        <div class="p-corporation-business-management-info__wrapper__container">
          <Paragraph class="p-corporation-business-management-info__wrapper__container__title">
            メールアドレス
          </Paragraph>
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value">{{
            props.email
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-info__wrapper__container">
          <Paragraph class="p-corporation-business-management-info__wrapper__container__title"> 電話番号 </Paragraph>
          <Paragraph class="p-corporation-business-management-info__wrapper__container__value">{{
            props.phone
          }}</Paragraph>
          <Divider />
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-business-management-info {
  padding: 20px 30px;
  &__info__annotation {
    font-size: 14px;
    font-weight: 500;
    letter-spacing: -0.015em;
  }
  &__wrapper {
    padding: 20px 0;
    &__container {
      padding: 10px 0;
      &__title {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: -0.015em;
      }
      &__value {
        padding: 5px 0;
        font-size: 16px;
        font-weight: 500;
        letter-spacing: -0.015em;
        line-height: 1;
      }
    }
  }
}
</style>
