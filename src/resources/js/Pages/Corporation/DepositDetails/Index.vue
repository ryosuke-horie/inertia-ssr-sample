<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  list: {
    requestId: number;
    transferAmount: number;
    transactionPeriodFrom: string;
    transactionPeriodTo: string;
  }[];
}>();
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '売上入金明細',
      href: RouteName.CorporationMypage,
    }"
  >
    <div v-for="(item, index) in props.list" :key="index" class="p-business-operator-deposit-details">
      <Link
        :href="route(RouteName.CorporationDepositDetailsBusiness, { requestId: item.requestId })"
        class="p-business-operator-deposit-details__container"
      >
        <div class="p-business-operator-deposit-details__container__detail">
          <Paragraph>
            {{ item.transactionPeriodFrom }}
            <span v-if="item.transactionPeriodTo">～<br />{{ item.transactionPeriodTo }}</span>
          </Paragraph>
          <Paragraph>{{ item.transferAmount }}円</Paragraph>
        </div>
        <Divider class="p-business-operator-deposit-details__container__divider" />
      </Link>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-deposit-details {
  &__container {
    &__detail {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      font-size: 18px;
      font-weight: 500;
      letter-spacing: -0.015em;
    }
  }
}
</style>
