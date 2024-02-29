<script lang="ts" setup>
// Vue関連のインポート
import { ref, reactive, computed, watch } from 'vue';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Button from '@/Components/atom/button/Button/Button.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import FormInput from '@/Components/atom/form/FormInput/FormInput.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
// 組織的コンポーネント
import BusinessOperatorList from '@/Components/organizms/list/BusinessOperatorList/BusinessOperatorList.vue';

// 型定義
type BusinessOperatorListItemProps = {
  id: number;
  name: string;
  city: string;
  images: { image: string; order: number }[];
  href: string;
};

const props = defineProps<{
  businessList: BusinessOperatorListItemProps[];
}>();

// 検索フォームの状態管理
const search = reactive({ businessName: '', businessCity: '' });
const businessList = ref<BusinessOperatorListItemProps[]>(props.businessList);

const offset = ref(10); // 初期表示件数
const incrementAmount = 10; // クリックごとに増やす件数

const computedBusinessList = computed(() => {
  const nameQuery = search.businessName.toLowerCase();
  const cityQuery = search.businessCity.toLowerCase();
  return businessList.value.filter((business) => {
    const isMatchBusinessName = business.name.toLowerCase().includes(nameQuery);
    const isMatchCity = cityQuery ? business.city.toLowerCase().includes(cityQuery) : true;
    return isMatchBusinessName && isMatchCity;
  });
});

const computedIsButton = computed(() => computedBusinessList.value.length > offset.value);

const computedVisibleBusinessList = computed(() => computedBusinessList.value.slice(0, offset.value));

const onIncrementOffset = () => {
  offset.value += incrementAmount;
};

watch(
  () => [search.businessName, search.businessCity],
  () => {
    offset.value = incrementAmount; // 表示件数を初期値にリセット
  },
  { deep: true },
);
</script>

<template>
  <BaseLayout
    title="事業者一覧"
    :is-auth="false"
    :role="RouteRoleName.GuestUser"
    :auth-header="{
      role: RouteRoleName.GuestUser,
      text: '事業者一覧',
      href: RouteName.Welcome,
    }"
  >
    <div class="p-user-business-operator">
      <Heading2 text="キーワードから探す" />
      <Divider />
      <ColorSection>
        <FormInput
          id="search-business-name"
          type="text"
          v-model="search.businessName"
          class="p-user-business-operator__input-search"
        />
      </ColorSection>
      <BusinessOperatorList :list="computedVisibleBusinessList" />
      <div class="p-user-business-operator__add">
        <Button v-if="computedIsButton" @click="onIncrementOffset" text="もっと見る" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-business-operator {
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
