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
// 型定義
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Button from '@/Components/atom/button/Button/Button.vue';
import Alert from '@/Components/atom/alert/Alert.vue';
import PointChargeGuestSubmitConfirmModal from '@/Components/organizms/point/PointChargeGuestSubmitConfirmModal/PointChargeGuestSubmitConfirmModal.vue';
import PointChargeAgeConfirmModal from '@/Components/organizms/point/PointChargeAgeConfirmModal/PointChargeAgeConfirmModal.vue';
import PointChargeCountSection from '@/Components/organizms/point/PointChargeCountSection/PointChargeCountSection.vue';
const props = defineProps<{
  stripeKey: string;
  businessId: number;
  staffId: number;
  amount: number;
  freeAmount: number;
  staffName: string;
  message: string;
  guestNickname: string;
}>();

const stripe = ref<Stripe | null>(null); // Stripeインスタンスを保持するリアクティブな変数
const elements = ref<StripeElements | null>(null); // Stripe Elementsインスタンスを保持するリアクティブな変数
const errorMessage = ref<string | null>(null);
const setErrorMessage = (): void => {
  errorMessage.value =
    '「お支払い」に失敗しました。\nネットワーク接続を確認してください。\n引き続き問題が解決しない場合は、カスタマーサポートまでご連絡ください。';
};

const isShowForm = ref<boolean>(false);
const isSubmitLoading = ref<boolean>(false);
const showModal = ref<boolean>(false);
const setShowModal = (val: boolean): void => {
  showModal.value = val;
};

const showAgeModal = ref(true);
const setShowAgeModal = (val: boolean): void => {
  showAgeModal.value = val;
};

// コンポーネントがマウントされた時に実行される
// 顧客情報があればその情報を表示する（未実装）
onMounted(async () => {
  stripe.value = await loadStripe(props.stripeKey);

  if (!stripe.value) return;
  // マウントされた時にStripe Elementsインスタンスを作成する
  // 支払い金額は1つ前のページから取得する
  const paymentIntent = await new GUESTApi(configuration)
    .postUserGuestBusinessOperatorBusinessIdStaffStaffIdCreatePaymentIntent(props.businessId, props.staffId, {
      amount: props.amount,
      guestNickname: props.guestNickname,
      message: props.message,
    })
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

  // businessIdとstaffIdに紐づくスタッフが存在するかチェック
  const exists = await new GUESTApi(configuration)
    .getUserGuestBusinessOperatorBusinessIdStaffStaffIdExists(props.businessId, props.staffId)
    .catch(() => {
      setErrorMessage();
      setShowModal(false);
      isSubmitLoading.value = false;
    });

  if (!exists) return;

  // 支払いを確定する
  // 3Dセキュア認証が必要な場合はreturn_urlを指定する（今は必要なし）
  const result = await stripe.value.confirmPayment({
    elements: elements.value,
    redirect: 'if_required',
    confirmParams: {},
  });

  if (result.error) {
    setShowModal(false);
    isSubmitLoading.value = false;
    return;
  }

  const paymentIntentId = result.paymentIntent.id;
  router.post(
    route(RouteName.GuestUserBusinessOperatorStaffUserTipPaymentComplete, {
      businessId: props.businessId,
      staffId: props.staffId,
    }),
    {
      paymentIntentId,
      guestNickname: props.guestNickname,
      message: props.message,
    },
  );
};

const onPageReturn = () => {
  router.visit(
    route(RouteName.GuestUserBusinessOperatorStaffShow, {
      businessId: props.businessId,
      staffId: props.staffId,
      message: props.message,
      guestNickname: props.guestNickname,
    }),
  );
};
</script>

<template>
  <BaseLayout
    title="お支払方法を選択"
    :is-auth="false"
    :role="RouteRoleName.GuestUser"
    :auth-header="{
      text: 'お支払方法を選択',
      href: RouteName.GuestUserBusinessOperatorStaffShow,
      params: {
        businessId: props.businessId,
        staffId: props.staffId,
        message: props.message,
        guestNickname: props.guestNickname,
      },
    }"
  >
    <div class="p-user-point-charge-payment-select">
      <div v-if="errorMessage" class="p-user-point-charge-payment-select__error">
        <Alert :text="errorMessage" />
      </div>
      <PointChargeCountSection
        v-if="isShowForm"
        :amount="props.amount"
        :free-amount="props.freeAmount"
        @click="onPageReturn"
      />
      <div class="p-user-point-charge-payment-select__form">
        <div id="payment-element" />
        <Button
          v-if="isShowForm"
          @click="setShowModal(true)"
          class="p-user-point-charge-payment-select__form-submit"
          text="確認"
        />
        <Button
          @click="onPageReturn"
          :variant="ButtonVariantProp.Secondary"
          class="p-user-point-charge-payment-select__form-return"
          text="内容を修正する"
        />
      </div>
      <PointChargeGuestSubmitConfirmModal
        v-if="showModal"
        :amount="props.amount"
        :staff-name="props.staffName"
        :guest-nickname="props.guestNickname"
        :message="props.message"
        :is-submit-loading="isSubmitLoading"
        @submit="handleSubmit"
        @close="setShowModal(false)"
      />
      <PointChargeAgeConfirmModal v-if="showAgeModal" :is-guest-user="true" @click="setShowAgeModal(false)" />
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
      margin-bottom: 16px;
    }
    &-return {
      width: 100%;
    }
  }
}
</style>
