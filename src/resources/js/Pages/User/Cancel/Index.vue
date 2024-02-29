<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { AnkerButtonVariantProp } from '@/Components/atom/button/AnkerButton/type';
import { ParagraphVariantProp } from '@/Components/atom/typograph/Paragraph/type';
</script>

<template>
  <BaseLayout
    title="退会"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      role: RouteRoleName.User,
      text: '退会',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-user-cancel">
      <div class="p-user-cancel__caution">
        <Paragraph :class="ParagraphVariantProp.Red" class="p-user-cancel__caution__title"
          >すべてのデータが失われます</Paragraph
        >
        <Paragraph class="p-user-cancel__caution__content">
          チアペイ会員を退会されますと会員情報や保有されているチアポイントをはじめ、お気に入り登録やメッセージ履歴など、お客様のすべてのデータが削除され復旧されません。<br />
          <br />
          退会手続きを進めてもよろしいでしょうか。
        </Paragraph>
      </div>
      <AnkerButton
        :href="route(RouteName.UserCancelConfirm)"
        :variant="AnkerButtonVariantProp.Derk"
        text="退会手続きを進める"
        :is-outlined="false"
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
      color: #cc0000;
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
  }
}
</style>
