<script lang="ts" setup>
import { RouteName } from '@/Utilities';
import { Link } from '@inertiajs/vue3';
import Button from '@/Components/_lp/Button/Button.vue';
import { ButtonVariantProp } from '@/Components/_lp/Button/type';

const menuList = [
  // { text: 'ポイント', icon: 'fa-solid fa-chevron-right', href: RouteName.StaffPointHistory },
  // { text: 'プロフィール', icon: 'fa-solid fa-chevron-right', href: RouteName.StaffProfileShow },
  // { text: 'ギフト履歴', icon: 'fa-solid fa-chevron-right', href: RouteName.StaffTips },
  { text: '特定商取引法に基づく表記', icon: 'fa-solid fa-chevron-right', href: RouteName.Law },
  { text: '運営会社', icon: 'fa-solid fa-chevron-right', target: true, href: RouteName.Faq },
  { text: 'お問い合わせ', icon: 'fa-solid fa-chevron-right', target: true, href: RouteName.Inquiry },
];
</script>

<template>
  <div class="c-hamburger-menu-web">
    <Button
      class="c-hamburger-menu-web__login"
      :variant="ButtonVariantProp.Green"
      icon="fa-solid fa-right-to-bracket"
      text="ログイン"
      :href="route(RouteName.UserLoginMethod)"
    />

    <ul class="c-hamburger-menu-web__list">
      <li class="c-hamburger-menu-web__list-item">
        <i class="fa-solid fa-store"></i>
        <span>導入店舗</span>
      </li>
      <li class="c-hamburger-menu-web__list-item">
        <i class="fa-solid fa-question"></i>
        <span>チアペイの使い方</span>
      </li>
    </ul>

    <hr />

    <ul class="c-hamburger-menu-web__list">
      <li class="c-hamburger-menu-web__list-item">
        <i class="fa-solid fa-users"></i>
        <span>お店やスタッフを応援したい！</span>
      </li>
    </ul>

    <Button
      class="c-hamburger-menu-web__register"
      :variant="ButtonVariantProp.Blue"
      icon="fa-solid fa-right-to-bracket"
      text="新規ユーザー登録（無料）"
      :href="route(RouteName.UserRegister)"
    />

    <hr />

    <ul class="c-hamburger-menu-web__list">
      <li class="c-hamburger-menu-web__list-item">
        <i class="fa-solid fa-users"></i>
        <span>導入をお考えの法人・店舗様</span>
      </li>
    </ul>

    <Button
      class="c-hamburger-menu-web__register"
      :variant="ButtonVariantProp.Orange"
      icon="fa-solid fa-right-to-bracket"
      text="１店舗でのお申し込み"
      :href="route(RouteName.BusinessOperatorRegister)"
    />
    <Button
      class="c-hamburger-menu-web__register"
      :variant="ButtonVariantProp.Orange"
      icon="fa-solid fa-right-to-bracket"
      text="複数店舗のお申し込み"
      :href="route(RouteName.CorporationRegister)"
    />

    <ul class="c-menu-list">
      <li v-for="item of menuList" :key="item.text" class="c-menu-list__item">
        <Link class="c-menu-list__link" :href="route(item.href)">
          <span>{{ item.text }}</span>
          <i :class="item.icon"></i>
        </Link>
      </li>
    </ul>
  </div>
</template>

<style lang="scss">
.c-hamburger-menu-web {
  width: 100%;
  padding: 16px;
  background-color: var(--white);
  @media screen and (min-width: 1025px) {
    position: sticky;
    top: 0;
    height: auto;
  }
  &__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    &-logo {
      height: 34px;
      img {
        height: 100%;
      }
    }
    &-menu {
      @media screen and (min-width: 1025px) {
        display: none;
      }
    }
  }
  &__login,
  &__register {
    width: 100%;
    margin-bottom: 16px;
  }
  &__list {
    display: flex;
    flex-direction: column;
    padding: 16px;
    &-item {
      display: flex;
      align-items: center;
      i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        font-size: 20px;
        margin-right: 16px;
      }
      & + .c-hamburger__list-item {
        margin-top: 8px;
      }
      span {
        font-size: 18px;
        font-weight: var(--bold);
      }
    }
  }
  &__copy {
    margin-top: 12px;
    text-align: center;
  }
}

.c-menu-list {
  display: flex;
  flex-direction: column;
  &__item {
    border-bottom: solid 1px #333;
    &:first-of-type {
      border-top: solid 1px #333;
    }
  }
  &__link {
    display: flex;
    align-items: center;
    color: #333;
    padding: 12px 16px;
    i {
      font-size: 14px;
      margin-left: auto;
    }
    span {
      font-size: 14px;
      font-weight: var(--bold);
    }
  }
}
</style>
