<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { useForm } from '@inertiajs/vue3';
import Alert from '@/Components/atom/alert/Alert.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { RouteRoleName } from '@/Utilities';
import { computed } from 'vue';

const props = defineProps<{ token: string; email: string }>();

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const isDisabled = computed<boolean>(() => {
  if (!form.password || !form.password_confirmation) return true;
  if (form.password !== form.password_confirmation) return true;
  return false;
});

const submit = () => {
  form.post(route('staff.password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <BaseLayout
    title="パスワードを忘れた方"
    :role="RouteRoleName.Staff"
    :auth-header="{
      text: 'パスワードを忘れた方',
    }"
  >
    <div class="p-staff-reset-password">
      <div class="p-staff-reset-password__container">
        <Alert
          v-if="form.errors.email || form.errors.token"
          text="予期せぬエラーが発生しました。"
          class="p-staff-reset-password__error"
        />
        <Paragraph class="p-staff-reset-password__text">新しいパスワードを設定します。</Paragraph>
        <form class="p-staff-reset-password__form" @submit.prevent="submit">
          <FormGroupInput
            id="password"
            label="パスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-staff-reset-password__password"
          />
          <FormGroupInput
            id="password_confirmation"
            label="パスワード-確認"
            annotation="パスワードは、半角8文字以上から半角100文字以下です。英大文字・英小文字・数字それぞれを最低1文字ずつ含んでください。"
            :type="FormInputTypeProp.Password"
            v-model="form.password_confirmation"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password_confirmation"
            class="p-staff-reset-password__password-confirm"
          />
          <Button
            class="p-staff-reset-password__submit"
            :disabled="isDisabled"
            :is-loading="form.processing"
            text="変更する"
          />
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-staff-reset-password {
  &__container {
    padding: 24px 16px;
  }
  &__error {
    margin-bottom: 24px;
  }
  &__text {
    margin-bottom: 30px;
  }
  &__form {
    display: flex;
    flex-direction: column;
  }
  &__password,
  &__password-confirm {
    margin-bottom: 30px;
  }
}
</style>
