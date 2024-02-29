<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { RouteName, RouteRoleName } from '@/Utilities';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import PointChargeSubmitConfirmModal from '@/Components/organizms/point/PointChargeSubmitConfirmModal/PointChargeSubmitConfirmModal.vue';
import PointChargeCountSection from '@/Components/organizms/point/PointChargeCountSection/PointChargeCountSection.vue';
import StripePaymentMethod from '@/Components/organizms/stripe/StripePaymentMethod/StripePaymentMethod.vue';

const props = defineProps<{
  amount: number;
  freeAmount: number;
  stripeKey: string;
  paymentMethod: {
    brand: string;
    last4: string;
    expiry: string;
  };
}>();

const form = useForm<{ amount: number }>({ amount: props.amount });

const showModal = ref<boolean>(false);
const setShowModal = (val: boolean): void => {
  if (form.processing) return;
  showModal.value = val;
};

// フォームの送信を処理する関数
const handleSubmit = async () => {
  if (form.processing) return;
  form.post(route(RouteName.UserPointChargePaymentCompleteExistCard));
};
</script>

<template>
  <BaseLayout
    title="購入完了"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'お支払方法を選択',
      href: RouteName.UserPointCharge,
    }"
  >
    <div class="p-user-point-charge-payment-select">
      <PointChargeCountSection
        :amount="props.amount"
        :free-amount="props.freeAmount"
        :href="route(RouteName.UserPointCharge)"
      />
      <div class="p-user-point-charge-payment-select__form">
        <StripePaymentMethod v-bind="props.paymentMethod" />
        <Button @click="setShowModal(true)" class="p-user-point-charge-payment-select__form-submit" text="確認" />
      </div>
      <PointChargeSubmitConfirmModal
        v-if="showModal"
        :amount="props.amount"
        :free-amount="props.freeAmount"
        :is-submit-loading="form.processing"
        @submit="handleSubmit"
        @close="setShowModal(false)"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-point-charge-payment-select {
  &__error {
    padding: 10px;
  }
  &__form {
    padding: 16px;
    &-submit {
      width: 100%;
      margin-top: 16px;
    }
  }
}
</style>
