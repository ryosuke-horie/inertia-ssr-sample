<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({});

const deleteBusinessOperator = () => {
  form.delete(route(RouteName.BusinessOperatorCancelDelete));
};
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '退会手続き',
    }"
  >
    <div class="p-business-operator-cancel">
      <div class="p-business-operator-cancel__caution">
        <Paragraph class="p-business-operator-cancel__caution__title">本当に退会しますか？</Paragraph>
        <Paragraph class="p-business-operator-cancel__caution__content">
          「退会する」を押すと退会手続きを実行します。退会後は、お客様のすべてのデータを削除します。
        </Paragraph>
      </div>
      <Button
        variant="secondary"
        text="退会する"
        :is-outlined="false"
        @click="deleteBusinessOperator"
        class="p-business-operator-cancel__next-button"
      />
      <AnkerButton
        :href="route(RouteName.BusinessOperatorMypage)"
        variant="primary"
        text="退会しない"
        :is-outlined="true"
        class="p-business-operator-cancel__cancel-button"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-cancel {
  padding: 30px;
  &__caution {
    background-color: #ffe3e3;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 40px;
    &__title {
      font-size: 18px;
      font-weight: 700;
      letter-spacing: -0.015em;
      color: #cc0000 !important;
      text-align: center;
      padding-bottom: 30px;
    }
    &__content {
      font-size: 16px;
      font-weight: 400;
      letter-spacing: -0.015em;
    }
  }
  &__next-button {
    background-color: #333333 !important;
    margin-bottom: 20px;
    width: 100%;
  }
  &__cancel-button {
    border-color: #1d9bf0 !important;
    color: #1d9bf0 !important;
  }
}
</style>
