<script lang="ts" setup>
// Vue関連のインポート
import { ref } from 'vue';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
// 型定義
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import { SwiperStaffListItemPorps } from '@/Components/molecules/list/SwiperStaffListItem/type';
import { BusinessReviewProps } from '@/Components/organizms/businessReview/businessReview/type';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Divider from '@/Components/atom/divider/Divider.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import Link from '@/Components/atom/link/Link/Link.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
// 組織的コンポーネント
import SwiperBusinessOperatorImage from '@/Components/organizms/swiper/SwiperBusinessOperatorImage/SwiperBusinessOperatorImage.vue';
import SwiperFavoriteStaffList from '@/Components/organizms/swiper/SwiperFavoriteStaffList/SwiperFavoriteStaffList.vue';
import BusinessReview from '@/Components/organizms/businessReview/businessReview/BusinessReview.vue';
import { Prefecture } from '@/Enums/Prefecture';

// 都道府県名を取得
function getPrefectureNameByValue(value: number): string | undefined {
  const entries = Object.entries(Prefecture) as [string, Prefecture][];
  for (const entry of entries) {
    const prefecture = entry[1];
    if (prefecture.value === value) {
      return prefecture.name;
    }
  }
  return undefined;
}

// 型定義
type BusinessOperatorItemProps = {
  businessName: string;
  businessDescription: string;
  city: string;
  prefCode: string;
  businessForm: string;
  images: { image: string; order: number }[];
};

const props = defineProps<{
  businessId: number;
  userId: number;
  businessOperator: BusinessOperatorItemProps;
  staff: SwiperStaffListItemPorps[];
  businessReviews: BusinessReviewProps[];
}>();

// 口コミ用処理
const localReviews = ref([...props.businessReviews]);
const isLoading = ref(false);
</script>

<template>
  <BaseLayout
    title="事業者詳細"
    :is-auth="false"
    :role="RouteRoleName.GuestUser"
    :auth-header="{
      role: RouteRoleName.GuestUser,
      text: '事業者詳細',
      href: RouteName.GuestUserBusinessOperator,
    }"
  >
    <LoadingModal v-if="isLoading" />
    <div class="p-user-business-operator-show">
      <SwiperBusinessOperatorImage :images="props.businessOperator.images" />
      <div class="p-user-business-operator-show__shop-contents">
        <div class="p-user-business-operator-show__location-business-container">
          <p class="p-user-business-operator-show__prefecture">
            {{ getPrefectureNameByValue(Number(props.businessOperator.prefCode)) }}
          </p>
          <p class="p-user-business-operator-show__business-form">{{ props.businessOperator.businessForm }}</p>
        </div>
        <p class="p-user-business-operator-show__business-name">{{ props.businessOperator.businessName }}</p>

        <div
          v-if="props.businessOperator.businessDescription"
          class="p-user-business-operator-show__business-description"
        >
          <Divider />
          <p>
            {{ props.businessOperator.businessDescription }}
          </p>
        </div>
      </div>

      <Divider />
      <Heading2 text="スタッフ" />
      <Divider />

      <!-- 所属スタッフ -->
      <SwiperFavoriteStaffList :list="props.staff" :is-guest-user="true" />
      <AnkerButton
        :variant="ButtonVariantProp.Derk"
        class="p-user-business-operator-show__staff-button"
        :href="route(RouteName.GuestUserBusinessOperatorStaff, { businessId: props.businessId })"
        text="在籍スタッフ一覧"
      />

      <Divider />
      <Heading2 text="応援メッセージ" />
      <Divider />

      <!-- 口コミ -->
      <BusinessReview :business-reviews="localReviews" :user-id="props.userId" />
      <AnkerButton
        :variant="ButtonVariantProp.Success"
        class="p-user-business-operator-show__review-button"
        :href="route(RouteName.GuestUserBusinessOperatorCreateReview, { businessId })"
        text="口コミを送る"
      />
      <Link
        class="p-user-business-operator-show__review-link"
        :href="RouteName.ReviewGuideLine"
        text="口コミに関するガイドライン"
      />

      <Divider />
      <Heading2 text="基本情報" />
      <Divider />

      <!-- 基本情報 -->
      <div class="p-user-business-operator-show__shop-info">
        <div class="p-user-business-operator-show__shop-info-item">
          <b class="p-user-business-operator-show__shop-info-title">住所</b>
          <p class="p-user-business-operator-show__shop-info-content">
            {{ getPrefectureNameByValue(Number(props.businessOperator.prefCode)) }}{{ props.businessOperator.city }}
          </p>
        </div>
        <Divider />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-business-operator-show {
  &__input-search {
    background-color: #ffffff;
    width: 100%;
  }
  &__shop-contents {
    margin: 5px 20px 21px 20px;
  }
  &__location-business-container {
    display: flex;
  }
  &__prefecture {
    margin-right: 10px;
  }
  &__business {
    &-name {
      font-size: 25px;
      margin-bottom: 10px;
    }
    &-description p {
      margin-top: 20px;
      font-weight: bold;
    }
  }
  &__staff {
    &-name {
      color: var(--black);
    }
    &-button {
      margin: 10px 20px 40px 20px;
    }
  }
  &__review {
    &-button {
      margin: 20px;
    }
    &-link {
      display: block;
      margin-bottom: 20px;
      margin-right: 20px;
      text-align: right;
    }
  }
  &__shop {
    &-info {
      &-item {
        display: flex;
        margin: 30px 16px;
        gap: 100px;
      }
    }
  }
}
</style>
