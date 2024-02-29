<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { Link } from '@inertiajs/vue3';
import { AnkerButtonVariantProp } from '@/Components/atom/button/AnkerButton/type';
import snsIcons from '@/../assets/images/icon/icon_sns.png';
import Image from '@/Components/atom/image/Image/Image.vue';
</script>

<template>
  <BaseLayout title="チアペイへようこそ！">
    <div class="p-user-login-method">
      <div class="p-user-login-method__container">
        <h3>ログイン方法を選択してください</h3>

        <Divider class="p-user-login-method__divider" />

        <div class="p-user-login-method__login-btn-container">
          <Link class="p-user-login-method__member" :href="route(RouteName.UserLogin)">
            ログイン<br />アカウントをお持ちの方
          </Link>

          <AnkerButton
            class="p-user-login-method__guest"
            text="ゲストで応援する"
            :variant="AnkerButtonVariantProp.Secondary"
            :href="route(RouteName.GuestUserBusinessOperator)"
          />
        </div>
        <div class="p-user-login-method__border">
          <span class="p-user-login-method__border-line"></span>
          <span class="p-user-login-method__border-text">または</span>
          <span class="p-user-login-method__border-line"></span>
        </div>
        <!-- Auth0によるSNSログイン -->
        <a class="p-user-login-method__link-icon-sns" href="/user/sns-login">
          <!-- Inertia.jsのLinkはヘッダーが除外されるため仮でaタグを使用 -->
          <Image :src="snsIcons" width="100%" height="80%" />
          <p>SNSアカウントでログイン</p>
        </a>

        <Divider class="p-user-login-method__divider" />
        <h3>はじめてご利用される方</h3>
        <Divider class="p-user-login-method__divider" />

        <div class="p-user-login-method__link-container">
          <AnkerButton
            class="p-user-login-method__link"
            text="無料会員登録"
            :is-outlined="true"
            :href="route(RouteName.UserRegister)"
          />
          <Paragraph>
            <Link text="利用規約" :href="RouteName.UserPasswordRequest" />
            及び
            <Link text="プライバシーポリシー" :href="RouteName.UserPasswordRequest" />
            に同意の上、登録又はログインへお進みください。
          </Paragraph>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-login-method {
  &__container {
    padding: 24px 0px;
    & > h3 {
      text-align: center;
    }
  }
  &__member {
    display: block;
    cursor: pointer;
    box-sizing: border-box;
    line-height: 25px;
    border-radius: 6px;
    border: solid 1px;
    min-width: 120px;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    margin-bottom: 10px;
    background: var(--anker);
    color: var(--white);
    padding: 20px;
    font-size: 18px;
  }
  &__icon {
    margin: 0 auto;
    display: block;
  }
  &__link-icon-sns {
    color: var(--black);
    border: 1px solid #ccc;
    margin: 10px 20px;
    display: grid;
    justify-items: center;
    padding: 25px 0;
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
  }
  &__border {
    display: flex;
    align-items: center;
    margin: 20px;
    &-text {
      padding: 0 10px;
    }
    &-line {
      flex: 1;
      height: 1px;
      background: #ccc;
      margin: 0 10px;
    }
  }
  &__divider {
    margin: 20px 0;
  }
  &__login-btn-container {
    padding: 0 20px;
  }
  &__link-container {
    margin: 20px;
    & > a {
      margin-bottom: 10px;
    }
  }
}
</style>
