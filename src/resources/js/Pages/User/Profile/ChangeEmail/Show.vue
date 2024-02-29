<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const onSubmit = () => {
  if (form.processing) return;
  form.put(route(RouteName.UserProfileChangeEmailCreate), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <BaseLayout
    title="メールアドレス変更"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'メールアドレス変更',
      href: RouteName.UserProfileShow,
    }"
  >
    <div class="p-business-operator-staff-reset-email">
      <div class="p-business-operator-staff-reset-email__container">
        <Paragraph class="p-business-operator-staff-reset-email__message">
          メールアドレスを変更できます。<br />新しいメールアドレスを入力してください。
        </Paragraph>
        <form class="p-business-operator-staff-reset-email__form" @submit.prevent="onSubmit" novalidate>
          <FormGroupInput
            id="email"
            label="新しいメールアドレス"
            :type="FormInputTypeProp.Email"
            v-model="form.email"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.email"
            class="p-business-operator-staff-reset-email__email"
          />
          <FormGroupInput
            id="password"
            label="パスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-business-operator-staff-reset-email__password"
          />
          <Button
            class="p-business-operator-staff-reset-email__submit"
            :disabled="form.processing"
            :is-loading="form.processing"
            text="送信する"
          />
          <ColorSection :is-rounded="true">
            <Paragraph>
              ※URLの有効期限は60分です。※登録時のメールアドレスが、変更等により利用できない場合は、ご本人確認ができないためパスワードの再発行ができません。その場合は、お手数ですが、
              <Link text="新規会員登録" :href="RouteName.StaffPasswordRequest" />
              をお願いいたします。
              ※再設定メールが受け取れない場合は、迷惑メールボックスの設定や、ドメイン着信許可の設定をお確かめの上、〇〇〇@fav.comのメールを受信できるようにしてください。
            </Paragraph>
          </ColorSection>
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
