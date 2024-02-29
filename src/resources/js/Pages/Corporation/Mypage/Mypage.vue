<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import type { MypageProps } from './type';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import NotificationListItem from '@/Components/molecules/list/NotificationListItem/NotificationListItem.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import MypageAnkerButtonList from '@/Components/organizms/list/MypageAnkerButtonList/MypageAnkerButtonList.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<MypageProps>();

const getRouteName = (flag: boolean, fileType: string): RouteName => {
  if (flag) {
    return RouteName.CorporationNotificationShowPrivate;
  }

  if (fileType === 'pdf') {
    return RouteName.CorporationPdf;
  }

  return RouteName.CorporationNotificationShow;
};
</script>

<template>
  <BaseLayout
    title="マイページ"
    :is-auth="true"
    :role="RouteRoleName.Corporation"
    :auth-header="{
      text: 'マイページ',
    }"
  >
    <div class="p-corporation-mypage">
      <div v-if="props.isPrivatePublish" class="p-corporation-mypage__publish">
        <Paragraph class="p-corporation-mypage__publish__text"
          >公開設定が「非公開」になっている事業者があります</Paragraph
        >
        <Link :href="route(RouteName.CorporationSettingPublish)" class="p-corporation-mypage__publish__link"
          >公開設定</Link
        >
      </div>
      <div class="p-corporation-mypage__info">
        <Paragraph class="p-corporation-mypage__info__name">{{ props.corporationName }} 様</Paragraph>
      </div>
      <divider />
      <ColorSection class="p-corporation-mypage__">
        <MypageAnkerButtonList
          :list="[
            { text: 'ポイント', icon: 'fa-solid fa-coins', href: route(RouteName.CorporationPoint) },
            { text: '会員情報', icon: 'fa-solid fa-coins', href: route(RouteName.CorporationProfileEdit) },
            { text: 'ショップ', icon: 'fa-solid fa-coins', href: route(RouteName.CorporationBusinessOperator) },
            { text: '振込情報', icon: 'fa-solid fa-coins', href: route(RouteName.CorporationTransferSelect) },
            { text: '各種設定', icon: 'fa-solid fa-coins', href: route(RouteName.CorporationSetting) },
          ]"
        />
      </ColorSection>
      <Heading2 text="運営からのお知らせ" />
      <Divider />
      <ul>
        <NotificationListItem
          v-for="(item, index) of props.notifications"
          :key="index"
          :is-read="item.isRead"
          :title="item.title"
          :start-at="item.startAt"
          :url="route(getRouteName(item.privateFlag, item.fileType), { notificationId: item.notificationId })"
        />
      </ul>
      <div class="p-corporation-mypage__notification-button">
        <AnkerButton
          :href="route(RouteName.CorporationNotification)"
          variant="secondary"
          text="お知らせ一覧"
          class=""
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-corporation-mypage {
  &__publish {
    background-color: #ff0000;
    display: flex;
    justify-content: space-between;
    padding: 10px 20px;
    align-items: center;
    &__text {
      color: white !important;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
    &__link {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: -0.015em;
      background-color: #ffffff;
      padding: 7px;
      border-radius: 5px;
      color: #333333;
      &:visited {
        color: #333333;
      }
    }
  }
  &__info {
    display: flex;
    align-items: center;
    padding: 20px;
    font-size: 20px;
    &__name {
      padding-left: 20px;
      font-size: 18px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
  }
  &__notification-button {
    margin-top: 30px;
    padding: 0 20px;
  }
}
</style>
