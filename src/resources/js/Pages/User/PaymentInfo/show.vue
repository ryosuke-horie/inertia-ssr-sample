<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { RouteRoleName, RouteName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import StripePaymentMethod from '@/Components/organizms/stripe/StripePaymentMethod/StripePaymentMethod.vue';

type PaymentMethod = {
  brand: string;
  last4: string;
  expiry: string;
};

const props = defineProps<{ paymentMethod: PaymentMethod | null }>();

const deleteform = useForm({});
const deletePaymentMethod = async (): Promise<void> => {
  if (deleteform.processing) return;
  deleteform.delete(route(RouteName.UserPaymentInfoDelete));
};
</script>

<template>
  <BaseLayout
    title="支払い情報詳細"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: '支払い情報詳細',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-payment-info">
      <div class="p-payment-info__detail">
        <template v-if="props.paymentMethod">
          <StripePaymentMethod v-bind="props.paymentMethod" />
          <Button
            class="p-payment-info__button"
            @click="deletePaymentMethod"
            :is-loading="deleteform.processing"
            text="カード情報削除"
          />
        </template>
        <template v-else>
          <div class="p-payment-info__card">
            <p>未登録</p>
            <p>カード情報が登録されていません</p>
          </div>
          <AnkerButton
            class="p-payment-info__button"
            text="カード登録"
            :href="route(RouteName.UserPaymentInfoCreate)"
          />
        </template>
      </div>
      <Paragraph class="p-payment-info__annotation">
        ※一度登録したカード情報を変更することはできません。カード情報の変更を行う場合は、該当のカード情報を一度削除し、あらためてカード情報をご登録ください。
      </Paragraph>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-payment-info {
  padding: 24px 16px;
  &__card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 16px;
    background-color: #eeeeee;
    align-items: center;
  }
  &__button {
    width: 100%;
    margin-top: 24px;
  }
  &__annotation {
    margin-top: 16px;
  }
}
</style>
