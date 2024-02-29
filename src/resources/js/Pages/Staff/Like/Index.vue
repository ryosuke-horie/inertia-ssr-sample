<script lang="ts" setup>
import { computed } from 'vue';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import TotalCountSection from '@/Components/organizms/count/TotalCountSection/TotalCountSection.vue';
import IconNameList from '@/Components/organizms/list/IconNameList/IconNameList.vue';
import { IconNameListProps } from '@/Components/organizms/list/IconNameList/type';
import Divider from '@/Components/atom/divider/Divider.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import { ResponseLikes } from '@/api';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

const props = defineProps<ResponseLikes>();

const computedIconNameList = computed<IconNameListProps['list']>(() => {
  return props.likeList.map((item) => {
    return {
      src: item.image || NoImageUser,
      name: item.userName,
    };
  });
});
</script>

<template>
  <BaseLayout
    title="いいね"
    :is-auth="true"
    :role="RouteRoleName.Staff"
    :auth-header="{
      text: 'いいね',
      href: RouteName.StaffMyPage,
    }"
  >
    <div class="p-like">
      <Heading2 text="いいね！してくれたユーザー" />
      <Divider />
      <TotalCountSection :count="props.likeCount" label="合計" unit="人" />
      <Divider />
      <div v-if="computedIconNameList.length" class="p-like__list">
        <IconNameList :list="computedIconNameList" />
      </div>
      <EmptySection v-else text="「いいね」してくれたユーザーはまだいません。" />
      <Divider />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-like {
  &__list {
    padding: 24px;
  }
}
</style>
