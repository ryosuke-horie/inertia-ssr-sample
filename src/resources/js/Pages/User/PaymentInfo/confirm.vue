<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteRoleName } from '@/Utilities';
import { RouteName } from '@/Utilities';
import { useForm } from '@inertiajs/vue3';
import Button from '@/Components/atom/button/Button/Button.vue';

const props = defineProps<{
  stripeKey: string;
  token: string;
  brand: string;
  last4: string;
  expiry: string;
  cvc: string;
}>();

const form = useForm<{ token: string }>({ token: props.token });

const submit = async () => {
  form.post(route(RouteName.UserPaymentInfoRegister, { token: props.token }));
};
</script>

<template>
  <BaseLayout
    title="支払い情報確認"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: '支払い情報確認',
      href: RouteName.UserPaymentInfoShow,
    }"
  >
    <div class="p-stripe-info">
      <div class="p-stripe-info__item">
        <div class="p-stripe-info__label">カード番号</div>
        <div class="p-stripe-info__detail">{{ props.brand }} **** **** **** {{ props.last4 }}</div>
      </div>
      <div class="p-stripe-info__item">
        <div class="p-stripe-info__label">有効期限</div>
        <div class="p-stripe-info__detail">{{ props.expiry }}</div>
      </div>
      <div class="p-stripe-info__item">
        <div class="p-stripe-info__label">セキュリティーコード</div>
        <div class="p-stripe-info__detail">***</div>
      </div>
      <Button class="p-stripe-info__submit" @click="submit" text="登録" :is-loading="form.processing" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-stripe-info {
  display: flex;
  flex-direction: column;
  padding: 24px 16px;
  &__item {
    border-bottom: solid 1px #ddd;
    padding-bottom: 16px;
    margin-bottom: 16px;
    &:last-of-type {
      margin-bottom: 0;
    }
  }
  &__label {
    font-size: 16px;
    margin-bottom: 8px;
    font-weight: var(--bold);
  }
  &__detail {
    font-size: 16px;
  }
  &__submit {
    margin-top: 24px;
  }
}
</style>
