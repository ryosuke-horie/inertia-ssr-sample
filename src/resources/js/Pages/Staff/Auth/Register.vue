<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { RouteRoleName } from '@/Utilities';
import Button from '@/Components/atom/button/Button/Button.vue';
import Alert from '@/Components/atom/alert/Alert.vue';

const props = defineProps<{ name: string; email: string; token: string }>();

const form = useForm({
  staffName: props.name,
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const onSubmit = () => {
  if (form.processing) return;
  form.post(route('staff.register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};

const isDisabled = computed<boolean>(() => {
  if (!form.password || !form.password_confirmation) return true;
  if (form.password !== form.password_confirmation) return true;
  return false;
});

const isUnexpectedError = computed<boolean>(() => {
  const { staffName, email, token } = form.errors;
  if (staffName || email || token) return true;
  return false;
});
</script>

<template>
  <BaseLayout
    title="スタッフ新規登録"
    :role="RouteRoleName.Staff"
    :auth-header="{
      text: '新規登録',
    }"
  >
    <div class="p-staff-register">
      <div class="p-staff-register__container">
        <Alert v-if="isUnexpectedError" text="予期せぬエラーが発生しました。" class="p-staff-register__alert" />
        <form class="p-staff-register__form" @submit.prevent="onSubmit">
          <FormGroupInput
            id="password"
            label="パスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-staff-register__password"
          />
          <FormGroupInput
            id="password_confirm"
            label="パスワード-確認-"
            :type="FormInputTypeProp.Password"
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
            class="p-staff-register__password"
          />
          <Button
            :disabled="isDisabled"
            :is-loading="form.processing"
            text="登録"
            @click="onSubmit"
            class="p-staff-register__submit"
          />
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-staff-register {
  &__container {
    padding: 24px 16px;
  }
  &__alert {
    margin-bottom: 24px;
  }
  &__form {
    display: flex;
    flex-direction: column;
  }
  &__password {
    margin-bottom: 10px;
  }
  &__submit {
    margin-top: 30px;
  }
}
</style>
