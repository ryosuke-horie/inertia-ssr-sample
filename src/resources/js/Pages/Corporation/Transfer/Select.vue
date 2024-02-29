<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '振込情報',
      href: RouteName.CorporationMypage,
    }"
  >
    <div class="p-corporation-transfer">
      <AnkerButton
        text="自動振込申請"
        :href="route(RouteName.CorporationTransferApplication)"
        class="p-corporation-transfer__button"
      ></AnkerButton>
      <AnkerButton
        text="振込口座"
        :href="route(RouteName.CorporationTransferBankAccount)"
        class="p-corporation-transfer__button"
      ></AnkerButton>
      <AnkerButton
        text="支払通知書"
        :href="route(RouteName.CorporationTransferPaymentAdvice)"
        class="p-corporation-transfer__button"
      ></AnkerButton>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-transfer {
  padding: 25px;
  &__button {
    margin-bottom: 25px;
  }
}
</style>
