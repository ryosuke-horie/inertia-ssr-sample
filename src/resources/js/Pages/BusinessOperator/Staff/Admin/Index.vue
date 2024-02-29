<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { ref, computed } from 'vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import { BUSINESSOPERATORApi } from '@/api';
import { configuration } from '@/lib/configuration';
import StaffListItem from '@/Components/molecules/list/StaffListItem/StaffListItem.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import AnkerTabs from '@/Components/molecules/tabs/AnkerTabs/AnkerTabs.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import StaffDeleteModal from '@/Components/molecules/modal/StaffDeleteModal/StaffDeleteModal.vue';
import FlashMessage from '@/Components/molecules/flash/FlashMessage/FlashMessage.vue';
import { PageProps } from '@/index';

type StaffListItemProps = {
  adminStaffId: number;
  name: string;
};

const props = defineProps<{ adminStaffList: StaffListItemProps[] }>();
const flashMessages = ref<PageProps['flash'][]>([]);
const adminStaffList = ref<StaffListItemProps[]>(props.adminStaffList);
const selectAdminStaffId = ref<number | null>(null);
const search = ref('');
const isLoading = ref(false);

const computedAdminStaffList = computed(() => {
  const adminStaffName = search.value.toLowerCase();
  return adminStaffList.value.filter((adminStaff) => {
    return adminStaff.name.toLowerCase().includes(adminStaffName);
  });
});

const getAdminStaffName = (id: number): string => {
  const adminStaff = adminStaffList.value.find((adminStaff) => adminStaff.adminStaffId === id);
  return adminStaff?.name || '';
};

const setSelectAdminStaffId = (val: number | null): void => {
  selectAdminStaffId.value = val;
};

const onDeleteAdminStaff = async (adminStaffId: number): Promise<void> => {
  if (!selectAdminStaffId.value) return;
  if (isLoading.value) return;
  isLoading.value = true;
  await new BUSINESSOPERATORApi(configuration)
    .deleteBusinessOperatorStaffAdminAdminStaffId(adminStaffId)
    .then(() => {
      adminStaffList.value = adminStaffList.value.filter((adminStaff) => adminStaff.adminStaffId !== adminStaffId);
      flashMessages.value.push({ type: 'success', text: '削除が完了しました' });
    })
    .catch(() => {
      flashMessages.value.push({ type: 'failed', text: '予期せぬエラーが発生しました。もう一度お試しください。' });
    })
    .finally(() => {
      setSelectAdminStaffId(null);
      isLoading.value = false;
    });
};
</script>
<template>
  <template v-for="(item, index) of flashMessages" :key="item + index">
    <FlashMessage v-if="item" :message="item.text" :variant="item.type" />
  </template>
  <BaseLayout
    title="管理者スタッフ管理"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '管理者スタッフ管理',
      href: RouteName.BusinessOperatorStaffSelect,
    }"
  >
    <div class="p-business-operator-staff">
      <LoadingModal v-if="isLoading" />
      <div class="p-business-operator-staff">
        <Heading2 text="管理者スタッフ一覧" />
        <Divider />
        <ColorSection>
          <FormInput
            id="search-staff-name"
            type="text"
            v-model="search"
            class="p-business-operator-staff__input-search"
          />
        </ColorSection>
        <AnkerTabs
          :tabs="[
            { href: route(RouteName.BusinessOperatorStaff), text: '一般スタッフ', isActive: false },
            { href: route(RouteName.BusinessOperatorStaffAdmin), text: '管理者スタッフ', isActive: true },
          ]"
        />
        <ul v-if="computedAdminStaffList.length" class="p-business-operator-staff__staff">
          <StaffListItem
            v-for="(item, index) of computedAdminStaffList"
            :key="index"
            :name="item.name"
            role="管理者スタッフ"
            :href="route(RouteName.BusinessOperatorStaffAdminShow, { adminStaffId: item.adminStaffId })"
            @delete="setSelectAdminStaffId(item.adminStaffId)"
          />
        </ul>
        <EmptySection v-else text="管理者スタッフが登録されていません。" />
        <ColorSection>
          <AnkerButton
            text="スタッフ管理者登録"
            :href="route(RouteName.BusinessOperatorStaffAdminAddShow)"
            class="p-business-operator-staff__register-admin-staff"
          />
        </ColorSection>
      </div>
    </div>
    <StaffDeleteModal
      v-if="selectAdminStaffId"
      :name="getAdminStaffName(selectAdminStaffId)"
      @close="setSelectAdminStaffId(null)"
      @submit="onDeleteAdminStaff(selectAdminStaffId)"
    />
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-staff {
  &__input-search {
    background-color: var(--white);
    width: 100%;
  }
  &__select-search {
    height: 40px;
  }
  &__register-admin-staff {
    background-color: #fff !important;
    color: #1d9bf0 !important;
    border-color: #fff !important;
  }
}
</style>
