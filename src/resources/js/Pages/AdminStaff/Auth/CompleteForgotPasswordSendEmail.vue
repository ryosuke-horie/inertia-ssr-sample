<script lang="ts" setup>
import { RouteName, RouteRoleName } from '@/Utilities';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
</script>

<template>
  <BaseLayout
    title="再設定メールを送信しました"
    :auth-header="{
      role: RouteRoleName.AdminStaff,
      text: '再設定メールを送信しました',
    }"
  >
    <div class="p-staff-complete-send-email">
      <div class="p-staff-complete-send-email__container">
        <Paragraph>
          ご登録されたメールアドレス宛にパスワード再設定メールを送信しました。<br />
          メールが届きましたら、60分いないにメッセージ記載されたURLからパスワードの再設定をおこなってください。
        </Paragraph>
        <AnkerButton class="p-staff-complete-send-email__submit" text="戻る" :href="route(RouteName.AdminStaffLogin)" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-staff-complete-send-email {
  &__container {
    padding: 24px 16px;
  }
  &__submit {
    width: 100%;
    margin-top: 25px;
  }
}
</style>
