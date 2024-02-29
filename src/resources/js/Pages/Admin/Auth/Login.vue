<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
    default: '',
  },
});

const form = useForm({
  name: '',
  password: '',
  remember: true,
});

const submit = () => {
  form.post(route('mits-admin.login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <AdminLayout>
    <Head title="Log in" />
    <Heading2 class="main-header" text="MITS Fav" />
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <InputError class="error_message" :message="form.errors.name" />
      <InputError class="error_message" :message="form.errors.password" />

      <div class="mail-input-section form_input">
        <InputLabel for="name" value="名前" />
        <FormInput
          class="user_name"
          type="text"
          max-length="10"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />
      </div>

      <div class="pass-input-section form_input">
        <InputLabel for="password" value="パスワード" />
        <FormInput
          class="user_email"
          type="password"
          max-length="10"
          v-model="form.password"
          required
          autocomplete="current-password"
        />
      </div>

      <div class="block mt-4">
        <label class="flex items-center">
          <Checkbox name="remember" v-model:checked="form.remember" />
          <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          v-if="canResetPassword"
          :href="route('admin.password.request')"
          class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Forgot your password?
        </Link>

        <Button class="login" :variant="ButtonVariantProp.Primary" :text="`ログイン`" :disabled="form.processing" />
      </div>
    </form>
  </AdminLayout>
</template>
<style lang="scss" scoped>
.mail-input-section {
  display: flex;
  flex-direction: column;
}
.pass-input-section {
  display: flex;
  flex-direction: column;
}
.login__submit {
  margin: 30px auto;
}
.form_input {
  width: 60%;
  margin: 30px auto;
  input {
    background-color: white;
  }
}
.main-header {
  width: 60%;
  margin: 30px auto;
  text-align: center;
  justify-content: center;
  background-color: #e0ecfc;
}
.user_name .user_email {
  background-color: white !important;
  border-color: black;
}
.error_message {
  width: 60%;
  margin: 30px auto;
  text-align: center;
  justify-content: center;
  color: red;
}
.login {
  margin: 20px auto;
}
</style>
