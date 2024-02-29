<script lang="ts" setup>
import { RouteName } from '@/Utilities';
import { router } from '@inertiajs/vue3';
import Button from '@/Components/atom/button/Button/Button.vue';
import HamburgerMenuListItem from '@/Components/molecules/list/HamburgerMenuListListItem/HamburgerMenuListItem.vue';

const menuList = [
  { text: 'ポイント', icon: 'fa-solid fa-coins', href: RouteName.CorporationPoint },
  { text: 'プロフィール', icon: 'fa-solid fa-user', href: RouteName.CorporationProfileEdit },
  { text: 'ショップ', icon: 'fa-solid fa-bullhorn', href: RouteName.CorporationBusinessOperator },
  { text: '投稿メディア', icon: 'fa-solid fa-coins', href: RouteName.CorporationMediaCheck },
  { text: '入金履歴', icon: 'fa-solid fa-coins', href: RouteName.CorporationDepositDetails },
  { text: '各種設定', icon: 'fa-solid fa-coins', href: RouteName.CorporationSetting },
  { text: '特定商取引法に基づく表記', icon: 'fa-solid fa-coins', href: RouteName.Law },
  { text: '運営会社', icon: 'fa-solid fa-coins', target: true, href: RouteName.Faq },
  { text: 'お問い合わせ', icon: 'fa-solid fa-comment', target: true, href: RouteName.CorporationInquiry },
];

const Logout = () => router.post(route(RouteName.CorporationLogout));
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
