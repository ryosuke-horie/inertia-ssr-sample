<script lang="ts" setup>
import { useForm, useRemember } from '@inertiajs/vue3';
import { RouteName, RouteRoleName } from '@/Utilities';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import FormCheckBox from '@/Components/atom/form/FormCheckBox/FormCheckBox.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import FormGroupTextarea from '@/Components/molecules/form/FormGroupTextarea/FormGroupTextarea.vue';
import Link from '@/Components/atom/link/Link/Link.vue';

const form = useForm({
  name: '',
  email: '',
  content: '',
  consent: false,
});

// useRememberでフォームの状態を記憶
useRemember(form);

const onSubmit = () => {
  form.post(route(RouteName.BusinessOperatorInquiryConfirm));
};
</script>

<template>
  <BaseLayout
    title="お問い合わせ"
    :is-auth="true"
    :role="RouteRoleName.BusinessOperator"
    :auth-header="{
      role: RouteRoleName.BusinessOperator,
      text: 'お問い合わせ',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-business-operator-inquiry">
      <FormGroupInput
        id="name"
        label="お名前"
        :type="FormInputTypeProp.Text"
        v-model="form.name"
        :required-type="FormRequiredLabelTypeProp.Required"
        :error="form.errors.name"
        :maxlength="255"
        class="p-business-operator-inquiry__form-element p-business-operator-inquiry__form-name"
      />
      <FormGroupInput
        id="email"
        label="メールアドレス"
        :type="FormInputTypeProp.Email"
        v-model="form.email"
        :required-type="FormRequiredLabelTypeProp.Required"
        :error="form.errors.email"
        :maxlength="255"
        class="p-business-operator-inquiry__form-element p-business-operator-inquiry__form-email"
      />
      <FormGroupTextarea
        id="content"
        label="内容"
        v-model="form.content"
        :error="form.errors.content"
        :required-type="FormRequiredLabelTypeProp.Required"
        class="p-business-operator-inquiry__form-element p-business-operator-inquiry__form-content"
      />
      <Link text="個人情報の取扱いについて" :href="RouteName.Welcome" />
      <FormCheckBox label="個人情報の取扱いについて同意する。" v-model:checked="form.consent" />
      <template v-if="form.errors.consent">
        <p class="p-business-operator-inquiry__error">{{ form.errors.consent }}</p>
      </template>
      <div class="p-business-operator-inquiry__button-container">
        <Button text="確認" @click="onSubmit" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-inquiry {
  margin: 16px;
  &__form-element {
    margin-bottom: 16px;
    input,
    textarea {
      background: #fff;
    }
  }
  &__button-container {
    margin-top: 24px;
    display: flex;
    justify-content: center;
    button {
      width: 100%;
    }
  }
  &__error {
    font-size: 13px;
    color: #ff0000;
    margin-top: 8px;
  }
}
</style>
