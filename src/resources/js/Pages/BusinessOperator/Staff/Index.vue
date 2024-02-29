<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { ref, reactive, computed } from 'vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import { BUSINESSOPERATORApi } from '@/api';
import { configuration } from '@/lib/configuration';
import StaffListItem from '@/Components/molecules/list/StaffListItem/StaffListItem.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import AnkerTabs from '@/Components/molecules/tabs/AnkerTabs/AnkerTabs.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import StaffDeleteModal from '@/Components/molecules/modal/StaffDeleteModal/StaffDeleteModal.vue';
import FlashMessage from '@/Components/molecules/flash/FlashMessage/FlashMessage.vue';
import { PageProps } from '@/index';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

type StaffListItemProps = {
  staffId: number;
  isAdminStaff: boolean;
  staffName: string;
  src: string;
};

const props = defineProps<{ staffList: StaffListItemProps[] }>();
const flashMessages = ref<PageProps['flash'][]>([]);
const staffList = ref<StaffListItemProps[]>(props.staffList);
const selectStaffId = ref<number | null>(null);
const search = reactive({ staffName: '', staffRole: '' });
const isLoading = ref(false);

const computedStaffList = computed<StaffListItemProps[]>(() => {
  const staffName = search.staffName.toLowerCase();
  return staffList.value.filter((staff) => {
    return staff.staffName.toLowerCase().includes(staffName);
  });
});

const getStaffName = (id: number): string => {
  const staff = staffList.value.find((staff) => staff.staffId === id);
  return staff?.staffName || '';
};

const setSelectStaffId = (val: number | null): void => {
  selectStaffId.value = val;
};

const onDeleteStaff = async (staffId: number): Promise<void> => {
  if (!selectStaffId.value) return;
  if (isLoading.value) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .deleteBusinessOperatorStaffStaffId(staffId)
    .then(() => {
      staffList.value = staffList.value.filter((staff) => staff.staffId !== staffId);
      flashMessages.value.push({ type: 'success', text: '削除が完了しました' });
    })
    .catch(() => {
      flashMessages.value.push({ type: 'failed', text: '予期せぬエラーが発生しました。もう一度お試しください。' });
    })
    .finally(() => {
      setSelectStaffId(null);
      isLoading.value = false;
    });
};
</script>
<template>
  <template v-for="(item, index) of flashMessages" :key="item + index">
    <FlashMessage v-if="item" :message="item.text" :variant="item.type" />
  </template>
  <BaseLayout
    title="スタッフ管理"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'スタッフ管理',
      href: RouteName.BusinessOperatorStaffSelect,
    }"
  >
    <LoadingModal v-if="isLoading" />
    <div class="p-business-operator-staff-index">
      <Heading2 text="スタッフ一覧" />
      <Divider />
      <ColorSection>
        <FormInput
          id="search-staff-name"
          type="text"
          v-model="search.staffName"
          class="p-business-operator-staff-index__input-search"
        />
      </ColorSection>
      <AnkerTabs
        :tabs="[
          { href: route(RouteName.BusinessOperatorStaff), text: '一般スタッフ', isActive: true },
          { href: route(RouteName.BusinessOperatorStaffAdmin), text: '管理者スタッフ', isActive: false },
        ]"
      />
      <ul v-if="computedStaffList.length" class="p-business-operator-staff-index__staff">
        <StaffListItem
          v-for="(item, index) of computedStaffList"
          :key="index"
          :src="item.src || NoImageUser"
          :name="item.staffName"
          role="一般スタッフ"
          :href="route(RouteName.BusinessOperatorStaffShow, { staffId: item.staffId })"
          @delete="setSelectStaffId(item.staffId)"
        />
      </ul>
      <EmptySection v-else text="スタッフが登録されていません。" />
      <Divider />
      <ColorSection>
        <AnkerButton
          text="スタッフ登録"
          :href="route(RouteName.BusinessOperatorStaffAddShow)"
          class="p-business-operator-staff-index__register-staff"
        />
      </ColorSection>
    </div>
    <StaffDeleteModal
      v-if="selectStaffId"
      :name="getStaffName(selectStaffId)"
      @close="setSelectStaffId(null)"
      @submit="onDeleteStaff(selectStaffId)"
    />
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-staff-index {
  &__input-search {
    background-color: var(--white);
    width: 100%;
  }
  &__select-search {
    height: 40px;
  }
  &__register-staff {
    background-color: #fff !important;
    color: #1d9bf0 !important;
    border-color: #fff !important;
  }
}
</style>
