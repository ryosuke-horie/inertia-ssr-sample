<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { useForm } from '@inertiajs/vue3';
import Button from '@/Components/atom/button/Button/Button.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { computed } from 'vue';

const props = defineProps<{
  businessName: string;
  applicantName: string;
  applicantNameKana: string;
  zipCode: string;
  prefCode: number;
  pref: string;
  city: string;
  streetAddress: string;
  building: string;
  email: string;
  password: string;
  passwordConfirmation: string;
  phone: number;
}>();

const form = useForm({
  businessName: props.businessName,
  applicantName: props.applicantName,
  applicantNameKana: props.applicantNameKana,
  zipCode: props.zipCode,
  prefCode: props.prefCode,
  city: props.city,
  streetAddress: props.streetAddress,
  building: props.building,
  email: props.email,
  password: props.password,
  password_confirmation: props.passwordConfirmation,
  phone: props.phone,
});

const onSubmit = () => {
  form.post(route(RouteName.CorporationBusinessOperatorManagementStore));
};

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
      text: '申込内容確認',
    }"
  >
    <form class="p-corporation-business-management-confirm" @submit.prevent="onSubmit">
      <Paragraph class="p-corporation-business-management-confirm__annotation">登録内容を確認してください。</Paragraph>
      <div class="p-corporation-business-management-confirm__wrapper">
        <div class="p-corporation-business-management-confirm__wrapper__container">
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__title">店舗名</Paragraph>
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value">{{
            props.businessName
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-confirm__wrapper__container">
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__title">担当者氏名</Paragraph>
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value">{{
            props.applicantName
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-confirm__wrapper__container">
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__title">
            担当者氏名（カナ）
          </Paragraph>
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value">{{
            props.applicantNameKana
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-confirm__wrapper__container">
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__title">住所</Paragraph>
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value"
            >〒{{ zipCodeFormat }}</Paragraph
          >
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value"
            >{{ props.pref }}{{ props.city }}</Paragraph
          >
          <Divider />
        </div>
        <div class="p-corporation-business-management-confirm__wrapper__container">
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__title">
            メールアドレス
          </Paragraph>
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value">{{
            props.email
          }}</Paragraph>
          <Divider />
        </div>
        <div class="p-corporation-business-management-confirm__wrapper__container">
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__title"> 電話番号 </Paragraph>
          <Paragraph class="p-corporation-business-management-confirm__wrapper__container__value">{{
            props.phone
          }}</Paragraph>
          <Divider />
        </div>
      </div>
      <Button text="この内容で申込する" class="p-corporation-business-management-confirm__submit" />
      <AnkerButton
        variant="secondary"
        :href="route(RouteName.CorporationBusinessOperatorManagementCreate)"
        text="修正する"
        class="p-corporation-business-management-confirm__prev"
      />
    </form>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-business-management-confirm {
  padding: 20px 30px;
  &__annotation {
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
  &__submit {
    width: 100%;
    margin-bottom: 20px;
  }
}
</style>
