<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { useForm } from '@inertiajs/vue3';
import FormSelectBox from '@/Components/atom/form/FormSelectBox/FormSelectBox.vue';
import type { FormSelectBoxOptionItem } from '@/Components/atom/form/FormSelectBox/type';
import Button from '@/Components/atom/button/Button/Button.vue';

const props = defineProps<{
  bankName?: string;
  accountType?: number;
  branchName?: string;
  accountNumber?: number;
  accountHolderName?: string;
}>();

const accountTypeOtpions: FormSelectBoxOptionItem[] = [
  { value: '1', label: '普通' },
  { value: '2', label: '当座' },
];

const form = useForm({
  bankName: props.bankName || '',
  accountType: props.accountType || 1,
  branchName: props.branchName || '',
  accountNumber: props.accountNumber || '',
  accountHolderName: props.accountHolderName || '',
});

const onSubmit = () => {
  form.post(route(RouteName.CorporationTransferBankAccountUpdate));
};
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '口座情報',
      href: RouteName.CorporationTransferSelect,
    }"
  >
    <form @submit.prevent="onSubmit" class="p-business-operator-transfer-bank-account">
      <div class="p-business-operator-transfer-bank-account__item">
        <div class="p-business-operator-transfer-bank-account__item__title">銀行</div>
        <FormGroupInput
          id="bank-name"
          v-model="form.bankName"
          :error="form.errors.bankName"
          placeholder="〇〇銀行"
          class="p-business-operator-transfer-bank-account__item__input"
        />
      </div>
      <Divider class="p-business-operator-transfer-bank-account__divider" />
      <div class="p-business-operator-transfer-bank-account__item">
        <div class="p-business-operator-transfer-bank-account__item__title">口座種別</div>
        <FormSelectBox
          id="bank-name"
          v-model="form.accountType"
          :options="accountTypeOtpions"
          :error="form.errors.accountType"
          class="p-business-operator-transfer-bank-account__item__input"
        />
      </div>
      <Divider class="p-business-operator-transfer-bank-account__divider" />
      <div class="p-business-operator-transfer-bank-account__item">
        <div class="p-business-operator-transfer-bank-account__item__title">支店名</div>
        <FormGroupInput
          id="branch-name"
          v-model="form.branchName"
          :error="form.errors.branchName"
          placeholder="〇〇支店"
          class="p-business-operator-transfer-bank-account__item__input"
        />
      </div>
      <Divider class="p-business-operator-transfer-bank-account__divider" />
      <div class="p-business-operator-transfer-bank-account__item">
        <div class="p-business-operator-transfer-bank-account__item__title">口座番号</div>
        <FormGroupInput
          id="account-number"
          v-model="form.accountNumber"
          placeholder="1234567(7桁)"
          :error="form.errors.accountNumber"
          class="p-business-operator-transfer-bank-account__item__input"
        />
      </div>
      <Divider class="p-business-operator-transfer-bank-account__divider" />
      <div class="p-business-operator-transfer-bank-account__item">
        <div class="p-business-operator-transfer-bank-account__item__title">口座名義(カナ)</div>
        <FormGroupInput
          id="account-holder-name"
          v-model="form.accountHolderName"
          :error="form.errors.accountHolderName"
          placeholder="ヤマダ タロウ"
          class="p-business-operator-transfer-bank-account__item__input"
        />
      </div>
      <Divider class="p-business-operator-transfer-bank-account__divider" />
      <Button class="p-business-operator-transfer-bank-account__button" text="更新" />
    </form>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-transfer-bank-account {
  padding: 5px 15px;
  height: 100vh;
  font-size: 16px;
  width: 100%;
  &__item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0px;
    &__title {
      font-weight: var(--bold);
    }
    &__input {
      width: 170px;
    }
  }
  &__divider {
    margin: 0px -15px;
  }
  &__button {
    width: 100%;
    margin-top: 30px;
  }
}
</style>
