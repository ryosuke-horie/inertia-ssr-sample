<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { RouteName } from '@/Utilities';
import { formatSelectBoxOptions } from '@/Utilities/enum';
import { Prefecture } from '@/Enums/Prefecture';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import { FormInputTypeProp } from '@/Components/atom/form/FormInput/type';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import FormGroupSelectBox from '@/Components/molecules/form/FormGroupSelectBox/FormGroupSelectBox.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import FormGroupTextarea from '@/Components/molecules/form/FormGroupTextarea/FormGroupTextarea.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import Modal from '@/Components/_admin/Modal/Modal.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';

type BusinessProps = {
  businessId: number;
  businessName: string;
};

const props = defineProps<{
  corporationApplicationId: number;
  corporationId: number;
  corporationName: string;
  email: string; // email_hashも同時に送信する // 暗号化 email_hashはhash化
  phone: number; //電話番号
  zipCode: number; // 郵便番号
  prefCode: string; // 都道府県
  city: string; // 市区町村
  invoice: string; // インボイス番号 // 暗号化
  applicantName: string; // 法人申込テーブルにて登録
  applicantNameKana: string; // 法人申込テーブルにて登録
  notes: string;
  businessList: BusinessProps[];
}>();

const form = useForm({
  corporationId: props.corporationId,
  corporationApplicationId: props.corporationApplicationId,
  corporationName: props.corporationName, // 法人名
  email: props.email, // メールアドレス email_hashも同時に送信する 暗号化 email_hashはhash化
  phone: props.phone, //電話番号
  zipCode: props.zipCode, // 郵便番号
  prefCode: props.prefCode, // 都道府県
  city: props.city, // 市区町村
  invoice: props.invoice, // インボイス番号 暗号化
  applicantName: props.applicantName, // 法人申込テーブルにて登録
  applicantNameKana: props.applicantNameKana, // 法人申込テーブルにて登録
  notes: props.notes,
});

// 都道府県の選択肢を生成
const prefectureOptions = formatSelectBoxOptions(
  Object.values(Prefecture).map((status) => ({
    value: status.value,
    label: status.name,
  })),
);

// 都道府県名を取得する
const prefectureName = computed(() => {
  const selectedPrefecture = Object.values(Prefecture).find((pref) => pref.value === Number(form.prefCode));
  return selectedPrefecture ? selectedPrefecture.name : '';
});

// モーダルに表示するデータ
const modalData = computed(() => [
  { label: '法人名', value: form.corporationName },
  { label: 'メールアドレス', value: form.email },
  { label: '電話番号', value: form.phone },
  { label: '郵便番号', value: form.zipCode },
  { label: '都道府県', value: prefectureName.value },
  { label: '市区町村', value: form.city },
  { label: 'インボイス番号', value: form.invoice },
  { label: '申込者名', value: form.applicantName },
  { label: '申込者名カナ', value: form.applicantNameKana },
  { label: '備考', value: form.notes },
]);

const isLoading = ref(false);
const showModal = ref(false);

const setIsModal = (val: boolean): void => {
  showModal.value = val;
};

const onSubmit = () => {
  console.log(isLoading.value);
  if (isLoading.value) return;
  isLoading.value = true;
  form.patch(route(RouteName.AdminCorporationUpdate, { corporationId: props.corporationId }), {
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
  <AdminLayout title="チアペイ管理" text="法人編集" :href="RouteName.AdminCorporation">
    <div class="p-corporation-create">
      <div class="p-corporation-create__form">
        <FormGroupInput
          id="corporationName"
          label="法人名"
          v-model="form.corporationName"
          :error="form.errors.corporationName"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="100"
          annotation="※100文字以内"
          class="p-corporation-create__form-element"
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
          class="p-corporation-create__form-element p-corporation-create__form-email"
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
          class="p-corporation-create__form-element p-corporation-create__form-phone"
        />
        <FormGroupSelectBox
          id="prefCode"
          label="都道府県"
          :model-value="form.prefCode"
          :options="prefectureOptions"
          :required-type="FormRequiredLabelTypeProp.Required"
          :error="form.errors.prefCode"
          class="p-corporation-create__form-element p-corporation-create__form-business-form"
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
          class="p-corporation-create__form-element p-corporation-create__form-zip-code"
        />
        <FormGroupInput
          id="city"
          label="市区町村"
          v-model="form.city"
          :error="form.errors.city"
          :required-type="FormRequiredLabelTypeProp.Required"
          class="p-corporation-create__form-element p-corporation-create__form-city"
        />
        <FormGroupInput
          id="invoice"
          label="インボイス番号"
          v-model="form.invoice"
          :error="form.errors.invoice"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="13"
          annotation="※Tを除く13桁の半角数字"
          class="p-corporation-create__form-element p-corporation-create__form-invoice"
        />
        <FormGroupInput
          id="applicantName"
          label="申込者氏名"
          v-model="form.applicantName"
          :error="form.errors.applicantName"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="60"
          annotation="※60文字以内"
          class="p-corporation-create__form-element p-corporation-create__form-applicant-name"
        />
        <FormGroupInput
          id="applicantNameKana"
          label="申込者氏名カナ"
          v-model="form.applicantNameKana"
          :error="form.errors.applicantNameKana"
          :required-type="FormRequiredLabelTypeProp.Required"
          :maxlength="120"
          annotation="※120文字以内、カタカナで入力してください"
          class="p-corporation-create__form-element p-corporation-create__form-applicant-name-kana"
        />
        <FormGroupTextarea
          id="notes"
          label="備考"
          v-model="form.notes"
          :error="form.errors.notes"
          :required-type="FormRequiredLabelTypeProp.Any"
          class="p-corporation-create__form-element p-corporation-create__form-notes"
        />
        <div class="p-corporation-create__button-container">
          <Button
            :variant="ButtonVariantProp.Primary"
            text="確認"
            class="p-corporation-create__modal--button"
            @click="setIsModal(true)"
          />
        </div>
        <Modal v-if="showModal" header-text="確認" @close="setIsModal(false)">
          <div class="p-corporation-create__modal-item">
            <div v-for="(data, index) in modalData" :key="index" class="p-corporation-create__modal-row">
              <span class="p-corporation-create__modal-row-label">{{ data.label }}:</span>
              <span class="p-corporation-create__modal-row-value">{{ data.value }}</span>
            </div>
          </div>
          <div class="p-corporation-create__modal-button-container">
            <Button
              :variant="ButtonVariantProp.Primary"
              text="登録"
              class="p-corporation-create__modal--button"
              @click="onSubmit"
              :is-loading="form.processing"
            />
          </div>
        </Modal>
      </div>
    </div>
  </AdminLayout>
</template>
<style lang="scss">
.p-corporation-create {
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
</style>
