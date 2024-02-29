<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
</script>

<template>
  <BaseLayout
    title="スタッフ管理"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフ管理',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <AnkerButton
      text="スタッフ一覧"
      :href="route(RouteName.BusinessOperatorStaff)"
      class="p-business-operator-staff-select-button"
    />
    <AnkerButton
      text="スタッフスケジュール"
      :href="route(RouteName.BusinessOperatorStaffSchedules)"
      class="p-business-operator-staff-select-button"
    />
    <AnkerButton
      text="応援履歴"
      :href="route(RouteName.BusinessOperatorStaffTips)"
      class="p-business-operator-staff-select-button"
    />
  </BaseLayout>
</template>

<style lamg="scss">
.p-business-operator-staff-select-button {
  margin: 24px 16px;
}
</style>
