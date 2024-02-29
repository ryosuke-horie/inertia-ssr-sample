<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
</script>

<template>
  <BaseLayout
    title="メールアドレス変更完了"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'メールアドレス変更完了',
    }"
  >
    <div class="p-user-login">
      <Paragraph>新しいメールアドレスの設定が完了しました。<br />引き続きチアペイをお楽しみください。</Paragraph>
      <AnkerButton class="p-user-login__button" :href="route(RouteName.StaffLogin)" text="ログイン" />
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
