<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName } from '@/Utilities';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
</script>

<template>
  <BaseLayout title="再設定メール送信">
    <HeaderSubText text="再設定メールを送信しました" />
    <Divider />
    <div class="p-business-operators-complete-send-email">
      <div class="p-business-operators-login__container">
        <Paragraph>
          ご登録されたメールアドレス宛にパスワード再設定メールを送信しました。<br />
          メールが届きましたら、60分いないにメッセージ記載されたURLからパスワードの再設定をおこなってください。
        </Paragraph>
        <AnkerButton
          class="p-business-operators-complete-send-email__submit"
          text="戻る"
          :href="route(RouteName.BusinessOperatorLogin)"
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operators-complete-send-email {
  &__container {
    padding: 24px 16px;
  }
  &__submit {
    width: 100%;
    margin-top: 25px;
  }
}
</style>
