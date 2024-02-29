<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import Alert from '@/Components/atom/alert/Alert.vue';
import { RouteRoleName } from '@/Utilities';

const props = defineProps<{ name: string; email: string; token: string; staffIds: number[] }>();

const form = useForm({
  name: props.name,
  token: props.token,
  email: props.email,
  staffIds: props.staffIds,
  password: '',
  password_confirmation: '',
});

const onSubmit = () => {
  if (form.processing) return;
  form.post(route('admin-staff.register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};

const isDisabled = computed<boolean>(() => {
  if (!form.password || !form.password_confirmation) return true;
  if (form.password !== form.password_confirmation) return true;
  return false;
});

const isUnexpectedError = computed<boolean>(() => {
  const { name, email, token, staffIds } = form.errors;
  if (name || email || token || staffIds) return true;
  return false;
});
</script>

<template>
  <BaseLayout
    title="スタッフ新規登録"
    :auth-header="{
      role: RouteRoleName.AdminStaff,
      text: '新規登録',
    }"
  >
    <div class="p-staff-register">
      <div class="p-staff-register__container">
        <Alert v-if="isUnexpectedError" text="不正なエラーが発生しました。" class="p-staff-register__alert" />
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
    margin-bottom: 30px;
  }
  &__submit {
    margin-top: 30px;
  }
}
</style>
