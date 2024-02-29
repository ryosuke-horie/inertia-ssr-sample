<script lang="ts" setup>
import { StripePaymentMethodProps } from './type';

const props = defineProps<StripePaymentMethodProps>();
</script>

<template>
  <div class="c-stripe-payment-method">
    <p>ブランド: {{ props.brand }}</p>
    <p>カード情報: **** **** **** {{ props.last4 }}</p>
    <p>有効期限: {{ props.expiry }}</p>
  </div>
</template>

<style lang="scss">
.c-stripe-payment-method {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 16px;
  background-color: #eeeeee;
  &--empty {
    align-items: center;
  }
}
</style>
