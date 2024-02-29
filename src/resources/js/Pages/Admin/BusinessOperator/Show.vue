<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue';
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
  businessId: number; // 事業者ID
  businessApplicationId: number; // 事業者申込ID
  settingId: number; // 事業者設定ID
  corporationId: number; // 親法人
  corporationName: string; // 法人名
  businessName: string; // 事業者名
  businessForm: string; // 事業形態
  firstDeskNumber: number; // 卓番号１
  secondDeskNumber: number; // 卓番号２
  email: string; // email_hashも同時に送信する 暗号化 email_hashはhash化
  phone: number; //電話番号
  zipCode: number; // 郵便番号
  prefCode: string; // 都道府県
  city: string; // 市区町村
  invoice: string; // インボイス番号 暗号化
  businessDescription: string; // 事業内容
  businessStatus: string; // 稼働状況
  isPublish: string; // 公開設定 // 事業者設定テーブルにて登録
  applicantName: string; // 事業者申込テーブルにて登録
  applicantNameKana: string; // 事業者申込テーブルにて登録
  leadCompany: string; // 営業元
  notes: string;
}>();

onMounted(() => {
  if (props.corporationId) updateParentCorporationSelect(props.corporationId);
});

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
  businessId: props.businessId, // 事業者ID
  businessApplicationId: props.businessApplicationId, // 事業者申込ID
  settingId: props.settingId, // 事業者設定ID
  corporationId: props.corporationId, // 親法人
  corporationName: props.corporationName, // 法人名
  businessName: props.businessName, // 事業者名
  businessForm: props.businessForm, // 事業形態
  firstDeskNumber: props.firstDeskNumber, // 卓番号１
  secondDeskNumber: props.secondDeskNumber, // 卓番号２
  email: props.email, // email_hashも同時に送信する 暗号化 email_hashはhash化
  phone: props.phone, //電話番号
  zipCode: props.zipCode, // 郵便番号
  prefCode: props.prefCode, // 都道府県
  city: props.city, // 市区町村
  invoice: props.invoice, // インボイス番号 // 暗号化
  businessDescription: props.businessDescription, // 事業内容
  businessStatus: props.businessStatus, // 稼働状況
  isPublish: props.isPublish, // 公開設定 // 事業者設定テーブルにて登録
  applicantName: props.applicantName, // 事業者申込テーブルにて登録
  applicantNameKana: props.applicantNameKana, // 事業者申込テーブルにて登録
  leadCompany: props.leadCompany, // 営業元
  notes: props.notes,
});

// 親法人名を取得
const parentCorporationName = computed(() => {
  const selectedCorporation = props.parentCorporationList.find((corp) => corp.corporationId === form.corporationId);
  return selectedCorporation ? selectedCorporation.corporationName : '';
});

// 都道府県名を取得
const prefectureName = computed(() => {
  const selectedPrefecture = Object.values(Prefecture).find((pref) => pref.value === Number(form.prefCode));
  return selectedPrefecture ? selectedPrefecture.name : '';
});

// 営業元名を取得
const leadCompanyName = computed(() => {
  const selectedOption = leadCompanyOptions.find((option) => option.value.toString() === form.leadCompany);
  return selectedOption ? selectedOption.label : '';
});

