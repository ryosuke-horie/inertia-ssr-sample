<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import FormGroupInputNumber from '@/Components/molecules/form/FormGroupInputNumber/FormGroupInputNumber.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormSelectBox from '@/Components/atom/form/FormSelectBox/FormSelectBox.vue';
import type { FormSelectBoxOptionItem } from '@/Components/atom/form/FormSelectBox/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { BUSINESSOPERATORApi, PostBusinessOperatorQrBusinessOperatorRequest } from '@/api';
import { configuration } from '@/lib/configuration';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';

const props = defineProps<{
  firstDeskNumber?: number;
  secondDeskNumber?: number;
}>();

const form = useForm<PostBusinessOperatorQrBusinessOperatorRequest>({
  isMulti: false,
  isAbroad: false,
  size: 100,
  firstDeskNumber: props.firstDeskNumber,
  secondDeskNumber: props.secondDeskNumber,
});

const sizeSelectOptions: FormSelectBoxOptionItem[] = [
  { value: '100', label: 'サイズ小 (100x100ピクセル)' },
  { value: '200', label: 'サイズ中 (200x200ピクセル)' },
  { value: '300', label: 'サイズ大 (300x300ピクセル)' },
];

const isLoading = ref(false);
const error = ref('');

const createQr = async (): Promise<void> => {
  await new BUSINESSOPERATORApi(configuration)
    .postBusinessOperatorQrBusinessOperator({
      isMulti: form.isMulti,
      isAbroad: form.isAbroad,
      size: form.size,
      firstDeskNumber: form.firstDeskNumber,
      secondDeskNumber: form.secondDeskNumber,
    })
    .then((res) => {
      // ダウンロード用のリンクを作成し、クリックしてダウンロード
      const link = document.createElement('a');
      link.href = res.data.filePath;
      link.setAttribute('download', res.data.fileName);
      document.body.appendChild(link);
      link.click();

      // リンクを削除;
      document.body.removeChild(link);

      // ファイル削除
      deleteFile(res.data.filePath);
    })
    .catch(() => {
      alert('予期せぬエラーが発生しました。');
    });
};

const deleteFile = async (filePath: string): Promise<void> => {
  await new BUSINESSOPERATORApi(configuration)
    .deleteBusinessOperatorFile({ filePath })
    .then((res) => {
      console.log(res);
    })
    .catch(() => {
      alert('予期せぬエラーが発生しました。');
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const onClick = () => {
  error.value = '';
  if (form.isMulti) {
    if (form.firstDeskNumber === null || form.secondDeskNumber === null) {
      error.value = '卓上番号を入力してください。';
      return;
    }
    if (form.firstDeskNumber != null && form.secondDeskNumber != null && form.firstDeskNumber > form.secondDeskNumber) {
      error.value = '卓上番号を正しく入力してください。';
      return;
    }
  }
  isLoading.value = true;
  createQr();
};
</script>

<template>
  <LoadingModal v-if="isLoading" />
  <BaseLayout
    title="ショップQRコード"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'ショップQRコード',
      href: RouteName.BusinessOperatorQR,
    }"
  >
    <div class="p-qr-business-operator">
      <div class="p-qr-business-operator__title-container">
        <Paragraph class="p-qr-business-operator__title-container__title">卓上番号</Paragraph>
        <input
          id="p-qr-business-operator__title-container__checkbox"
          type="checkbox"
          class="p-qr-business-operator__title-container__checkbox"
          v-model="form.isMulti"
        />
        <label
          for="p-qr-business-operator__title-container__checkbox"
          class="p-qr-business-operator__title-container__label"
          >複数卓上番号ダウンロード</label
        >
      </div>
      <div
        class="p-qr-business-operator__form-container"
        :class="{ 'p-qr-business-operator__form-flex': form.isMulti }"
      >
        <FormGroupInputNumber
          id="first-desk-number"
          v-model="form.firstDeskNumber"
          :is-error="form.errors.firstDeskNumber"
          :min="0"
          class="p-qr-business-operator__form-container__form"
        />
        <Paragraph v-if="!form.isMulti" class="p-qr-business-operator__form-container__attention">
          ※卓上番号なしのQRコードを発行したい場合は<br />&nbsp;&nbsp;&nbsp;入力せずにダウンロードしてください。
        </Paragraph>
        <Paragraph class="p-qr-business-operator__form-container__text" v-if="form.isMulti">卓から</Paragraph>
      </div>
      <div class="p-qr-business-operator__form-flex" v-if="form.isMulti">
        <FormGroupInputNumber
          id="first-desk-number"
          v-model="form.secondDeskNumber"
          :is-error="form.errors.secondDeskNumber"
          :min="0"
          class="p-qr-business-operator__form-container__form"
        />
        <Paragraph class="p-qr-business-operator__form-container__text">卓まで</Paragraph>
      </div>
      <div class="p-qr-business-operator__click">
        <FormSelectBox
          id="sizeSelectBox"
          v-model="form.size"
          :options="sizeSelectOptions"
          class="p-qr-business-operator__click__select-box"
        />
        <input
          id="p-qr-business-operator__click__checkbox"
          type="checkbox"
          class="p-qr-business-operator__click__checkbox"
          v-model="form.isAbroad"
        />
        <label for="p-qr-business-operator__click__checkbox" class="p-qr-business-operator__click__label">海外用</label>
        <Button text="ダウンロード" class="p-qr-business-operator__click__download" @click="onClick" />
        <Paragraph class="p-qr-business-operator__click__error" v-if="error">{{ error }}</Paragraph>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-qr-business-operator {
  padding: 20px 20px;
  width: 100%;
  &__title-container {
    display: flex;
    align-items: center;
    &__title {
      font-weight: 700;
    }
    &__checkbox {
      margin-left: 20px;
    }
    &__label {
      font-size: 12px;
    }
  }
  &__form-container {
    align-items: center;
    padding-top: 10px;
    &__form {
      width: 60%;
      padding-right: 20px;
    }
    &__attention {
      margin-top: 5px;
      font-size: 13px;
    }
    &__text {
      font-weight: 700;
    }
  }
  &__form-flex {
    display: flex;
    align-items: center;
    padding-top: 10px;
  }
  &__click {
    &__select-box {
      width: 100%;
      margin: 20px 0px;
      background-color: #ffffff !important;
    }
    &__checkbox {
      padding-left: 0;
    }
    &__label {
      padding-left: 5px;
    }
    &__download {
      width: 100%;
      background-color: black !important;
      margin: 20px 0;
    }
    &__error {
      color: red !important;
    }
  }
}
</style>
