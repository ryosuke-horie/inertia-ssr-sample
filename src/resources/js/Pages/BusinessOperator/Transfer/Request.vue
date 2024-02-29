<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout/AuthLayout.vue';
import { type AuthLayoutProps } from '@/Layouts/AuthLayout/type';
import { RouteName, RouteRoleName } from '@/Utilities';
import { ref } from 'vue';
import FormToggle from '@/Components/atom/form/FormToggle/FormToggle.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { BUSINESSOPERATORApi } from '@/api';
import { configuration } from '@/lib/configuration';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';

const authLayoutProps: AuthLayoutProps = {
  isAuthRoute: true,
  title: 'チアペイ',
  header: {
    role: RouteRoleName.Business,
    text: '振込申請',
    href: RouteName.BusinessOperatorTransferSelect,
  },
};

const isAutoApply = ref();

const onChange = async () => {
  console.log('kita');
};
</script>

<template>
  <AuthLayout v-bind="authLayoutProps">
    <div class="p-business-operator-transfer-request">
      <div class="p-business-operator-transfer-request__item">
        <div class="p-business-operator-transfer-request__item-title">自動振込</div>
        <FormToggle id="push-notification" v-model="isAutoApply" @change="onChange()" />
      </div>
      <Divider />
      <div class="p-business-operator-transfer-request__item">
        <div class="p-business-operator-transfer-request__item-title">振込金額設定</div>
      </div>
      <Divider />
      <Paragraph class="p-business-operator-transfer-request__annotation">
        自動振込は、月末締めの翌月15日に手数料を差し引いた金額が1000円以上の場合、登録口座へ自動で振り込まれます。15日が休日の場合は、翌営業日に振り込まれます。
      </Paragraph>
    </div>
  </AuthLayout>
</template>

<style lang="scss">
.p-business-operator-transfer-request {
  padding: 5px 0px;
  height: 100vh;
  &__item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;

    &-title {
      font-size: 18px;
      font-weight: var(--bold);
    }
  }
  &__annotation {
    font-size: 14px;
    font-weight: 500;
    line-height: 18px;
    letter-spacing: -0.015em;
  }
}
</style>
