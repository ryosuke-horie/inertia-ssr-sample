<script lang="ts" setup>
// Vue関連のインポート
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
import { getSessionStorageBase64 } from '@/Utilities/storage';
// 型定義
import type { MypageProps } from './type';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Image from '@/Components/atom/image/Image/Image.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';
// 組織的コンポーネント
import NotificationListItem from '@/Components/molecules/list/NotificationListItem/NotificationListItem.vue';
import MypageAnkerButtonList from '@/Components/organizms/list/MypageAnkerButtonList/MypageAnkerButtonList.vue';
import SwiperFavoriteStaffList from '@/Components/organizms/swiper/SwiperFavoriteStaffList/SwiperFavoriteStaffList.vue';

// ページプロパティの定義
const props = defineProps<MypageProps>();

// ゲストユーザーがsessionStorageに保存しているURLへリダイレクト
// 投げ銭からのリダイレクトのための処理
onMounted(() => {
  // sessionStorageからBase64文字列を取得
  const url = getSessionStorageBase64('guestRedirectUrl');

  if (url !== null) {
    const userUrl = url.replace('/guest', '');
    router.visit(userUrl);
  }
});

const getRouteName = (flag: boolean, fileType: string): RouteName => {
  if (flag) {
    return RouteName.UserNotificationShowPrivate;
  }

  if (fileType === 'pdf') {
    return RouteName.UserNotificationPdf;
  }

  return RouteName.UserNotificationShow;
};
</script>

<template>
  <BaseLayout
    title="チアペイ"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'マイページ',
    }"
  >
    <div class="p-user-mypage">
      <div class="p-user-mypage__user-info">
        <Image :src="props.userProfileImage ? props.userProfileImage : NoImageUser" alt="ユーザー画像" />
        <p>{{ props.nickname }} 様</p>
      </div>

      <ColorSection class="p-staff-mypage__buttons">
        <MypageAnkerButtonList
          :list="[
            { text: 'ポイント', icon: 'fa-solid fa-coins', href: route(RouteName.UserPointHistory) },
            { text: 'プロフィール', icon: 'fa-solid fa-user', href: route(RouteName.UserProfileShow) },
            { text: '応援履歴', icon: 'fa-solid fa-bullhorn', href: route(RouteName.UserTips) },
            { text: 'ランキング', icon: 'fa-solid fa-crown', href: route(RouteName.UserMypage) },
            { text: '支払い情報', icon: 'fa-regular fa-credit-card', href: route(RouteName.UserPaymentInfoShow) },
            { text: '退会', icon: 'fa-solid fa-door-open', href: route(RouteName.UserCancel) },
          ]"
        />
        <div class="p-user-mypage__anker-button-wrap">
          <AnkerButton
            :variant="ButtonVariantProp.Derk"
            icon="fa-solid fa-shop"
            :is-outlined="true"
            :href="route(RouteName.UserBusinessOperator)"
            class="p-user-mypage__button--shadow"
            text="ショップ一覧"
          />
          <AnkerButton
            :variant="ButtonVariantProp.Warning"
            :href="route(RouteName.UserPointCharge)"
            class="p-user-mypage__button--point-charge p-user-mypage__button--shadow"
            text="ポイントチャージ"
          />
        </div>
      </ColorSection>

      <div class="p-user-mypage__favorites">
        <Heading2 text="お気に入りスタッフ" />
        <Divider />
        <SwiperFavoriteStaffList :list="props.favoriteStaff" :is-guest-user="false" />
        <div class="p-user-mypage__favorite-staff-button">
          <AnkerButton
            :href="route(RouteName.UserFavoriteStaff)"
            :variant="ButtonVariantProp.Derk"
            text="お気に入りスタッフ一覧"
          />
        </div>
      </div>

      <div class="p-user-mypage__notification">
        <Heading2 text="運営からのお知らせ" />
        <Divider />
        <ul class="p-user-mypage__notification-content">
          <NotificationListItem
            class="p-user-mypage__notification-item"
            v-for="(item, index) of props.notifications"
            :key="item.title + index"
            :is-read="item.isRead"
            :title="item.title"
            :start-at="item.startAt"
            :is-guest-user="false"
            :url="route(getRouteName(item.privateFlag, item.fileType), { notificationId: item.notificationId })"
          />
        </ul>
        <div class="p-user-mypage__notification-button">
          <AnkerButton
            :variant="ButtonVariantProp.Derk"
            :href="route(RouteName.UserNotification)"
            text="お知らせ一覧"
          />
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-mypage {
  &__user-info {
    display: flex;
    align-items: center;
    margin: 20px;

    & img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }

    & p {
      margin-left: 10px;
      font-weight: bold;
    }
  }

  &__anker-button-wrap {
    display: flex;
    flex-direction: column;
    margin: 20px 0 10px 0;
  }

  &__button--point-charge {
    margin-top: 15px;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.05);
  }

  &__staff-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin: 10px 0 0 5px;
  }

  &__staff-name {
    font-size: 14px;
    font-weight: bold;
  }

  &__business-name {
    font-size: 12px;
    color: var(--gray);
  }

  &__notification-button {
    margin-top: 30px;
    padding: 0 20px;
  }

  &__contents__main-content {
    margin: 20px 15px;
  }

  &__flex__button-group {
    display: flex;
    justify-content: space-around;
    margin: 10px 0;
    gap: 10px;
  }

  &__favorite-staff-button {
    margin: 20px;
  }
}

.c-anker-button.p-user-mypage {
  &__button--shadow {
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.05);
    border: none;
    border-radius: 10px;

    & span i {
      color: #ffeb3b;
    }
  }
}
</style>
