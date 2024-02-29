<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import { RouteName } from '@/Utilities';
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import snsIcons from '@/../assets/images/icon/icon_sns.png';
import Image from '@/Components/atom/image/Image/Image.vue';
</script>

<template>
  <BaseLayout title="チアペイ無料会員登録">
    <HeaderSubText text="チアペイ無料会員登録" />
    <Divider />
    <div class="p-user-signup">
      <div class="p-user-signup__container">
        <div class="p-user-signup__link-icon-email">
          <i class="fa-regular fa-envelope"></i>
          <Link class="p-user-signup__register" text="メールアドレスで登録" :href="RouteName.UserRegister" />
        </div>

        <Divider class="p-user-signup__divider" />

        <a class="p-user-signup__link-icon-sns" href="/user/sns-login">
          <!-- Inertia.jsのLinkはヘッダーが除外されるため仮でaタグを使用 -->
          <Image :src="snsIcons" width="100%" height="80%" />
          <p>SNSアカウントで登録</p>
        </a>

        <div class="p-user-signup__link-container">
          <h3>アカウントお持ちの方</h3>
          <AnkerButton
            class="p-user-signup__link"
            text="ログイン"
            :is-outlined="true"
            :href="route(RouteName.UserLogin)"
          />
          <Paragraph>
            <Link text="利用規約" :href="RouteName.UserPasswordRequest" />
            及び
            <Link
              text="プライバシーポリシー"
              class="p-user-signup__privacy-policy"
              :href="RouteName.UserPasswordRequest"
            />
            に同意の上、登録又はログインへお進みください。
          </Paragraph>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-signup {
  &__container {
    padding: 24px 0px;
  }
  &__icon {
    width: 190px;
    height: 145px;
    margin: 0 auto;
    display: block;
  }
  &__link-icon-email {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 19px 0 36px 0;
    gap: 10px;
    & > i {
      font-size: 24px;
    }
    & > a {
      color: var(--black);
      font-weight: 700;
    }
  }
  &__link-icon-sns {
    display: flex;
    padding: 25px 0;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    font-weight: bold;
    color: var(--black);
  }
  &__link-container {
    background: var(--light-gray);
    padding: 20px;
    & > h3 {
      color: var(--black);
      text-align: center;
      margin-bottom: 10px;
      margin-top: 10px;
    }
  }
  &__divider {
    margin: 5px 0px 10px 0;
  }
  &__privacy-policy {
    margin-top: 20px;
  }
}
</style>
