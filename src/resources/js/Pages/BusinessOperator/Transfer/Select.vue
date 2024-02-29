<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
</script>

<template>
  <BaseLayout
    title="振込情報"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '振込情報',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <AnkerButton
      text="自動振込申請"
      :href="route(RouteName.BusinessOperatorTransferApplication)"
      class="p-business-operator-staff-select-button"
    />
    <AnkerButton
      text="振込口座"
      :href="route(RouteName.BusinessOperatorTransferBankAccount)"
      class="p-business-operator-staff-select-button"
    />
    <AnkerButton
      text="支払通知書"
      :href="route(RouteName.BusinessOperatorTransferPaymentAdvice)"
      class="p-business-operator-staff-select-button"
    />
  </BaseLayout>
</template>

<style lamg="scss">
.p-business-operator-staff-select-button {
  margin: 24px 16px;
}
</style>
