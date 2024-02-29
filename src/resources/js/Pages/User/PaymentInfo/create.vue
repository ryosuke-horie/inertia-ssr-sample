<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { RouteName, RouteRoleName } from '@/Utilities';
import { onMounted } from 'vue';
import { loadStripe, StripeElements, Stripe } from '@stripe/stripe-js';
import Button from '@/Components/atom/button/Button/Button.vue';
import { ref } from 'vue';

const props = defineProps<{ stripeKey: string }>();

const isLoading = ref<boolean>(true);
const stripe = ref<Stripe | null>(null);
const stripeElements = ref<StripeElements | null>(null);

const form = useForm<{ token: string; brand: string; last4: string; expMonth: number; expYear: number }>({
  token: '',
  brand: '',
  last4: '',
  expMonth: 0,
  expYear: 0,
});

onMounted(async (): Promise<void> => {
  stripe.value = await loadStripe(props.stripeKey);
  if (!stripe.value) return;
  stripeElements.value = stripe.value.elements();
  stripeElements.value.create('cardNumber', { placeholder: '0000 0000 0000 0000' }).mount('#card-number-element');
  stripeElements.value.create('cardExpiry').mount('#card-expiry-element');
  stripeElements.value.create('cardCvc', { placeholder: '000' }).mount('#card-cvc-element');
  isLoading.value = false;
});

const submit = async (): Promise<void> => {
  if (!stripe.value) {
    alert('予期せぬエラー');
    return;
  }

  if (!stripeElements.value) {
    alert('予期せぬエラー');
    return;
  }

  const cardNumber = stripeElements.value.getElement('cardNumber');
  const cardExpiry = stripeElements.value.getElement('cardExpiry');
  const cardCvc = stripeElements.value.getElement('cardCvc');

  if (!cardNumber || !cardExpiry || !cardCvc) return;

  const { token, error } = await stripe.value.createToken(cardNumber);

  // const { paymentMethod, error } = await stripe.value.createPaymentMethod({
  //   type: 'card',
  //   card: cardNumber,
  // });

  if (error) return;

  if (!token.card) return;

  form.token = token.id;
  form.brand = token.card.brand;
  form.last4 = token.card.last4;
  form.expMonth = token.card.exp_month;
  form.expYear = token.card.exp_year;

  form.post(route(RouteName.UserPaymentInfoConfirm));
};
</script>

<template>
  <BaseLayout
    title="支払い情報登録"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: '支払い情報登録',
      href: RouteName.UserPaymentInfoShow,
    }"
  >
    <form v-show="!isLoading" @submit.prevent="submit" class="p-stripe-info">
      <div class="p-stripe-info__number">
        <div class="p-stripe-info__label">カード番号</div>
        <div id="card-number-element" />
      </div>
      <div class="p-stripe-info__expiry">
        <div class="p-stripe-info__label">有効期限</div>
        <div id="card-expiry-element" />
      </div>
      <div class="p-stripe-info__cvc">
        <div class="p-stripe-info__label">セキュリティーコード</div>
        <div id="card-cvc-element" />
      </div>
      <Button class="p-stripe-info__submit" text="確認" :is-loading="form.processing" />
    </form>
  </BaseLayout>
</template>

<style lang="scss">
.p-stripe-info {
  display: flex;
  flex-direction: column;
  padding: 24px 16px;
  &__label {
    font-size: 16px;
    margin-bottom: 8px;
    font-weight: var(--bold);
  }
  &__number,
  &__expiry {
    margin-bottom: 16px;
  }
  &__submit {
    margin-top: 24px;
  }
}
</style>
