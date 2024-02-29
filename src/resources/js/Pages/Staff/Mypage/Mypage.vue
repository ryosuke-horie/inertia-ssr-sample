<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { Link } from '@inertiajs/vue3';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import NotificationListItem from '@/Components/molecules/list/NotificationListItem/NotificationListItem.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import MypageAnkerButtonList from '@/Components/organizms/list/MypageAnkerButtonList/MypageAnkerButtonList.vue';
import EmptySection from '@/Components/atom/section/EmptySection/EmptySection.vue';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

const props = defineProps<{
  staffName: string;
  staffImage: string | null;
  businessName: string;
  favorites: number;
  notifications: {
    notificationId: number;
    isRead: boolean;
    title: string;
    startAt: string;
    url: string;
    fileType: string;
    fileName: string;
    privateFlag: boolean;
  }[];
}>();

const getRouteName = (flag: boolean, fileType: string): RouteName => {
  if (flag) {
    return RouteName.StaffNotificationShowPrivate;
  }

  if (fileType === 'pdf') {
    return RouteName.StaffNotificationPdf;
  }

  return RouteName.StaffNotificationShow;
};
</script>

<template>
  <BaseLayout
    title="マイページ"
    :is-auth="true"
    :role="RouteRoleName.Staff"
    :auth-header="{
      text: 'マイページ',
    }"
  >
    <div class="p-staff-mypage">
      <div class="p-staff-mypage__profile">
        <div class="p-staff-mypage__profile-image">
          <IconImage :src="props.staffImage || NoImageUser" :width="50" :height="50" />
        </div>
        <div class="p-staff-mypage__profile-detail">
          <div class="p-staff-mypage__profile-detail-shop">{{ props.businessName }}</div>
          <div class="p-staff-mypage__profile-detail-staff">{{ props.staffName }} 様</div>
          <div class="p-staff-mypage__profile-detail-favorite">
            <Link class="p-staff-mypage__profile-detail-favorite-link" :href="route(RouteName.StaffLike)">
              <i class="icon fa-regular fa-thumbs-up" />いいね！：{{ props.favorites.toLocaleString() }}
            </Link>
          </div>
        </div>
      </div>
      <Divider />
      <ColorSection class="p-staff-mypage__buttons">
        <MypageAnkerButtonList
          :list="[
            { text: 'ポイント', icon: 'fa-solid fa-coins', href: route(RouteName.StaffPointHistory) },
            { text: 'プロフィール', icon: 'fa-solid fa-user', href: route(RouteName.StaffProfileShow) },
            { text: 'ギフト履歴', icon: 'fa-solid fa-bullhorn', href: route(RouteName.StaffTips) },
            { text: 'ランキング', icon: 'fa-solid fa-crown', href: route(RouteName.StaffPointHistory) },
          ]"
        />
      </ColorSection>
      <Divider />
      <div class="p-staff-mypage__notification">
        <Heading2 text="運営からのお知らせ" />
        <Divider />
        <ul v-if="props.notifications.length" class="p-staff-mypage__notification-content">
          <NotificationListItem
            class="p-staff-mypage__notification-item"
            v-for="(item, index) of props.notifications"
            :key="item.title + index"
            :is-read="item.isRead"
            :title="item.title"
            :start-at="item.startAt"
            :url="route(getRouteName(item.privateFlag, item.fileType), { notificationId: item.notificationId })"
          />
        </ul>
        <EmptySection v-else text="運営からのお知らせはまだありません。" />
        <Divider />
        <div class="p-staff-mypage__notification-button">
          <AnkerButton variant="derk" :href="route(RouteName.StaffNotification)" text="お知らせ一覧" />
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-staff-mypage {
  &__profile {
    display: flex;
    padding: 16px;
    &-detail {
      margin-left: 16px;
      &-shop {
        font-size: 11px;
      }
      &-staff {
        font-size: 14px;
        margin-bottom: 4px;
        font-weight: var(--bold);
      }
      &-favorite {
        &-link {
          display: inline-flex;
          font-size: 14px;
          border-radius: 5px;
          border: solid 1px #ccc;
          padding: 2px 8px;
          color: inherit;
        }
        .icon {
          font-size: 18px;
          margin-right: 5px;
        }
        span {
          margin-left: 4px;
        }
      }
    }
  }
  &__notification {
    display: flex;
    flex-direction: column;
    &-button {
      padding: 20px;
    }
  }
}
</style>
