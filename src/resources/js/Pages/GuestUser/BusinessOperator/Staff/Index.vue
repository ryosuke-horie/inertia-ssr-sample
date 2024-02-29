<script lang="ts" setup>
// Vue関連のインポート
import { ref, reactive, computed, watch } from 'vue';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
// 型定義
import { type UserStaffListItemProps } from '@/Components/molecules/list/UserStaffListItem/type';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Button from '@/Components/atom/button/Button/Button.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
// 組織的コンポーネント
import UserStaffListItem from '@/Components/molecules/list/UserStaffListItem/UserStaffListItem.vue';

// 型宣言
type BusinessOperatorStaffListItemProps = {
  UserStaffDetailListItem: UserStaffListItemProps[];
  businessId: number;
  staffId: number;
  staffName: string;
  todayShiftStatus: number;
  images: { image: string; order: number }[];
};

const props = defineProps<{
  businessId: number;
  staffList: BusinessOperatorStaffListItemProps[];
}>();

// 検索フォームの状態管理
const search = reactive({ staffName: '' });
const searchStaffList = ref<BusinessOperatorStaffListItemProps[]>(props.staffList);

const offset = ref(10); // 初期表示件数
const incrementAmount = 10; // クリックごとに増やす件数

// スタッフ名で検索する
const computedStaffList = computed(() => {
  const searchQuery = search.staffName.toLowerCase();
  return searchStaffList.value.filter((staff) => staff.staffName.toLowerCase().includes(searchQuery));
});

// 「もっと見る」ボタンの表示制御
const computedIsButton = computed(() => computedStaffList.value.length > offset.value);

// 表示するスタッフリスト
const visibleStaffList = computed(() => computedStaffList.value.slice(0, offset.value));

// オフセットを増やす
const onIncrementOffset = () => {
  offset.value += incrementAmount;
};

// 検索条件が変更されたらオフセットをリセット
watch(
  search,
  () => {
    offset.value = incrementAmount; // 表示件数を初期値にリセット
  },
  { deep: true },
);
</script>

<template>
  <BaseLayout
    title="スタッフ一覧"
    :is-auth="false"
    :role="RouteRoleName.GuestUser"
    :auth-header="{
      role: RouteRoleName.GuestUser,
      text: 'スタッフ一覧',
      href: RouteName.GuestUserBusinessOperatorShow,
      params: { businessId: props.businessId },
    }"
  >
    <div class="p-user-business-operator-staff">
      <Heading2 text="キーワードから探す" />
      <Divider />
      <ColorSection>
        <FormInput
          id="search-staff-name"
          type="text"
          v-model="search.staffName"
          class="p-user-business-operator-staff__input-search"
        />
      </ColorSection>
      <ul class="p-user-business-operator-staff__staff-list">
        <UserStaffListItem
          v-for="(staff, index) in visibleStaffList"
          :key="index"
          :href="
            route(RouteName.GuestUserBusinessOperatorStaffShow, {
              staffId: staff.staffId,
              businessId: props.businessId,
            })
          "
          :staff-id="staff.staffId"
          :staff="staff"
          :staff-name="staff.staffName"
          :today-shift-status="staff.todayShiftStatus"
          :images="staff.images"
          :is-guest-user="true"
        />
      </ul>
      <div class="p-user-business-operator-staff__add">
        <Button v-if="computedIsButton" @click="onIncrementOffset" text="もっと見る" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-business-operator-staff {
  &__input-search {
    background-color: #ffffff;
    width: 100%;
  }
  &__add {
    display: flex;
    flex-direction: column;
    padding: 16px;
  }
}
</style>
