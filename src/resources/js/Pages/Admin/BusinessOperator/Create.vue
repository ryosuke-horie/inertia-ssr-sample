<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { formatSelectBoxOptions } from '@/Utilities/enum';
import { BusinessFormNameStatus } from '@/Enums/BusinessFormNameStatus';
import { BusinessPublishedStatus } from '@/Enums/BusinessPublishedStatus';
import { LeadCompany } from '@/Enums/LeadCompany';
import { BusinessStatus } from '@/Enums/BusinessStatus';
import { Prefecture } from '@/Enums/Prefecture';
import { RouteName } from '@/Utilities';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import FormGroupSelectBox from '@/Components/molecules/form/FormGroupSelectBox/FormGroupSelectBox.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import FormGroupTextarea from '@/Components/molecules/form/FormGroupTextarea/FormGroupTextarea.vue';
import FormGroupInputNumber from '@/Components/molecules/form/FormGroupInputNumber/FormGroupInputNumber.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import Modal from '@/Components/_admin/Modal/Modal.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';

type ParentCorporationProps = {
  corporationId: number;
  corporationName: string;
};

const props = defineProps<{
  parentCorporationList: ParentCorporationProps[];
}>();

const parentCorporationOptions = formatSelectBoxOptions(
  props.parentCorporationList.map((corp) => ({
    value: corp.corporationId,
    label: corp.corporationName,
  })),
);

const businessFormOptions = formatSelectBoxOptions(
  Object.values(BusinessFormNameStatus).map((status) => ({
    value: status.name,
    label: status.name,
  })),
);

const isPublishOptions = formatSelectBoxOptions(
  Object.values(BusinessPublishedStatus).map((status) => ({
    value: status.value === 1 ? 'true' : 'false',
    label: status.name,
  })),
);

const businessStatusOptions = formatSelectBoxOptions(
  Object.values(BusinessStatus).map((status) => ({
    value: status.value,
    label: status.name,
  })),
);

const prefectureOptions = formatSelectBoxOptions(
  Object.values(Prefecture).map((status) => ({
    value: status.value,
    label: status.name,
  })),
);

const leadCompanyOptions = formatSelectBoxOptions(
  Object.values(LeadCompany).map((status) => ({
    value: status.value,
    label: status.name,
  })),
);

const form = useForm({
  corporationId: '', // 親法人
  corporationName: '', // 法人名
  businessName: '', // 事業者名
  businessForm: '', // 事業形態
  firstDeskNumber: 0, // 卓番号１
  secondDeskNumber: 0, // 卓番号２
  email: '', // email_hashも同時に送信する // 暗号化 email_hashはhash化
  password: '', // hash化
  phone: '', //電話番号
  zipCode: '', // 郵便番号
  prefCode: '', // 都道府県
  city: '', // 市区町村
  invoice: '', // インボイス番号 // 暗号化
  businessDescription: '', // 事業内容
  businessStatus: '', // 稼働状況
  isPublish: '', // 公開設定 // 事業者設定テーブルにて登録
  applicantName: '', // 事業者申込テーブルにて登録
  applicantNameKana: '', // 事業者申込テーブルにて登録
  leadCompany: '', // 営業元
  notes: '',
});

// 親法人名を取得
const parentCorporationName = computed(() => {
  const selectedCorporation = props.parentCorporationList.find(
    (corp) => corp.corporationId === Number(form.corporationId),
  );
  return selectedCorporation ? selectedCorporation.corporationName : '';
});

// 都道府県名を取得する
const prefectureName = computed(() => {
  // form.prefCodeに基づいてPrefectureオブジェクトから都道府県名を探す
  const selectedPrefecture = Object.values(Prefecture).find((pref) => pref.value === Number(form.prefCode));
  return selectedPrefecture ? selectedPrefecture.name : '';
});

// 事業形態名を取得する
const businessFormName = computed(() => {
  const selectedOption = businessFormOptions.find((option) => option.value.toString() === form.businessForm);
  return selectedOption ? selectedOption.label : '';
});