const modalData = computed(() => [
  { label: '親法人', value: parentCorporationName.value },
  { label: '法人名', value: form.corporationName },
  { label: '事業者名', value: form.businessName },
  { label: '事業形態', value: form.businessForm },
  { label: '卓番号１', value: form.firstDeskNumber },
  { label: '卓番号２', value: form.secondDeskNumber },
  { label: 'メールアドレス', value: form.email },
  { label: '電話番号', value: form.phone },
  { label: '郵便番号', value: form.zipCode },
  { label: '都道府県', value: prefectureName.value },
  { label: '市区町村', value: form.city },
  { label: 'インボイス番号', value: form.invoice },
  { label: '事業内容', value: form.businessDescription },
  {
    label: '稼働状況',
    value:
      form.businessStatus == '1'
        ? '稼働中'
        : form.businessStatus == '2'
          ? '停止'
          : form.businessStatus == '3'
            ? '休業'
            : null,
  },
  { label: '公開設定', value: form.isPublish == '1' ? '公開' : form.isPublish == '2' ? '非公開' : null },
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

  form.patch(route(RouteName.AdminBusinessOperatorUpdate, { businessId: props.businessId }), {
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
  <AdminLayout title="チアペイ管理" text="事業者編集" :href="RouteName.AdminBusinessOperator">
    <div class="p-business-operator-show">
      <div class="p-business-operator-show__form">
        <FormGroupSelectBox
          id="parentCorporationId"
          label="親法人"
          :model-value="form.corporationId"
          :options="parentCorporationOptions"
          :disabled="isParentDisabled"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-show__form-element p-business-operator-show__form-parent-corporation"
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
          class="p-business-operator-show__form-element"
        />
        <FormGroupInput
          id="businessName"
          label="事業者名"
          v-model="form.businessName"
          :error="form.errors.businessName"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="100"
          annotation="※100文字以内"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-name"
        />
        <FormGroupSelectBox
          id="businessForm"
          label="事業形態"
          :model-value="form.businessForm"
          :options="businessFormOptions"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-form"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-first-desk-number"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-second-desk-number"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-email"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-phone"
        />
        <FormGroupSelectBox
          id="prefCode"
          label="都道府県"
          :model-value="form.prefCode"
          :options="prefectureOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.prefCode"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-form"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-zip-code"
        />
        <FormGroupInput
          id="city"
          label="市区町村"
          v-model="form.city"
          :error="form.errors.city"
          :required-type="FormRequiredLabelTypeProp.Required"
          class="p-business-operator-show__form-element p-business-operator-show__form-city"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-invoice"
        />
        <FormGroupInput
          id="businessDescription"
          label="事業内容"
          v-model="form.businessDescription"
          :error="form.errors.businessDescription"
          :required-type="FormRequiredLabelTypeProp.Any"
          :maxlength="300"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-description"
        />
        <FormGroupSelectBox
          id="businessStatus"
          label="稼働状況"
          :model-value="form.businessStatus"
          :options="businessStatusOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.businessStatus"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-status"
          @update:model-value="(val) => (form.businessStatus = val)"
        />
        <FormGroupSelectBox
          id="isPublish"
          label="公開状況"
          :model-value="form.isPublish"
          :options="isPublishOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.isPublish"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-is-publish"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-applicant-name"
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
          class="p-business-operator-show__form-element p-business-operator-show__form-applicant-name-kana"
        />
        <FormGroupSelectBox
          id="leadCompany"
          label="営業元"
          :model-value="form.leadCompany"
          :options="leadCompanyOptions"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-show__form-element p-business-operator-show__form-business-lead-company"
          @update:model-value="(val) => (form.leadCompany = val)"
        />
        <FormGroupTextarea
          id="notes"
          label="備考"
          v-model="form.notes"
          :error="form.errors.notes"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-business-operator-show__form-element p-business-operator-show__form-notes"
        />
      </div>
      <div class="p-business-operator-show__button-container">
        <Button
          :variant="ButtonVariantProp.Primary"
          text="確認"
          class="p-business-operator-show__modal--button"
          @click="setIsModal(true)"
        />
      </div>
      <Modal v-if="showModal" header-text="確認" @close="setIsModal(false)">
        <div class="p-business-operator-show__modal-item">
          <div v-for="(item, index) in modalData" :key="index" class="p-business-operator-show__modal-row">
            <span class="p-business-operator-show__modal-row-label">{{ item.label }}:</span>
            <span class="p-business-operator-show__modal-row-value">{{ item.value }}</span>
          </div>
        </div>
        <div class="p-business-operator-show__modal-button-container">
          <Button
            :variant="ButtonVariantProp.Primary"
            text="登録"
            class="p-business-operator-show__modal--button"
            @click="onSubmit"
            :is-loading="isLoading"
          />
        </div>
      </Modal>
    </div>
  </AdminLayout>
</template>
<style lang="scss">
.p-business-operator-show {
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
