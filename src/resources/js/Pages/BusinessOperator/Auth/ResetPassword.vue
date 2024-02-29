<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { RouteName } from '@/Utilities';

// const form = useForm({
//   email: '',
//   password: '',
//   remember: false,
// });

const props = defineProps<{ token: string; email: string }>();

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

// const submit = () => {
//   form.post(route('staff.login'), {
//     onFinish: () => form.reset('password'),
//   });
// };

const submit = () => {
  form.post(route(RouteName.BusinessOperatorPasswordStore), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <BaseLayout title="パスワード再設定">
    <HeaderSubText text="パスワード再設定" />
    <Divider />
    <div class="p-business-operator-reset-password">
      <div class="p-business-operator-reset-password__container">
        <Paragraph class="p-business-operator-reset-password__text">新しいパスワードを設定します。</Paragraph>
        <form class="p-business-operator-reset-password__form" @submit.prevent="submit">
          <FormGroupInput
            id="password"
            label="パスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-business-operator-reset-password__password"
          />
          <FormGroupInput
            id="password_confirmation"
            label="パスワード-確認"
            annotation="パスワードは、半角8文字以上から半角100文字以下です。英大文字・英小文字・数字それぞれを最低1文字ずつ含んでください。"
            :type="FormInputTypeProp.Password"
            v-model="form.password_confirmation"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password_confirmation"
            class="p-business-operator-reset-password__password-confirm"
          />
          <Button class="p-business-operator-reset-password__submit" :disabled="form.processing" text="変更する" />
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-reset-password {
  &__container {
    padding: 24px 16px;
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
  &__submit {
  }
}
</style>

<!-- 

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("staff.password.store"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script> -->

<!-- <template>
  <GuestLayout>
    <Head title="Reset Password" />

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Reset Password
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template> -->
