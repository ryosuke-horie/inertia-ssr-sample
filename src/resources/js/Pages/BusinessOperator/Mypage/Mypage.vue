<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import type { MypageProps } from './type';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import NotificationListItem from '@/Components/molecules/list/NotificationListItem/NotificationListItem.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import MypageAnkerButtonList from '@/Components/organizms/list/MypageAnkerButtonList/MypageAnkerButtonList.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<MypageProps>();

const getRouteName = (flag: boolean, fileType: string): RouteName => {
  if (flag) {
    return RouteName.BusinessOperatorNotificationShowPrivate;
  }

  if (fileType === 'pdf') {
    return RouteName.BusinessOperatorPdf;
  }

  return RouteName.BusinessOperatorNotificationShow;
};
</script>

<template>
  <BaseLayout
    title="マイページ"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'マイページ',
    }"
  >
    <div class="p-business-operator-mypage">
      <div v-if="!props.isPublish" class="p-business-operator-mypage__publish">
        <Paragraph class="p-business-operator-mypage__publish__text">公開設定は「非公開」になっています</Paragraph>
        <Link :href="route(RouteName.BusinessOperatorSettingPublish)" class="p-business-operator-mypage__publish__link"
          >公開設定 ></Link
        >
      </div>
      <div class="p-business-operator-mypage__info">
        <IconImage :src="props.src" :width="60" :height="60" class="p-business-operator-mypage__info__icon" />
        <Paragraph class="p-business-operator-mypage__info__name">{{ props.businessName }}</Paragraph>
      </div>
      <divider />
      <ColorSection class="p-business-operator-mypage__">
        <MypageAnkerButtonList
          :list="[
            { text: 'ポイント', icon: 'fa-solid fa-coins', href: route(RouteName.BusinessOperatorPoint) },
            { text: '会員情報', icon: 'fa-solid fa-coins', href: route(RouteName.BusinessOperatorProfileEdit) },
            { text: '振込情報', icon: 'fa-solid fa-coins', href: route(RouteName.BusinessOperatorTransferSelect) },
            { text: 'スタッフ', icon: 'fa-solid fa-coins', href: route(RouteName.BusinessOperatorStaffSelect) },
            { text: 'QRコード', icon: 'fa-solid fa-coins', href: route(RouteName.BusinessOperatorQR) },
            { text: '各種設定', icon: 'fa-solid fa-coins', href: route(RouteName.BusinessOperatorSetting) },
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
      <div class="p-business-operator-mypage__notification-button">
        <AnkerButton
          :href="route(RouteName.BusinessOperatorNotification)"
          variant="secondary"
          text="お知らせ一覧"
          class=""
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-mypage {
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
