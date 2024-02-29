<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import FormGroupHeader from '@/Components/molecules/form/FormGroupHeader/FormGroupHeader.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import { Prefecture } from '@/Enums/Prefecture';
import FormGroupSelectBox from '@/Components/molecules/form/FormGroupSelectBox/FormGroupSelectBox.vue';
import type { FormSelectBoxOptionItem } from '@/Components/atom/form/FormSelectBox/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import { ALLApi } from '@/api';
import { configuration } from '@/lib/configuration';
import { formatSelectBoxOptions } from '@/Utilities/enum';

const props = defineProps<{
  businessName: string;
  applicantName: string;
  applicantNameKana: string;
  zipCode: string;
  prefCode: string;
  city: string;
  streetAddress: string;
  building: string;
  email: string;
  phone: number;
}>();

const form = useForm({
  businessName: props.businessName,
  applicantName: props.applicantName,
  applicantNameKana: props.applicantNameKana,
  zipCode: props.zipCode,
  prefCode: props.prefCode,
  city: props.city,
  streetAddress: props.streetAddress,
  building: props.building,
  email: props.email,
  password: '',
  password_confirmation: '',
  phone: props.phone,
});

const prefOtpions: FormSelectBoxOptionItem[] = formatSelectBoxOptions(
  Object.values(Prefecture).map((item) => ({
    label: item.name,
    value: item.value,
  })),
);

const handleInput = async (): Promise<void> => {
  if (form.zipCode.length == 7) {
    await new ALLApi(configuration)
      .getAllZipcloudZipCode(form.zipCode)
      .then((res) => {
        form.prefCode = res.data.prefCode;
        form.city = res.data.city;
      })
      .catch(() => {
        alert('予期せぬエラーが発生しました。');
      });
  }
};

const onSubmit = () => {
  form.post(route(RouteName.CorporationBusinessOperatorManagementConfirm), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '新規店舗申込',
      href: RouteName.CorporationBusinessOperatorManagement,
    }"
  >
    <form class="p-corporation-business-management-create" @submit.prevent="onSubmit">
      <Paragraph class="p-corporation-business-management-create__annotation">必須項目を入力してください。</Paragraph>
      <div class="p-corporation-business-management-create__wrapper">
        <FormGroupInput
          id="business_name"
          label="店舗名"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.businessName"
          :error="form.errors.businessName"
        />
        <FormGroupInput
          id="applicant_name"
          label="担当者氏名"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.applicantName"
          :error="form.errors.applicantName"
        />
        <FormGroupInput
          id="applicant_name_kana"
          label="担当者氏名（カナ）"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.applicantNameKana"
          :error="form.errors.applicantNameKana"
        />
        <FormGroupInput
          id="phone"
          label="電話番号"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.phone"
          :error="form.errors.phone"
          annotation="※ハイフン抜き"
        />
        <FormGroupHeader id="address" label-name="住所" type="required" />
        <div class="p-corporation-business-management-create__wrapper__address">
          <FormGroupInput
            id="zip_code"
            label="郵便番号"
            v-model="form.zipCode"
            :error="form.errors.zipCode"
            class="p-corporation-business-management-create__wrapper__address__zip-code"
            :maxlength="7"
            annotation="※ハイフン抜き"
            @input="handleInput"
          />
          <FormGroupSelectBox
            id="pref_code"
            label="都道府県"
            v-model="form.prefCode"
            :error="form.errors.prefCode"
            :options="prefOtpions"
            class="p-corporation-business-management-create__wrapper__address__pref-code"
          />
        </div>
        <FormGroupInput
          id="city"
          label="市区町村"
          v-model="form.city"
          :error="form.errors.city"
          class="p-corporation-business-management-create__wrapper__city"
        />
        <FormGroupInput
          id="street_address"
          label="番地"
          v-model="form.streetAddress"
          :error="form.errors.streetAddress"
          class="p-corporation-business-management-create__wrapper__streetAddress"
        />
        <FormGroupInput
          id="building"
          label="建物名など"
          v-model="form.building"
          :error="form.errors.building"
          class="p-corporation-business-management-create__wrapper__building"
        />
        <FormGroupInput
          id="email"
          label="メールアドレス"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.email"
          :error="form.errors.email"
          class="p-corporation-business-management-create__wrapper__email"
        />
        <FormGroupInput
          id="password"
          label="パスワード"
          :type="FormInputTypeProp.Password"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.password"
          :error="form.errors.password"
          class="p-corporation-business-management-create__wrapper__password"
        />
        <FormGroupInput
          id="password"
          label="パスワード確認"
          :type="FormInputTypeProp.Password"
          :required-type="FormRequiredLabelTypeProp.Required"
          v-model="form.password_confirmation"
          :error="form.errors.password_confirmation"
          class="p-corporation-business-management-create__wrapper__password-confirmation"
        />
      </div>
      <Button text="申込内容確認" class="p-corporation-business-management-create__button" />
      <Paragraph class="p-corporation-business-management-create__annotation">
        <Link :href="route(RouteName.CorporationMypage)">利用規約</Link>及び
        <Link :href="route(RouteName.CorporationMypage)">プライバシーポリシー</Link>に同意の上、<br />
        登録又はログインへお進みください。
      </Paragraph>
    </form>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-business-management-create {
  padding: 20px 30px;
  &__annotation {
    font-size: 14px;
    font-weight: 500;
    letter-spacing: -0.015em;
  }
  &__wrapper {
    padding: 20px 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
    &__address {
      display: flex;
      &__zip-code {
        width: 120px;
        margin-right: 20px;
      }
      &__pref-code {
        width: 160px;
      }
    }
  }
  &__button {
    width: 100%;
    margin: 15px 0;
  }
  &__annotation {
    font-size: 14px;
    font-weight: 500;
    letter-spacing: -0.015em;
  }
}
</style>
