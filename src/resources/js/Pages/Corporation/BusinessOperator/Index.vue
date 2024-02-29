<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
</script>

<template>
  <BaseLayout
    title="ショップ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: 'ショップ',
    }"
  >
    <div class="p-corporation-business">
      <AnkerButton
        text="店舗管理"
        :href="route(RouteName.CorporationBusinessOperatorManagement)"
        class="p-corporation-business__button"
      />
      <AnkerButton
        text="店舗操作"
        :href="route(RouteName.BusinessOperatorTransferBankAccount)"
        class="p-corporation-business__button"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-business {
  padding: 25px;
  &__button {
    margin-bottom: 25px;
  }
}
</style>
