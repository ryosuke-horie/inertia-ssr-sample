<script lang="ts" setup>
import { RouteName } from '@/Utilities';
import { AnkerButtonVariantProp } from '@/Components/atom/button/AnkerButton/type';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
</script>

<template>
  <BaseLayout
    title="お問い合わせ完了"
    :is-auth="false"
    :auth-header="{
      text: 'お問い合わせ完了',
    }"
  >
    <div class="p-inquiry-complete">
      <h3>お問い合わせありがとうございました。</h3>
      <p class="p-inquiry-complete__text-first">確認のため、お客様に自動返信メールを送りしております。</p>
      <p class="p-inquiry-complete__text-second">後日担当者よりご連絡させていただきます 。</p>
      <AnkerButton
        class="p-inquiry-complete__fix-button"
        :variant="AnkerButtonVariantProp.Primary"
        :href="route(RouteName.Welcome)"
        text="トップページへ"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-inquiry-complete {
  margin: 16px;
  &__text-first {
    margin-top: 18px;
  }
  &__text-second {
    margin-bottom: 28px;
  }
}
</style>
