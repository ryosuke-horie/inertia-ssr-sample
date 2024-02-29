<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { RouteName } from '@/Utilities';
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
</script>

<template>
  <BaseLayout title="メールアドレス変更完了">
    <HeaderSubText text="メールアドレス変更完了" />
    <Divider />
    <div class="p-user-login">
      <Paragraph>新しいメールアドレスの設定が完了しました。<br />引き続きチアペイをお楽しみください。</Paragraph>
      <AnkerButton class="p-user-login__button" :href="route(RouteName.CorporationLogin)" text="マイページ" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-login {
  padding: 24px 16px;
  &__button {
    margin-top: 32px;
  }
}
</style>
