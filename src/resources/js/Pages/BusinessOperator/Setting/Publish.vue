<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import FormToggle from '@/Components/atom/form/FormToggle/FormToggle.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import { BUSINESSOPERATORApi, PutBusinessOperatorSettingUpdatePublishRequest } from '@/api';
import { configuration } from '@/lib/configuration';

const props = defineProps<{ settingId: number; isPublish: boolean }>();

const form = useForm<PutBusinessOperatorSettingUpdatePublishRequest>({
  settingId: props.settingId,
  isPublish: props.isPublish,
});

watch(form, () => {
  updatePublish();
});

const updatePublish = async (): Promise<void> => {
  await new BUSINESSOPERATORApi(configuration)
    .putBusinessOperatorSettingUpdatePublish({
      settingId: form.settingId,
      isPublish: form.isPublish,
    })
    .catch(() => {
      alert('予期せぬエラーが発生しました。');
    })
    .then((res) => {
      console.log(res);
    });
};
</script>

<template>
  <BaseLayout
    title="公開設定"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '公開設定',
      href: RouteName.BusinessOperatorSetting,
    }"
  >
    <div class="p-business-operator-transfer-application">
      <div class="p-business-operator-transfer-application__item">
        <div class="p-business-operator-transfer-application__item__title">公開設定</div>
        <FormToggle id="push-notification" v-model="form.isPublish" />
      </div>
      <Divider class="p-business-operator-transfer-application__divider" />
      <Paragraph class="p-business-operator-transfer-application__annotation"> </Paragraph>
    </div>
  </BaseLayout>
</template>
<style lang="scss">
.p-business-operator-transfer-application {
  padding: 5px 30px;
  height: 100vh;
  &__item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
    &__title {
      font-size: 18px;
      font-weight: var(--bold);
    }
    &__auto-apply-amount {
      width: 120px;
      height: 40px;
      margin-left: -60px;
      background-color: #fff !important;
    }
  }
  &__divider {
    margin: 0px -30px;
  }
  &__annotation {
    font-size: 13px;
    font-weight: 500;
    line-height: 18px;
    letter-spacing: -0.015em;
    padding: 30px 0px;
  }
  &__button {
    width: 100%;
  }
}
</style>
