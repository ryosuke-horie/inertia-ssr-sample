<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Link from '@/Components/atom/link/Link/Link.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import type { ProfileProps } from './type';
import DefinitionListItem from '@/Components/molecules/list/DefinitionListItem/DefinitionListItem.vue';

const props = defineProps<ProfileProps>();
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: 'プロフィール',
      href: RouteName.CorporationMypage,
    }"
  >
    <div class="p-profile">
      <div class="p-profile__form__container">
        <DefinitionListItem label="法人名" :content="props.corporationName" class="p-profile__definition-list-item" />
        <Divider class="p-profile__form__divider" />
      </div>
      <div class="p-profile__form__link-container">
        <DefinitionListItem label="メールアドレス" :content="props.email" class="p-profile__definition-list-item" />
        <Divider class="p-profile__form__divider" />
        <Link
          text="メールアドレスを変更する >"
          :href="RouteName.CorporationProfileChangeEmailShow"
          class="p-profile__form__change-link"
        />
      </div>
      <div class="p-profile__form__link-container">
        <DefinitionListItem label="パスワード" content="＊＊＊＊＊＊＊＊" class="p-profile__definition-list-item" />
        <Divider class="p-profile__form__divider" />
        <Link
          text="パスワードを変更する >"
          :href="RouteName.CorporationProfileChangePasswordShow"
          class="p-profile__form__change-link"
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-profile {
  padding: 0 20px;
  &__form {
    width: 90%;
    margin: 20px auto;
    &__container {
      margin-top: 20px;
      margin-bottom: 35px;
    }
    &__link-container {
      margin-top: 20px;
    }
    &__divider {
      border-bottom: 1px solid #000000;
    }
    &__change-link {
      width: 100%;
      text-align: right;
      margin-top: 5px;
    }
    &__submit {
      width: 100%;
      margin-top: 20px;
      font-size: 18px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
  }
}
</style>
