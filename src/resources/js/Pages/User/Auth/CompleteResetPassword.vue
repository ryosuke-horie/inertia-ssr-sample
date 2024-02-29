<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
</script>

<template>
  <BaseLayout
    title="パスワード再設定完了"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'パスワード再設定完了',
    }"
  >
    <div class="p-user-complete-send-email">
      <div class="p-user-complete-send-email__container">
        <Paragraph>
          パスワードの再設定が完了しました。<br />
          新しいパスワードでログインいただき、チアペイをお楽しみください。
        </Paragraph>
        <AnkerButton class="p-user-complete-send-email__submit" text="ログイン" :href="route(RouteName.UserLogin)" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-complete-send-email {
  &__container {
    padding: 24px 16px;
  }
  &__submit {
    width: 100%;
    margin-top: 25px;
  }
}
</style>
