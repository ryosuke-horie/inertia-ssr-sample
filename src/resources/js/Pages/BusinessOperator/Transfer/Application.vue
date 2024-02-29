<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import FormToggle from '@/Components/atom/form/FormToggle/FormToggle.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import FormGroupInputNumber from '@/Components/molecules/form/FormGroupInputNumber/FormGroupInputNumber.vue';
import { useForm } from '@inertiajs/vue3';
import Button from '@/Components/atom/button/Button/Button.vue';

const props = defineProps<{
  settingId: number;
  isAutoApply: boolean;
  autoApplyAmount: number;
}>();

const form = useForm({
  isAutoApply: props.isAutoApply,
  autoApplyAmount: props.autoApplyAmount || 2000,
});

const onSubmit = (): void => {
  form.put(route(RouteName.BusinessOperatorTransferApplicationUpdate, { settingId: props.settingId }));
};
</script>

<template>
  <BaseLayout
    title="振込申請"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '振込申請',
      href: RouteName.BusinessOperatorTransferSelect,
    }"
  >
    <div class="p-business-operator-transfer-application">
      <form @submit.prevent="onSubmit" class="p-profile__form">
        <div class="p-business-operator-transfer-application__item">
          <div class="p-business-operator-transfer-application__item__title">自動振込</div>
          <FormToggle id="push-notification" v-model="form.isAutoApply" />
        </div>
        <Divider class="p-business-operator-transfer-application__divider" />
        <div class="p-business-operator-transfer-application__item">
          <div class="p-business-operator-transfer-application__item__title">振込金額設定</div>
          <span class="p-business-operator-transfer-application__item__mark">￥</span>
          <FormGroupInputNumber
            id="auto-apply-amount"
            class="p-business-operator-transfer-application__item__auto-apply-amount"
            v-model="form.autoApplyAmount"
            :error="form.errors.autoApplyAmount"
            :min="2000"
            :max="9999999"
          />
        </div>
        <Divider class="p-business-operator-transfer-application__divider" />
        <Paragraph class="p-business-operator-transfer-application__annotation">
          自動振込は、月末締めの翌月15日に手数料を差し引いた金額が1000円以上の場合、登録口座へ自動で振り込まれます。<br />15日が休日の場合は、翌営業日に振り込まれます。
        </Paragraph>
        <Button class="p-business-operator-transfer-application__button" text="更新" />
      </form>
    </div>
  </BaseLayout>
</template>
<style lang="scss">
.p-business-operator-transfer-application {
  padding: 5px 30px;
  height: 100vh;
  &__item {
    display: flex;
    align-items: center;
    height: 60px;
    &__title {
      font-size: 18px;
      margin-right: auto;
      font-weight: var(--bold);
    }
    &-mark {
      display: block;
    }
    &__auto-apply-amount {
      width: 120px;
      height: 40px;
      margin-left: 8px;
      background-color: #fff !important;
    }
  }
  &__divider {
    margin: 0px -30px;
  }
  &__annotation {
    font-size: 13px;
    font-weight: 500;
    line-height: 18px;
    letter-spacing: -0.015em;
    padding: 30px 0px;
  }
  &__button {
    width: 100%;
  }
}
</style>
