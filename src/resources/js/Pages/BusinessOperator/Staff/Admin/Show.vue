<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { useForm } from '@inertiajs/vue3';
import Divider from '@/Components/atom/divider/Divider.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Modal from '@/Components/molecules/modal/Modal/Modal.vue';
import AnkerSettingListItem from '@/Components/molecules/list/AnkerSettingListItem/AnkerSettingListItem.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import Alert from '@/Components/atom/alert/Alert.vue';
import { ref, computed } from 'vue';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';
import StaffCheckListItem from '@/Components/molecules/list/StaffCheckListItem/StaffCheckListItem.vue';

const props = defineProps<{
  adminStaffId: number;
  name: string;
  connectedStaffIds: number[];
  staffList: { staffId: number; staffName: string; src?: string }[];
}>();

const form = useForm<{ name: string; staffIds: number[] }>({
  name: props.name,
  staffIds: props.connectedStaffIds,
});

const deleteStaff = (staffId: number): void => {
  form.staffIds = form.staffIds.filter((id) => id !== staffId);
};

const computedConnectedStaffList = computed(() => {
  return props.staffList
    .filter((staff) => {
      return form.staffIds.includes(staff.staffId);
    })
    .sort((a, b) => {
      return a.staffId - b.staffId;
    });
});

const isModal = ref<boolean>(false);
const setIsModal = (val: boolean): void => {
  isModal.value = val;
};

const onSubmit = (): void => {
  if (form.processing) return;
  form.put(route(RouteName.BusinessOperatorStaffAdminUpdate, { adminStaffId: props.adminStaffId }));
};
</script>

<template>
  <BaseLayout
    title="スタッフ管理者詳細"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフ管理者詳細',
      href: RouteName.BusinessOperatorStaffAdmin,
    }"
  >
    <div class="p-profile">
      <div v-if="form.errors.staffIds" class="p-profile__error">
        <Alert :text="form.errors.staffIds" />
      </div>
      <div class="p-profile__staff-name">
        <FormGroupInput id="staff-name" label="管理者スタッフ名" v-model="form.name" :error="form.errors.name" />
      </div>
      <Divider />
      <Heading2 text="スタッフ一覧" />
      <Divider />
      <ul v-if="computedConnectedStaffList.length" class="c-staff-connected-list">
        <li v-for="(item, index) of computedConnectedStaffList" :key="index" class="c-staff-connected-list__item">
          <div class="c-staff-connected-list__image">
            <IconImage :src="item.src || NoImageUser" :width="70" :height="70" />
          </div>
          <div class="c-staff-connected-list__detail">
            <Paragraph class="c-staff-connected-list__detail-role">一般スタッフ</Paragraph>
            <Paragraph class="c-staff-connected-list__detail-name">{{ item.staffName }}</Paragraph>
          </div>
          <button type="button" class="c-staff-connected-list__button" @click="deleteStaff(item.staffId)">解除</button>
        </li>
      </ul>
      <EmptySection v-else text="連携スタッフが選択されていません。" />
      <Divider />
      <div class="p-profile__buttons">
        <Button class="p-profile__buttons-add" text="連携スタッフ追加" @click="setIsModal(true)" />
        <Button class="p-profile__buttons-register" text="登録" :is-loading="form.processing" @click="onSubmit" />
      </div>
    </div>
    <Divider />
    <AnkerSettingListItem
      text="メールアドレス変更"
      :href="route(RouteName.BusinessOperatorStaffAdminChangeEmailShow, { adminStaffId: props.adminStaffId })"
    />
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
  </BaseLayout>
</template>

<style lang="scss">
.p-profile {
  &__error {
    padding: 16px;
  }
  &__staff-name {
    padding: 24px 16px;
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

.c-staff-connected-list {
  &__item {
    display: flex;
    padding: 16px;
    & + .c-staff-connected-list__item {
      border-top: solid 1px #dddddd;
    }
  }
  &__image {
    margin-right: 20px;
  }
  &__detail {
    flex: 1;
    width: 100%;
    display: inline-flex;
    flex-direction: column;
    justify-content: center;
    &-role {
      margin-bottom: 4px;
    }
    &-name {
      font-size: 20px;
    }
  }
  &__button {
    align-self: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 30px;
    border-radius: 4px;
    font-size: 14px;
    border: solid 1px;
    box-sizing: border-box;
    text-align: center;
    background-color: var(--black);
    color: var(--white);
  }
}
</style>
