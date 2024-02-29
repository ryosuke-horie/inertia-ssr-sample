<script lang="ts" setup>
import { useForm, useRemember } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';

const props = defineProps<{
  name: string;
  email: string;
  content: string;
}>();

const form = useForm({
  name: props.name,
  email: props.email,
  content: props.content,
  consent: true,
});

// useRememberでフォームの状態を記憶
useRemember(form);

// SSRでエラーが出るため、onMountedで初期化
const back = ref(() => {});
onMounted(() => {
  // フォーム状態保持して一覧画面へ戻す
  back.value = () => {
    window.history.back();
  };
});

const onSubmit = () => {
  form.post(route(RouteName.BusinessOperatorInquiryComplete));
};
</script>

<template>
  <BaseLayout
    title="お問い合わせ確認"
    :is-auth="true"
    :role="RouteRoleName.BusinessOperator"
    :auth-header="{
      text: 'お問い合わせ確認',
    }"
  >
    <div class="p-business-operator-inquiry-confirm">
      <div class="p-business-operator-inquiry-confirm__detail">
        <p class="p-business-operator-inquiry-confirm__label">お名前</p>
        <p class="p-business-operator-inquiry-confirm__value">{{ props.name }}</p>
      </div>
      <div class="p-business-operator-inquiry-confirm__detail">
        <p class="p-business-operator-inquiry-confirm__label">メールアドレス</p>
        <p class="p-business-operator-inquiry-confirm__value">{{ props.email }}</p>
      </div>
      <div class="p-business-operator-inquiry-confirm__detail">
        <p class="p-business-operator-inquiry-confirm__label">内容</p>
        <p class="p-business-operator-inquiry-confirm__value">{{ props.content }}</p>
      </div>

      <div class="p-business-operator-inquiry-confirm__button-container">
        <Button
          class="p-business-operator-inquiry-confirm__send-button"
          text="送信"
          @click="onSubmit"
          :variant="ButtonVariantProp.Primary"
        />
        <Button
          class="p-business-operator-inquiry-confirm__fix-button"
          text="修正する"
          @click="back"
          :variant="ButtonVariantProp.Secondary"
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-inquiry-confirm {
  margin: 16px;
  &__detail {
    margin-bottom: 16px;
  }
  &__label {
    font-weight: bold;
    margin-bottom: 8px;
  }
  &__value {
    background-color: #f5f5f5;
    padding: 16px;
    border-radius: 4px;
  }
  &__form-element {
    margin-bottom: 16px;
  }
  &__button-container {
    margin-top: 24px;
    display: flex;
    flex-direction: column;
    button {
      width: 100%;
    }
  }
  &__fix-button {
    margin-top: 16px;
  }
}
</style>
