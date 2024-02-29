<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { FormRequiredLabelTypeProp } from '@/Components/atom/form/FormRequiredLabel/type';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Modal from '@/Components/molecules/modal/Modal/Modal.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Alert from '@/Components/atom/alert/Alert.vue';
import StaffCheckListItem from '@/Components/molecules/list/StaffCheckListItem/StaffCheckListItem.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

interface FormData {
  name: string;
  email: string;
  staffIds: number[];
}

const props = defineProps<{ staffList: { staffId: number; staffName: string; src?: string }[] }>();

const form = useForm<FormData>({
  name: '',
  email: '',
  staffIds: [],
});

const isModal = ref<boolean>(false);
const checkedStaffList = computed<{ staffId: number; staffName: string; src?: string }[]>(() => {
  return props.staffList
    .filter((staff) => {
      return form.staffIds.includes(staff.staffId);
    })
    .sort((a, b) => {
      return a.staffId - b.staffId;
    });
});

const setIsModal = (val: boolean): void => {
  isModal.value = val;
};

const onClickRegister = () => {
  if (form.processing) return;
  form.post(route(RouteName.BusinessOperatorStaffAdminAddCreate), {
    onFinish: () => form.reset('staffIds'),
  });
};
</script>

<template>
  <BaseLayout
    title="スタッフ管理者登録"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフ管理者登録',
      href: RouteName.BusinessOperatorStaffAdmin,
    }"
  >
    <div class="p-business-operator-staff-create-admin-staff">
      <div v-if="form.errors.staffIds" class="p-business-operator-staff-create-admin-staff__error">
        <Alert :text="form.errors.staffIds" />
      </div>
      <form class="p-business-operator-staff-create-admin-staff__form">
        <div class="p-business-operator-staff-create-admin-staff__form-item">
          <FormGroupInput
            id="name"
            type="text"
            label="管理者スタッフ名"
            :required-type="FormRequiredLabelTypeProp.Required"
            v-model="form.name"
            :error="form.errors.name"
            class="p-business-operator-staff-create-admin-staff__form-name"
          />
          <FormGroupInput
            id="name"
            type="text"
            label="メールアドレス"
            :required-type="FormRequiredLabelTypeProp.Required"
            v-model="form.email"
            :error="form.errors.email"
            class="p-business-operator-staff-create-admin-staff__form-email"
          />
        </div>
      </form>
      <Heading2 text="連携スタッフ" />
      <Divider />
      <div v-if="checkedStaffList.length" class="p-business-operator-staff-create-admin-staff__checked-list">
        <ul>
          <li v-for="(staff, index) of checkedStaffList" :key="index" class="c-staff-check-list-item">
            <IconImage :src="staff.src || NoImageUser" :width="70" :height="70" />
            <Paragraph class="c-staff-check-list-item__name">{{ staff.staffName }}</Paragraph>
          </li>
        </ul>
      </div>
      <div v-else class="p-business-operator-staff-create-admin-staff__checked-list-empty">
        連携スタッフが選択されていません。
      </div>
      <Divider />
      <div class="p-business-operator-staff-create-admin-staff__buttons">
        <Button
          class="p-business-operator-staff-create-admin-staff__buttons-add"
          text="連携スタッフ追加"
          @click="setIsModal(true)"
        />
        <Button
          class="p-business-operator-staff-create-admin-staff__buttons-register"
          text="登録"
          @click="onClickRegister"
          :is-loading="form.processing"
        />
      </div>
      <Modal v-if="isModal" variant="white" header-text="連携スタッフ追加" @close="setIsModal(false)">
        <ul v-if="props.staffList.length">
          <StaffCheckListItem
            v-for="(staff, index) of props.staffList"
            v-model="form.staffIds"
            :key="index"
            :name="staff.staffName"
            :value="staff.staffId"
            :src="staff.src || NoImageUser"
          />
        </ul>
        <EmptySection v-else text="追加できるスタッフが存在しません" />
      </Modal>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-staff-create-admin-staff {
  &__error {
    padding: 16px;
  }
  &__form {
    display: flex;
    flex-direction: column;
    &-item {
      display: flex;
      flex-direction: column;
      padding: 24px 16px;
      border-bottom: solid 1px #ddd;
    }
    &-email {
      margin-top: 16px;
    }
  }
  &__checked-list {
    &-empty {
      text-align: center;
      padding: 16px;
    }
  }
  &__buttons {
    display: flex;
    flex-direction: column;
    padding: 24px 16px;
    &-add {
      height: 40px !important;
      background-color: #fff !important;
      color: #000 !important;
      border-color: #666 !important;
    }
    &-register {
      margin-top: 24px;
    }
  }
}

.c-staff-check-list-item {
  display: flex;
  align-items: center;
  padding: 16px;
  & + .c-staff-check-list-item {
    border-top: solid 1px #ddd;
  }
  &__name {
    flex: 1;
    width: 100%;
    text-align: left;
    margin: 0 16px;
  }
}
</style>