// 稼働状況名を取得する
const businessStatusName = computed(() => {
  const selectedOption = businessStatusOptions.find((option) => option.value.toString() === form.businessStatus);
  return selectedOption ? selectedOption.label : '';
});

// 公開設定名を取得する
const isPublishName = computed(() => {
  const selectedOption = isPublishOptions.find((option) => option.value === form.isPublish);
  return selectedOption ? selectedOption.label : '';
});

// 営業元名を取得する
const leadCompanyName = computed(() => {
  const selectedOption = leadCompanyOptions.find((option) => option.value.toString() === form.leadCompany);
  return selectedOption ? selectedOption.label : '';
});

const modalData = computed(() => [
  { label: '親法人', value: parentCorporationName.value },
  { label: '法人名', value: form.corporationName },
  { label: '事業者名', value: form.businessName },
  { label: '事業形態', value: businessFormName.value },
  { label: '卓番号１', value: form.firstDeskNumber },
  { label: '卓番号２', value: form.secondDeskNumber },
  { label: 'メールアドレス', value: form.email },
  { label: 'パスワード', value: form.password },
  { label: '電話番号', value: form.phone },
  { label: '郵便番号', value: form.zipCode },
  { label: '都道府県', value: prefectureName.value },
  { label: '市区町村', value: form.city },
  { label: 'インボイス番号', value: form.invoice },
  { label: '事業内容', value: form.businessDescription },
  { label: '稼働状況', value: businessStatusName.value },
  { label: '公開設定', value: isPublishName.value },
  { label: '申込者名', value: form.applicantName },
  { label: '申込者名カナ', value: form.applicantNameKana },
  { label: '営業元', value: leadCompanyName.value },
  { label: '備考', value: form.notes },
]);

const isLoading = ref(false);
const showModal = ref(false);
const isParentDisabled = ref(false);
const parentRequiredType = ref<FormRequiredLabelTypeProp>('required');

const setIsModal = (val: boolean): void => {
  showModal.value = val;
};

const updateParentCorporationSelect = (selectId: number): void => {
  // 親法人が選択されている場合は法人名・インボイス番号・申込者氏名・申込者氏名カナを入力不可にする
  isParentDisabled.value = selectId !== null && selectId !== undefined && selectId > 0;
  parentRequiredType.value = isParentDisabled.value
    ? FormRequiredLabelTypeProp.Any
    : FormRequiredLabelTypeProp.Required;

  // 親法人が選択されている場合は入力値をクリアする
  if (isParentDisabled.value) {
    form.corporationName = '';
    form.invoice = '';
    form.applicantName = '';
    form.applicantNameKana = '';
  }

  console.log('isParentDisabled', isParentDisabled.value, 'parentRequiredType', parentRequiredType.value);
};

