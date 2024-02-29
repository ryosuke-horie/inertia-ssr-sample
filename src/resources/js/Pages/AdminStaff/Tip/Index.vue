<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import TipListItem from '@/Components/molecules/list/TipListItem/TipListItem.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import FormSelectBox from '@/Components/atom/form/FormSelectBox/FormSelectBox.vue';
import { ResponseTips } from '@/api';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';
import { ref, computed } from 'vue';

enum FilterTypeEnum {
  All = '',
  NotRead = 'notRead',
  NotResponse = 'notResponse',
}

const props = defineProps<ResponseTips>();

const selectFilterType = ref<FilterTypeEnum>(FilterTypeEnum.All);

const notReadLength = computed<number>(() => props.userTips.filter((tip) => !tip.isRead).length);
const notResponseLength = computed<number>(
  () => props.userTips.filter((tip) => !tip.isResponse && tip.userId !== 1).length,
);

const userTips = computed<ResponseTips['userTips']>(() => {
  if (selectFilterType.value === FilterTypeEnum.NotRead) {
    return props.userTips.filter((tip) => !tip.isRead);
  }

  if (selectFilterType.value === FilterTypeEnum.NotResponse) {
    return props.userTips.filter((tip) => !tip.isResponse && tip.userId !== 1);
  }

  return props.userTips;
});

const emptyMessage = computed<string>(() => {
  let message = '応援履歴';
  switch (selectFilterType.value) {
    case FilterTypeEnum.NotRead:
      message = '未読';
      break;
    case FilterTypeEnum.NotResponse:
      message = '未返信';
      break;
  }

  return message + 'はありません。';
});
</script>

<template>
  <BaseLayout
    title="スタッフ応援履歴一覧"
    :is-auth="true"
    :role="RouteRoleName.AdminStaff"
    :auth-header="{
      role: RouteRoleName.AdminStaff,
      text: 'スタッフ応援履歴',
      href: RouteName.AdminStaffMyPage,
    }"
  >
    <div class="p-point-history">
      <ColorSection>
        <FormSelectBox
          class="form"
          id="filter-type"
          v-model="selectFilterType"
          :options="[
            { value: FilterTypeEnum.All, label: `全件（${props.userTips.length}件）` },
            { value: FilterTypeEnum.NotRead, label: `未読（${notReadLength}件）` },
            { value: FilterTypeEnum.NotResponse, label: `未返信（${notResponseLength}件）` },
          ]"
        />
      </ColorSection>
      <Heading2 text="メッセージ一覧" />
      <Divider />
      <ul v-if="userTips.length">
        <TipListItem
          v-for="(item, index) of userTips"
          :key="item.userName + index"
          :href="route(RouteName.AdminStaffTipsShow, { tipId: item.tipId })"
          :src="item.image || NoImageUser"
          :point="item.points"
          :name="item.userName"
          :date="item.createdAt"
          :is-read="item.isRead"
          :is-reponse="item.isResponse || item.userId === 1"
        />
      </ul>
      <EmptySection v-else :text="emptyMessage" />
      <Divider />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-point-history {
  .form {
    width: 100%;
    background-color: var(--white);
  }
}
</style>
