<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { ref } from 'vue';
import FormToggle from '@/Components/atom/form/FormToggle/FormToggle.vue';
import { GUESTApi } from '@/api';
import { configuration } from '@/lib/configuration';

const props = defineProps<{ isShowRanking: boolean }>();

const isShowRanking = ref(props.isShowRanking);
const onChangeIsShowRanking = async () => {
  await new GUESTApi(configuration).postProfileSettingShowRanking({ isShowRanking: isShowRanking.value });
};
</script>

<template>
  <BaseLayout
    title="ランキング表示設定"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'ランキング表示設定',
      href: RouteName.UserProfileShow,
    }"
  >
    <div class="p-profile-edit-setting">
      <div class="p-profile-edit-setting__item">
        <div class="p-profile-edit-setting__item-title">ランキング表示</div>
        <FormToggle id="push-notification" v-model="isShowRanking" @change="onChangeIsShowRanking" />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-profile-edit-setting {
  padding: 24px 16px;
  &__item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    &-title {
      font-size: 18px;
      font-weight: var(--bold);
    }
  }
}
</style>
