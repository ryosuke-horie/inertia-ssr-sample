<script setup lang="ts">
import { RouteName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import BaseLayout from '@/Layouts/BaseLayout.vue';
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :header-menus="[
      { text: 'チアペイとは', href: route(RouteName.Welcome), hrefId: 'about' },
      { text: '導入店舗', href: route(RouteName.Welcome), hrefId: 'pickup' },
      { text: '特徴', href: route(RouteName.Welcome), hrefId: 'feature' },
      { text: '使い方', href: route(RouteName.Welcome), hrefId: 'use' },
    ]"
  >
    <div class="p-user-cancel-complete">
      <Paragraph class="p-user-cancel-complete__title">退会が完了しました</Paragraph>
      <Paragraph class="p-user-cancel-complete__content">
        これまでチアペイをご利用いただきまして<br />誠にありがとうございました。
      </Paragraph>
      <AnkerButton
        :href="route(RouteName.Welcome)"
        variant="secondary"
        text="チアペイトップ"
        :is-outlined="false"
        class="p-user-cancel-complete__button"
      />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-cancel-complete {
  padding: 20px 30px;
  text-align: center;
  &__title {
    padding-bottom: 20px;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.015em;
  }
  &__content {
    font-size: 16px;
    font-weight: 400;
    letter-spacing: -0.015em;
    padding-bottom: 20px;
  }
}
</style>
