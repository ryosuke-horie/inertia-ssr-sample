<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import LinkWithIcon from '@/Components/atom/link/LinkWithIcon/LinkWithIcon.vue';
import { RouteName } from '@/Utilities';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const onSubmit = () => {
  form.post(route(RouteName.BusinessOperatorLogin), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <BaseLayout title="事業者ログイン">
    <HeaderSubText text="事業者ログイン" />
    <Divider />
    <div class="p-business-operators-login">
      <div class="p-business-operators-login__container">
        <form class="p-business-operators-login__form" @submit.prevent="onSubmit">
          <FormGroupInput
            id="email"
            label="メールアドレス"
            :type="FormInputTypeProp.Email"
            v-model="form.email"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.email"
            class="p-business-operators-login__email"
          />
          <FormGroupInput
            id="password"
            label="パスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-business-operators-login__password"
          />
          <LinkWithIcon
            class="p-business-operators-login__reset-password"
            text="パスワードを忘れた方"
            :href="RouteName.BusinessOperatorPasswordRequest"
          />
          <Button class="p-business-operators-login__submit" :disabled="form.processing" text="ログイン" />
          <Paragraph>
            <Link text="利用規約" :href="RouteName.BusinessOperatorPasswordRequest" />
            及び
            <Link text="プライバシーポリシー" :href="RouteName.BusinessOperatorPasswordRequest" />
            に同意の上、登録又はログインへお進みください。
          </Paragraph>
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operators-login {
  &__container {
    padding: 24px 16px;
  }
  &__form {
    display: flex;
    flex-direction: column;
  }
  &__email {
    margin-bottom: 30px;
  }
  &__password {
    margin-bottom: 10px;
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
