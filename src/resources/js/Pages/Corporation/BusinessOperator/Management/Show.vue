<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  businessApplicationId: number;
  businessName: string;
  applicationStatus: number;
  businessId: number | null;
}>();

const getApplicationStatusName = (applicationStatus: number) => {
  switch (applicationStatus) {
    case 1:
      return '審査中';
    case 2:
      return '承認されました';
    case 3:
      return '否認されました';
  }
};
</script>

<template>
  <BaseLayout
    title="店舗管理"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: props.businessName,
      href: RouteName.CorporationBusinessOperatorManagement,
    }"
  >
    <div class="p-corporation-business-management-show">
      <div class="p-corporation-business-management-show__container">
        <Paragraph class="p-corporation-business-management-show__container__left">申請状況</Paragraph>
        <Paragraph
          class="p-corporation-business-management-show__container__status"
          :class="{
            'p-corporation-business-management-show__container__status-green': props.applicationStatus == 2,
            'p-corporation-business-management-show__container__status-red':
              props.applicationStatus == 1 || props.applicationStatus == 3,
          }"
          >{{ getApplicationStatusName(props.applicationStatus) }}</Paragraph
        >
      </div>
      <Divider />
      <Link
        :href="
          route(RouteName.CorporationBusinessOperatorManagementInfo, {
            businessApplicationId: props.businessApplicationId,
          })
        "
        class="p-corporation-business-management-show__container"
      >
        <Paragraph class="p-corporation-business-management-show__container__left">登録情報</Paragraph>
        <Paragraph class="p-corporation-business-management-show__container__arrow">></Paragraph>
      </Link>
      <Divider />
      <Link
        v-if="props.applicationStatus == 2"
        :href="
          route(RouteName.CorporationBusinessOperatorManagementPublish, {
            businessApplicationId: props.businessApplicationId,
            businessId: props.businessId,
          })
        "
        class="p-corporation-business-management-show__container"
      >
        <Paragraph class="p-corporation-business-management-show__container__left">公開設定</Paragraph>
        <Paragraph class="p-corporation-business-management-show__container__arrow">></Paragraph>
      </Link>
      <Divider />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-business-management-show {
  &__container {
    display: flex;
    justify-content: space-between;
    padding: 15px 20px;
    &__status {
      font-size: 16px;
      font-weight: 500;
      letter-spacing: -0.015em;
      &-green {
        color: #06c755 !important;
      }
      &-red {
        color: #ff0707 !important;
      }
    }
    &__arrow {
      font-size: 16px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
    &__left {
      font-size: 16px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
  }
}
</style>
