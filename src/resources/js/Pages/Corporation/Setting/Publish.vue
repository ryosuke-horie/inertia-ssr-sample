<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import FormToggle from '@/Components/atom/form/FormToggle/FormToggle.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { CORPORATIONApi } from '@/api';
import { configuration } from '@/lib/configuration';
import { ref } from 'vue';

type ListItem = {
  settingId: number;
  businessName: string;
  isPublish: boolean;
};

const props = defineProps<{
  list: ListItem[];
}>();

const data = ref<ListItem[]>(props.list);

const onChange = async (index: number): Promise<void> => {
  console.log(data.value[index].isPublish);

  await new CORPORATIONApi(configuration)
    .putCorporationSettingUpdatePublish({
      settingId: data.value[index].settingId,
      isPublish: data.value[index].isPublish,
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
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: '公開設定',
      href: RouteName.CorporationSetting,
    }"
  >
    <div class="p-corporation-setting-publish">
      <div v-for="(item, index) in data" :key="index" propsclass="p-corporation-setting-publish__container">
        <div class="p-corporation-setting-publish__container__content">
          <div class="p-corporation-setting-publish__container__content__title">{{ item.businessName }}</div>
          <FormToggle :id="item.businessName" v-model="item.isPublish" @change="onChange(index)" />
        </div>
        <Divider class="p-corporation-setting-publish__divider" />
      </div>
    </div>
  </BaseLayout>
</template>
<style lang="scss">
.p-corporation-setting-publish {
  padding: 5px 30px;
  height: 100vh;
  &__container {
    &__content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
      &__title {
        font-size: 18px;
        font-weight: var(--bold);
      }
    }
  }
  &__divider {
    margin: 0px -30px;
  }
}
</style>
