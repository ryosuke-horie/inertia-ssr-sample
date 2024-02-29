<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { ref } from 'vue';
import FormToggle from '@/Components/atom/form/FormToggle/FormToggle.vue';
import { BUSINESSOPERATORApi, ResponseStaffProfileSetting } from '@/api';
import { configuration } from '@/lib/configuration';

const props = defineProps<ResponseStaffProfileSetting>();

const isMessageNotified = ref(props.isMessageNotified);
const onChangeMessageNotified = async () => {
  await new BUSINESSOPERATORApi(configuration).postBusinessOperatorStaffStaffIdSettingMessageNotified(props.staffId, {
    isMessageNotified: isMessageNotified.value,
  });
};
</script>

<template>
  <BaseLayout
    title="各種設定"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '各種設定',
      href: RouteName.BusinessOperatorStaffShow,
      params: {
        staffId: props.staffId,
      },
    }"
  >
    <div class="p-profile-edit-setting">
      <div class="p-profile-edit-setting__item">
        <div class="p-profile-edit-setting__item-title">プッシュ通知</div>
        <FormToggle id="push-notification" v-model="isMessageNotified" @change="onChangeMessageNotified" />
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
