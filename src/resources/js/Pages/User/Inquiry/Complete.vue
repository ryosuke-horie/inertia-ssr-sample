<script lang="ts" setup>
import { RouteName, RouteRoleName } from '@/Utilities';
import { AnkerButtonVariantProp } from '@/Components/atom/button/AnkerButton/type';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
</script>

<template>
  <BaseLayout
    title="お問い合わせ完了"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'お問い合わせ完了',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-user-inquiry-complete">
      <h3>お問い合わせありがとうございました。</h3>
      <p class="p-user-inquiry-complete__text-first">確認のため、お客様に自動返信メールを送りしております。</p>
      <p class="p-user-inquiry-complete__text-second">後日担当者よりご連絡させていただきます 。</p>
      <AnkerButton
        class="p-user-inquiry-complete__fix-button"
        :variant="AnkerButtonVariantProp.Primary"
        :href="route(RouteName.UserMypage)"
        text="マイページへ"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-inquiry-complete {
  margin: 16px;
  &__text-first {
    margin-top: 18px;
  }
  &__text-second {
    margin-bottom: 28px;
  }
}
</style>