const onSubmit = () => {
  if (isLoading.value) return;
  isLoading.value = true;

  form.post(route(RouteName.AdminBusinessOperatorStore), {
    onSuccess: () => {
      console.log('送信成功');
      setIsModal(false);
      isLoading.value = false;
    },
    onError: () => {
      console.log('送信失敗');
      setIsModal(false);
      isLoading.value = false;
    },
  });
};
</script>
<template>
  <LoadingModal v-if="isLoading" class="loading" />
  <AdminLayout title="チアペイ管理" text="事業者登録" :href="RouteName.AdminBusinessOperator">
    <div class="p-business-operator-create">
      <div class="p-business-operator-create__form">
        <FormGroupSelectBox
          id="parentCorporationId"
          label="親法人"
          :model-value="form.corporationId"
          :options="parentCorporationOptions"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-create__form-element p-business-operator-create__form-parent-corporation"
          @update:model-value="
            (val) => {
              form.corporationId = val;
              updateParentCorporationSelect(val);
            }
          "
        />
        <FormGroupInput
          id="corporationName"
          label="法人名"
          v-model="form.corporationName"
          :error="form.errors.corporationName"
          :disabled="isParentDisabled"
          :required-type="parentRequiredType"
          :maxlength="100"
          annotation="※100文字以内"
          class="p-business-operator-create__form-element"
        />
        <FormGroupInput
          id="businessName"
          label="事業者名"
          v-model="form.businessName"
          :error="form.errors.businessName"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="100"
          annotation="※100文字以内"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-name"
        />
        <FormGroupSelectBox
          id="businessForm"
          label="事業形態"
          :model-value="form.businessForm"
          :options="businessFormOptions"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-form"
          @update:model-value="(val) => (form.businessForm = val)"
        />
        <FormGroupInputNumber
          id="firstDeskNumber"
          label="卓番号１"
          v-model="form.firstDeskNumber"
          :error="form.errors.firstDeskNumber"
          :required-type="FormRequiredLabelTypeProp.Any"
          :min="0"
          :max="9999"
          annotation="※0以上9999以内"
          class="p-business-operator-create__form-element p-business-operator-create__form-first-desk-number"
        />
        <FormGroupInputNumber
          id="secondDeskNumber"
          label="卓番号２"
          v-model="form.secondDeskNumber"
          :error="form.errors.secondDeskNumber"
          :required-type="FormRequiredLabelTypeProp.Any"
          :min="0"
          :max="9999"
          annotation="※0以上9999以内"
          class="p-business-operator-create__form-element p-business-operator-create__form-second-desk-number"
        />
        <FormGroupInput
          id="email"
          label="メールアドレス"
          :type="FormInputTypeProp.Email"
          v-model="form.email"
          :error="form.errors.email"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="255"
          annotation="※255文字以内"
          class="p-business-operator-create__form-element p-business-operator-create__form-email"
        />
        <FormGroupInput
          id="password"
          label="パスワード"
          :type="FormInputTypeProp.Password"
          v-model="form.password"
          :error="form.errors.password"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="100"
          annotation="※8文字以上の100文字以内の半角英数字"
          class="p-business-operator-create__form-element p-business-operator-create__form-password"
        />
        <FormGroupInput
          id="phone"
          :type="FormInputTypeProp.Tel"
          label="電話番号"
          v-model="form.phone"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.phone"
          annotation="※11桁以内、数字のみ"
          :maxlength="11"
          class="p-business-operator-create__form-element p-business-operator-create__form-phone"
        />
        <FormGroupSelectBox
          id="prefCode"
          label="都道府県"
          :model-value="form.prefCode"
          :options="prefectureOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.prefCode"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-form"
          @update:model-value="(val) => (form.prefCode = val)"
        />
        <FormGroupInput
          id="zipCode"
          label="郵便番号"
          v-model="form.zipCode"
          :error="form.errors.zipCode"
          :required-type="FormRequiredLabelTypeProp.Required"
          annotation="※7桁、数字のみ"
          :maxlength="7"
          class="p-business-operator-create__form-element p-business-operator-create__form-zip-code"
        />
        <FormGroupInput
          id="city"
          label="市区町村"
          v-model="form.city"
          :error="form.errors.city"
          :required-type="FormRequiredLabelTypeProp.Required"
          class="p-business-operator-create__form-element p-business-operator-create__form-city"
        />
        <FormGroupInput
          id="invoice"
          label="インボイス番号"
          v-model="form.invoice"
          :error="form.errors.invoice"
          :disabled="isParentDisabled"
          :required-type="parentRequiredType"
          :maxlength="13"
          annotation="※Tを除く13桁の半角数字"
          class="p-business-operator-create__form-element p-business-operator-create__form-invoice"
        />
        <FormGroupInput
          id="businessDescription"
          label="事業内容"
          v-model="form.businessDescription"
          :error="form.errors.businessDescription"
          :required-type="FormRequiredLabelTypeProp.Any"
          :maxlength="300"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-description"
        />
        <FormGroupSelectBox
          id="businessStatus"
          label="稼働状況"
          :model-value="form.businessStatus"
          :options="businessStatusOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.businessStatus"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-status"
          @update:model-value="(val) => (form.businessStatus = val)"
        />
        <FormGroupSelectBox
          id="isPublish"
          label="公開状況"
          :model-value="form.isPublish"
          :options="isPublishOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.isPublish"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-is-publish"
          @update:model-value="(val) => (form.isPublish = val)"
        />
        <FormGroupInput
          id="applicantName"
          label="申込者氏名"
          v-model="form.applicantName"
          :error="form.errors.applicantName"
          :disabled="isParentDisabled"
          :required-type="parentRequiredType"
          :maxlength="60"
          annotation="※60文字以内"
          class="p-business-operator-create__form-element p-business-operator-create__form-applicant-name"
        />
        <FormGroupInput
          id="applicantNameKana"
          label="申込者氏名カナ"
          v-model="form.applicantNameKana"
          :error="form.errors.applicantNameKana"
          :disabled="isParentDisabled"
          :required-type="parentRequiredType"
          :maxlength="120"
          annotation="※120文字以内、カタカナで入力してください"
          class="p-business-operator-create__form-element p-business-operator-create__form-applicant-name-kana"
        />
        <FormGroupSelectBox
          id="leadCompany"
          label="営業元"
          :model-value="form.leadCompany"
          :options="leadCompanyOptions"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-create__form-element p-business-operator-create__form-business-lead-company"
          @update:model-value="(val) => (form.leadCompany = val)"
        />
        <FormGroupTextarea
          id="notes"
          label="備考"
          v-model="form.notes"
          :error="form.errors.notes"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-create__form-element p-business-operator-create__form-notes"
        />
      </div>
      <div class="p-business-operator-create__button-container">
        <Button
          :variant="ButtonVariantProp.Primary"
          text="確認"
          class="p-business-operator-create__modal--button"
          @click="setIsModal(true)"
        />
      </div>
      <Modal v-if="showModal" header-text="確認" @close="setIsModal(false)">
        <div class="p-business-operator-create__modal-item">
          <div v-for="(item, index) in modalData" :key="index" class="p-business-operator-create__modal-row">
            <span class="p-business-operator-create__modal-row-label">{{ item.label }}:</span>
            <span class="p-business-operator-create__modal-row-value">{{ item.value }}</span>
          </div>
        </div>
        <div class="p-business-operator-create__modal-button-container">
          <Button
            :variant="ButtonVariantProp.Primary"
            text="登録"
            class="p-business-operator-create__modal--button"
            @click="onSubmit"
            :is-loading="isLoading"
          />
        </div>
      </Modal>
    </div>
  </AdminLayout>
</template>
<style lang="scss">
.p-business-operator-create {
  margin-top: 16px;
  background: #f5f5f5;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border: 1px solid #dcdcdc;
  padding: 32px;
  &__form {
    &-element {
      margin-bottom: 16px;
      input,
      select,
      textarea {
        background: #fff;
      }
    }
    &-first-desk-number,
    &-second-desk-number,
    &-zip-code {
      width: 120px;
    }
    &-phone {
      width: 160px;
    }
    &-invoice {
      width: 170px;
    }
  }
  &__button-container {
    display: flex;
    justify-content: center;
    margin-top: 16px;
  }
  &__modal {
    &-item {
      padding: 16px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    &-row {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      width: 100%;

      &:last-child {
        margin-bottom: 0;
      }

      &-label {
        flex-grow: 0;
        text-align: left;
        margin-right: 10px;
        width: 25%;
      }

      &-value {
        flex-grow: 1;
        flex-basis: 70%;
        text-align: left;
      }
    }
    &-button-container {
      display: flex;
      justify-content: center;
      margin-top: 16px;
    }
  }
}
.loading {
  z-index: 9999;
}
.c-form-input:disabled {
  background-color: var(--gray) !important;
  border-color: var(--gray) !important;
}
</style>
