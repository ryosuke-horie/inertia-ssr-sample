<script lang="ts" setup>
import { AnkerButtonVariantProp } from '@/Components/atom/button/AnkerButton/type';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import LinkWithIcon from '@/Components/atom/link/LinkWithIcon/LinkWithIcon.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { useForm } from '@inertiajs/vue3';
import FormCheckBox from '@/Components/atom/form/FormCheckBox/FormCheckBox.vue';
import snsIcons from '@/../assets/images/icon/icon_sns.png';
import Image from '@/Components/atom/image/Image/Image.vue';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const onSubmit = () => {
  form.post(route(RouteName.UserLogin), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <BaseLayout
    title="ユーザーログイン"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'ログイン',
      href: RouteName.UserLoginMethod,
    }"
  >
    <div class="p-user-login">
      <div class="p-user-login__container">
        <form class="p-user-login__form" @submit.prevent="onSubmit">
          <FormGroupInput
            id="email"
            label="メールアドレス"
            :type="FormInputTypeProp.Email"
            v-model="form.email"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.email"
            class="p-user-login__email"
          />
          <FormGroupInput
            id="password"
            label="パスワード"
            :type="FormInputTypeProp.Password"
            v-model="form.password"
            :required-type="FormRequiredLabelTypeProp.Required"
            :error="form.errors.password"
            class="p-user-login__password"
          />
          <FormCheckBox label="ログイン保持" v-model:checked="form.remember" />
          <LinkWithIcon
            class="p-user-login__reset-password"
            text="パスワードを忘れた方"
            :href="RouteName.UserPasswordRequest"
          />
          <Button class="p-user-login__submit" :disabled="form.processing" text="ログイン" />

          <div class="p-user-login__border">
            <span class="p-user-login__border-line"></span>
            <span class="p-user-login__border-text">または</span>
            <span class="p-user-login__border-line"></span>
          </div>
          <!-- Auth0によるSNSログイン -->
          <a class="p-user-login__link-icon-sns" href="/user/sns-login">
            <!-- Inertia.jsのLinkはヘッダーが除外されるため仮でaタグを使用 -->
            <Image :src="snsIcons" width="100%" height="80%" />
            <p>SNSアカウントでログイン</p>
          </a>
          <AnkerButton
            class="p-user-login__guest"
            text="ゲストで応援する"
            :variant="AnkerButtonVariantProp.Secondary"
            :href="route(RouteName.GuestUserBusinessOperator)"
          />
          <Paragraph>
            <Link text="利用規約" :href="RouteName.UserPasswordRequest" />
            及び
            <Link text="プライバシーポリシー" :href="RouteName.UserPasswordRequest" />
            に同意の上、登録又はログインへお進みください。
          </Paragraph>
        </form>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-login {
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
  &__border {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    &-text {
      padding: 0 10px;
    }
    &-line {
      flex: 1;
      height: 1px;
      background: #ccc;
      margin: 0 10px;
    }
  }
  &__link-icon-sns {
    border: 1px solid #ccc;
    border-radius: 10px;
    display: grid;
    justify-items: center;
    padding: 25px 0;
    text-align: center;
    color: var(--black);
    font-weight: bold;
  }
  &__guest {
    margin: 10px 0;
  }
}
</style>
