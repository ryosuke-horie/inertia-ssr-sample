<script lang="ts" setup>
import { RouteName } from '@/Utilities';
import { router } from '@inertiajs/vue3';
import Button from '@/Components/atom/button/Button/Button.vue';
import HamburgerMenuListItem from '@/Components/molecules/list/HamburgerMenuListListItem/HamburgerMenuListItem.vue';

const menuList = [
  { text: 'ポイント', icon: 'fa-solid fa-coins', href: RouteName.UserPointHistory },
  { text: 'プロフィール', icon: 'fa-solid fa-user', href: RouteName.UserProfileShow },
  { text: '応援履歴', icon: 'fa-solid fa-bullhorn', href: RouteName.UserTips },
  { text: 'ランキング', icon: 'fa-solid fa-crown', href: RouteName.UserBusinessOperator },
  { text: 'ショップ一覧', icon: 'fa-solid fa-shop', href: RouteName.UserBusinessOperator },
  { text: 'ポイントチャージ', icon: 'fa-solid fa-coins', href: RouteName.UserPointCharge },
  { text: '支払い情報', icon: 'fa-solid fa-coins', href: RouteName.UserPaymentInfoShow },
  { text: '特定商取引法に基づく表記', icon: 'fa-solid fa-coins', href: RouteName.Law },
  { text: '運営会社', icon: 'fa-solid fa-coins', target: true, href: RouteName.Faq },
  { text: 'お問い合わせ', icon: 'fa-solid fa-comment', target: true, href: RouteName.UserInquiry },
];

const Logout = () => router.post(route(RouteName.UserLogout));
</script>

<template>
  <div class="c-hamburger-menu-staff">
    <HamburgerMenuListItem
      v-for="(item, index) of menuList"
      :key="index"
      :href="route(item.href)"
      :text="item.text"
      :icon="item.icon"
    />
    <div class="c-hamburger-menu-staff__logout">
      <Button class="c-hamburger-menu-staff__logout-button" text="ログアウト" @click="Logout" />
    </div>
  </div>
</template>

<style lang="scss">
.c-hamburger-menu-staff {
  &__logout {
    width: 100%;
    padding: 0 16px;
    margin-top: 16px;
    &-button {
      width: 100%;
    }
  }
}
</style>
