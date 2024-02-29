<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { useForm } from '@inertiajs/vue3';

const authLayoutProps: AuthLayoutProps = {
  isAuthRoute: false,
  title: 'パスワード変更',
  header: {
    role: RouteRoleName.Corporation,
    text: 'パスワード変更',
    href: RouteName.CorporationProfileEdit,
  },
};

const form = useForm({
  currentPassword: '',
  password: '',
  password_confirmation: '',
});

const onSubmit = () => {
  if (form.processing) return;
  form.put(route(RouteName.CorporationProfileChangePasswordUpdate), {
    onFinish: () => form.reset('currentPassword', 'password', 'password_confirmation'),
  });
};
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: 'パスワード変更',
      href: RouteName.CorporationProfileEdit,
    }"
  >
    <div class="p-business-operator-staff-reset-email">
      <div class="p-business-operator-staff-reset-email__container">
        <Paragraph class="p-business-operator-staff-reset-email__message">新しいパスワードを設定します。</Paragraph>
        <form class="p-business-operator-staff-reset-email__form" @submit.prevent="onSubmit" novalidate>
          <FormGroupInput
            id="current_password"
            label="現在のパスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.currentPassword"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.currentPassword"
            class="p-business-operator-staff-reset-email__email"
          />
          <FormGroupInput
            id="password"
            label="新しいパスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-business-operator-staff-reset-email__email"
          />
          <FormGroupInput
            id="email"
            label="新しいパスワード-確認"
            :type="FormInputTypeProp.Password"
            v-model="form.password_confirmation"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password_confirmation"
            :annotation="`パスワードは、半角8文字以上から半角100文字以下です。\n英大文字・英小文字・数字それぞれを最低1文字ずつ含んでください。`"
            class="p-business-operator-staff-reset-email__email"
          />
          <Button
            class="p-business-operator-staff-reset-email__submit"
            :disabled="form.processing"
            :is-loading="form.processing"
            text="変更する"
          />
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-staff-reset-email {
  &__container {
    padding: 24px 16px;
  }
  &__flash {
    margin-bottom: 24px;
  }
  &__message {
    margin-bottom: 30px;
  }
  &__form {
    display: flex;
    flex-direction: column;
  }
  &__email,
  &__password {
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
