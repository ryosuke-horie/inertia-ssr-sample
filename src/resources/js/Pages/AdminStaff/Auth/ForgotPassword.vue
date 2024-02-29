<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({ email: '' });

const onSubmit = () => {
  if (form.processing) return;
  form.post(route(RouteName.AdminStaffPasswordEmail));
};
</script>

<template>
  <BaseLayout
    title="パスワードを忘れた方"
    :auth-header="{
      role: RouteRoleName.AdminStaff,
      text: 'パスワードを忘れた方',
      href: RouteName.AdminStaffLogin,
    }"
  >
    <div class="p-staff-forgot-password">
      <div class="p-staff-forgot-password__container">
        <Paragraph class="p-staff-forgot-password__message">
          パスワード再設定メールを送信します。 管理者スタッフ登録時に登録されたメールアドレスを 入力してください。
        </Paragraph>
        <form class="p-staff-forgot-password__form" @submit.prevent="onSubmit">
          <FormGroupInput
            id="email"
            label="メールアドレス"
            :type="FormInputTypeProp.Email"
            v-model="form.email"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.email"
            class="p-staff-forgot-password__email"
          />
          <Button
            class="p-staff-forgot-password__submit"
            :disabled="form.processing"
            :is-loading="form.processing"
            text="送信する"
          />
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-staff-forgot-password {
  &__container {
    padding: 24px 16px;
  }
  &__message {
    margin-bottom: 30px;
  }
  &__form {
    display: flex;
    flex-direction: column;
  }
  &__email {
    margin-bottom: 30px;
  }
  &__reset-password {
    justify-content: flex-end;
    margin-bottom: 10px;
  }
  &__submit {
    margin-bottom: 30px;
  }
}
</style>
