<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <BaseLayout
    title="各種設定"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '各種設定',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-setting-space"></div>
    <Link :href="route(RouteName.BusinessOperatorSettingPublish)" class="p-setting">
      <AnkerButton class="p-setting__container">
        <Paragraph>公開設定</Paragraph>
        <Paragraph>＞</Paragraph>
      </AnkerButton>
      <Divider />
    </Link>
    <Link :href="route(RouteName.BusinessOperatorTransferApplication)" class="p-setting">
      <AnkerButton class="p-setting__container">
        <Paragraph>自動振込申請</Paragraph>
        <Paragraph>＞</Paragraph>
      </AnkerButton>
      <Divider />
    </Link>
    <Link :href="route(RouteName.BusinessOperatorSettingTip)" class="p-setting">
      <AnkerButton class="p-setting__container">
        <Paragraph>投げ銭設定</Paragraph>
        <Paragraph>＞</Paragraph>
      </AnkerButton>
      <Divider />
    </Link>
    <Link :href="route(RouteName.BusinessOperatorSettingReviewPublish)" class="p-setting">
      <AnkerButton class="p-setting__container">
        <Paragraph>口コミ公開設定</Paragraph>
        <Paragraph>＞</Paragraph>
      </AnkerButton>
      <Divider />
    </Link>
    <Link :href="route(RouteName.BusinessOperatorSettingPublish)" class="p-setting">
      <AnkerButton class="p-setting__container">
        <Paragraph>ランキング公開設定</Paragraph>
        <Paragraph>＞</Paragraph>
      </AnkerButton>
      <Divider />
    </Link>
  </BaseLayout>
</template>

<style lang="scss">
.p-setting {
  &__container {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    font-weight: 700;
    line-height: 18px;
    letter-spacing: -0.015em;
    padding: 20px 25px;
  }
}
.p-setting-space {
  width: 100%;
  height: 35px;
  background-color: #eeeeee;
}
</style>
