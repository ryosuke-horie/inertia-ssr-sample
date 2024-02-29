<script setup lang="ts">
// Vue関連のインポート
import { defineProps, ref, reactive, computed, watch } from 'vue';
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
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
// 組織的コンポーネント
import UserStaffListItem from '@/Components/molecules/list/UserStaffListItem/UserStaffListItem.vue';
// API
import { GUESTApi } from '@/api';
import { configuration } from '@/lib/configuration';

// 型定義
type SwiperStaffListItemProps = {
  UserStaffDetailListItem: UserStaffListItemProps[];
  businessId: number;
  staffId: number;
  favoriteId: number | null;
  staffName: string;
  todayShiftStatus: number;
  staffProfileImages: { image: string; order: number }[];
};

const props = defineProps<{
  staffList: SwiperStaffListItemProps[];
}>();

// 検索フォームの状態管理
const search = reactive({ staffName: '' });
const searchStaffList = ref<SwiperStaffListItemProps[]>(props.staffList);
const isLoading = ref(false);

// スタッフ名で検索する計算プロパティ
const computedStaffList = computed(() => {
  const searchQuery = search.staffName.toLowerCase();
  return searchStaffList.value.filter((staff) => staff.staffName.toLowerCase().includes(searchQuery));
});

const offset = ref(10); // 初期表示件数
const incrementAmount = 10; // クリックごとに増やす件数

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

// お気に入りの切り替え
const onToggleFavorite = async (staff: SwiperStaffListItemProps): Promise<void> => {
  if (isLoading.value) return;
  isLoading.value = true;
  await new GUESTApi(configuration)
    .toggleFavorite(staff.staffId, staff.favoriteId)
    .then((res) => {
      staff.favoriteId = typeof res.data === 'object' ? null : res.data;
      searchStaffList.value = searchStaffList.value.filter((item) => item.favoriteId !== staff.favoriteId);
    })
    .catch((e) => {
      console.error(e);
      alert('予期せぬエラーが発生しました。');
    })
    .finally(() => {
      isLoading.value = false;
    });
};
</script>

<template>
  <BaseLayout
    title="お気に入りスタッフ"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      role: RouteRoleName.User,
      text: 'お気に入りスタッフ',
      href: RouteName.UserMypage,
    }"
  >
    <LoadingModal v-if="isLoading" />
    <div class="p-user-favorite-staff">
      <Heading2 text="キーワードから探す" />
      <Divider />
      <ColorSection>
        <FormInput
          id="search-staff-name"
          type="text"
          v-model="search.staffName"
          class="p-user-favorite-staff__input-search"
        />
      </ColorSection>
      <ul class="p-user-favorite-staff__staff-list">
        <UserStaffListItem
          v-for="(staff, index) in visibleStaffList"
          :key="index"
          :href="
            route(RouteName.UserBusinessOperatorStaffShow, { staffId: staff.staffId, businessId: staff.businessId })
          "
          :staff-id="staff.staffId"
          :staff="staff"
          :favorite-id="staff.favoriteId"
          :staff-name="staff.staffName"
          :today-shift-status="staff.todayShiftStatus"
          :images="staff.staffProfileImages"
          :is-guest-user="false"
          @toggle-favorite="onToggleFavorite"
        />
      </ul>
      <div class="p-user-favorite-staff__add">
        <Button v-if="computedIsButton" @click="onIncrementOffset" text="もっと見る" />
      </div></div
  ></BaseLayout>
</template>

<style lang="scss">
.p-user-favorite-staff {
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
