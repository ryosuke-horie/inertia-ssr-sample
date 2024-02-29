<script lang="ts" setup>
import { RouteName, RouteRoleName } from '@/Utilities';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
</script>

<template>
  <BaseLayout
    title="マイページ"
    :is-auth="true"
    :role="RouteRoleName.AdminStaff"
    :auth-header="{
      role: RouteRoleName.AdminStaff,
      text: 'マイページ',
    }"
  >
    <div class="p-admin-staff-mypage">
      <AnkerButton
        text="応援履歴"
        :href="route(RouteName.AdminStaffTips)"
        class="p-business-operator-staff-select-button"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-admin-staff-mypage {
  padding: 24px 16px;
}
</style>
