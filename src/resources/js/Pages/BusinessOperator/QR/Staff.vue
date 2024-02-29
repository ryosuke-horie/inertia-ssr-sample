<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { useForm } from '@inertiajs/vue3';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import FormSelectBox from '@/Components/atom/form/FormSelectBox/FormSelectBox.vue';
import type { FormSelectBoxOptionItem } from '@/Components/atom/form/FormSelectBox/type';
import Button from '@/Components/atom/button/Button/Button.vue';
import { BUSINESSOPERATORApi, PostBusinessOperatorQrStaffRequest } from '@/api';
import { configuration } from '@/lib/configuration';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
  staffList: {
    staffId: number;
    role: string;
    staffName: string;
    src: string;
  }[];
}>();

const sizeSelectOptions: FormSelectBoxOptionItem[] = [
  { value: '100', label: 'サイズ小 (100x100ピクセル)' },
  { value: '200', label: 'サイズ中 (200x200ピクセル)' },
  { value: '300', label: 'サイズ大 (300x300ピクセル)' },
];

const isLoading = ref(false);
const isError = ref(false);
const searchStaffName = ref('');

const form = useForm<PostBusinessOperatorQrStaffRequest>({
  staffIdList: [],
  size: 100,
});

const filteredStaffList = computed(() => {
  // 検索文字列を小文字に変換して取得
  const searchQuery = searchStaffName.value.toLowerCase();

  // リストを検索文字列に基づいてフィルタリング
  return props.staffList.filter((staff) => {
    // スタッフの名前を小文字に変換して検索文字列と比較
    return staff.staffName.toLowerCase().includes(searchQuery);
  });
});

const onClick = () => {
  isError.value = false;
  if (form.staffIdList.length === 0) {
    isError.value = true;
    return;
  }
  isLoading.value = true;
  createQr();
};

const createQr = async (): Promise<void> => {
  await new BUSINESSOPERATORApi(configuration)
    .postBusinessOperatorQrStaff({ staffIdList: form.staffIdList, size: form.size })
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
</script>

<template>
  <LoadingModal v-if="isLoading" />
  <BaseLayout
    title="スタッフQRコード"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフQRコード',
      href: RouteName.BusinessOperatorQR,
    }"
  >
    <Heading2 text="スタッフ一覧" class="p-qr-staff-title" />
    <Divider />
    <div class="p-qr-staff-search">
      <FormInput id="search" type="text" v-model="searchStaffName" class="p-qr-staff-search__form" />
    </div>
    <div class="p-qr-staff-list">
      <div v-for="staff in filteredStaffList" :key="staff.staffId" class="p-qr-staff-list__item">
        <div class="p-qr-staff-list__item__image">
          <IconImage :src="staff.src" :width="70" :height="70" />
        </div>
        <div class="p-qr-staff-list__item__text">
          <Paragraph class="p-qr-staff-list__item__text__role">{{ staff.role }}</Paragraph>
          <Paragraph class="p-qr-staff-list__item__text__name">{{ staff.staffName }}</Paragraph>
        </div>
        <div class="p-qr-staff-list__item__checkbox">
          <input
            :id="'p-qr-staff-list__item__checkbox__input' + staff.staffId"
            type="checkbox"
            class="p-qr-staff-list__item__checkbox__input"
            :value="staff.staffId"
            v-model="form.staffIdList"
          />
          <label
            :for="'p-qr-staff-list__item__checkbox__input' + staff.staffId"
            class="p-qr-staff-list__item__checkbox__label"
          ></label>
        </div>
      </div>
      <Divider />
    </div>
    <div class="p-qr-staff-click">
      <FormSelectBox
        id="sizeSelectBox"
        v-model="form.size"
        :options="sizeSelectOptions"
        class="p-qr-staff-click__select-box"
      />
      <Button text="ダウンロード" class="p-qr-staff-click__download" @click="onClick" />
      <Paragraph class="p-qr-staff-click__error" v-if="isError">スタッフを選択してください</Paragraph>
    </div>
  </BaseLayout>
</template>
<style lang="scss">
.p-qr-staff-title {
  padding-left: 10px;
}
.p-qr-staff-search {
  background-color: #eeeeee;
  width: 100%;
  padding: 10px 30px;
  text-align: center;
  &__form {
    background-color: #ffffff;
    box-shadow: 0px 0px 10px 0px #0000000d;
    border-radius: 5px;
    padding: 10px;
    width: 100%;
  }
}
.p-qr-staff-list {
  height: 40vh;
  overflow-y: auto;
  &::-webkit-scrollbar {
    display: none;
  }
  &__item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    &__image {
      width: 30%;
      padding-left: 30px;
    }
    &__text {
      width: 50%;
      &__role {
        font-size: 14px;
        font-weight: 500;
        line-height: 16px;
        letter-spacing: -0.015em;
      }
      &__name {
        font-size: 20px;
        font-weight: 500;
        line-height: 22px;
        letter-spacing: -0.015em;
      }
    }
    &__checkbox {
      width: 20%;
      &__input {
        display: none;
        &:checked + .p-qr-staff-list__item__checkbox__label {
          background-color: #36df98;
          &::before {
            display: block;
          }
        }
      }
      &__label {
        width: 40px;
        height: 40px;
        background-color: #eeeeee;
        display: inline-block;
        border-radius: 5px;
        position: relative;
        &::before {
          display: none;
          position: absolute;
          content: '✔';
          font-size: 25px;
          top: 2px;
          left: 10px;
        }
      }
    }
  }
}
.p-qr-staff-click {
  padding: 0px 30px;
  text-align: center;
  &__select-box {
    width: 100%;
    margin: 20px 0px;
    background-color: #fff !important;
  }
  &__download {
    width: 100%;
    background-color: black !important;
  }
  &__error {
    color: red !important;
  }
}
</style>
