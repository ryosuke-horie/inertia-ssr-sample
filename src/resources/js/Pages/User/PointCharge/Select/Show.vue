<script setup lang="ts">
// Vue関連のインポート
import { ref, onMounted } from 'vue';
// Stripe関連のインポート
import { Stripe, loadStripe, StripeElements } from '@stripe/stripe-js';
import { router } from '@inertiajs/vue3';
// API関連インポート
import { GUESTApi } from '@/api';
import { configuration } from '@/lib/configuration';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Button from '@/Components/atom/button/Button/Button.vue';
import Alert from '@/Components/atom/alert/Alert.vue';
import PointChargeSubmitConfirmModal from '@/Components/organizms/point/PointChargeSubmitConfirmModal/PointChargeSubmitConfirmModal.vue';
import PointChargeCountSection from '@/Components/organizms/point/PointChargeCountSection/PointChargeCountSection.vue';
// 組織的コンポーネント

const props = defineProps<{ amount: number; freeAmount: number; stripeKey: string }>();

const stripe = ref<Stripe | null>(null); // Stripeインスタンスを保持するリアクティブな変数
const elements = ref<StripeElements | null>(null); // Stripe Elementsインスタンスを保持するリアクティブな変数
const errorMessage = ref<string | null>(null);
const setErrorMessage = (
  message = '「お支払い」に失敗しました。\nネットワーク接続を確認してください。\n引き続き問題が解決しない場合は、カスタマーサポートまでご連絡ください。',
): void => {
  errorMessage.value = message;
};

const isShowForm = ref<boolean>(false);
const isSubmitLoading = ref<boolean>(false);
const showModal = ref<boolean>(false);
const setShowModal = (val: boolean): void => {
  showModal.value = val;
};

// コンポーネントがマウントされた時に実行される
// 顧客情報があればその情報を表示する（未実装）
onMounted(async () => {
  stripe.value = await loadStripe(props.stripeKey);
  if (!stripe.value) return;
  // paymentIntentのクライアントで利用するシークレットキーを取得
  // 支払い金額は1つ前のページから取得する
  const paymentIntent = await new GUESTApi(configuration)
    .postUserPaymentInfoCreatePaymentIntent({ amount: props.amount })
    .catch(() => {
      isShowForm.value = false;
      setErrorMessage();
    });

  if (!paymentIntent) return;
  isShowForm.value = true;
  const clientSecret = paymentIntent.data.clientSecret;
  elements.value = stripe.value.elements({ clientSecret });
  elements.value
    .create('payment', { paymentMethodOrder: ['card', 'apple_pay', 'google_pay'] })
    .mount('#payment-element');
});

// フォームの送信を処理する関数
const handleSubmit = async () => {
  if (isSubmitLoading.value) return;
  isSubmitLoading.value = true;
  if (!stripe.value || !elements.value) {
    setErrorMessage();
    setShowModal(false);
    isSubmitLoading.value = false;
    return;
  }

  // 支払いを確定する
  // 3Dセキュア認証が必要な場合はreturn_urlを指定する（今は必要なし）
  const result = await stripe.value.confirmPayment({
    elements: elements.value,
    redirect: 'if_required',
    confirmParams: {},
  });

  if (result.error) {
    setErrorMessage(result.error.message);
    setShowModal(false);
    isSubmitLoading.value = false;
    return;
  }

  router.post(route(RouteName.UserPointChargePaymentComplete));
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
      <div v-if="errorMessage" class="p-user-point-charge-payment-select__error">
        <Alert :text="errorMessage" />
      </div>
      <PointChargeCountSection v-if="isShowForm" :amount="props.amount" :free-amount="props.freeAmount" />
      <div class="p-user-point-charge-payment-select__form">
        <div id="payment-element" />
        <Button
          v-if="isShowForm"
          @click="setShowModal(true)"
          class="p-user-point-charge-payment-select__form-submit"
          text="確認"
        />
      </div>
      <PointChargeSubmitConfirmModal
        v-if="showModal"
        :amount="props.amount"
        :free-amount="props.freeAmount"
        :is-submit-loading="isSubmitLoading"
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
