<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { AnkerButtonVariantProp } from '@/Components/atom/button/AnkerButton/type';
import { ParagraphVariantProp } from '@/Components/atom/typograph/Paragraph/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({});

const deleteUser = () => {
  form.delete(route(RouteName.UserCancelDelete));
};
</script>

<template>
  <BaseLayout
    title="退会手続き"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      role: RouteRoleName.User,
      text: '退会手続き',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-user-cancel">
      <div class="p-user-cancel__caution">
        <Paragraph :class="ParagraphVariantProp.Red" class="p-user-cancel__caution__title"
          >本当に退会しますか？</Paragraph
        >
        <Paragraph class="p-user-cancel__caution__content">
          「退会する」を押すと退会手続きを実行します。退会後は、お客様のすべてのデータを削除します。
        </Paragraph>
      </div>
      <Button
        :variant="AnkerButtonVariantProp.Derk"
        text="退会する"
        :is-outlined="false"
        @click="deleteUser"
        class="p-user-cancel__next-button"
      />
      <AnkerButton
        :href="route(RouteName.UserMypage)"
        :variant="AnkerButtonVariantProp.Primary"
        text="退会しない"
        :is-outlined="true"
        class="p-user-cancel__cancel-button"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-cancel {
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
    margin-bottom: 20px;
    width: 100%;
  }
}
</style>
