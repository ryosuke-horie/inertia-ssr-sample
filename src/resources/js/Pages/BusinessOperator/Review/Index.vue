<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import IconImage from '@/Components/atom/image/IconImage/IconImage.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  list: {
    date: {
      reviewId: number;
      src: string;
      nickname: string;
      comment: string;
    };
  }[];
}>();

const form = useForm<{ reviewId: number | string }>({
  reviewId: '',
});

const deleteReview = (reviewId: number) => {
  if (window.confirm('本当に削除しますか？')) {
    form.reviewId = reviewId;
    form.delete(route(RouteName.BusinessOperatorReviewDelete));
  }
};
</script>

<template>
  <BaseLayout
    title="口コミ管理"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '口コミ管理',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-business-operator-review">
      <Paragraph v-if="props.list.length == 0">口コミはありません</Paragraph>
      <div v-for="(item, date) in props.list" :key="date" class="p-business-operator-review__container">
        <div class="p-business-operator-review__container__date-container">
          <Paragraph class="p-business-operator-review__container__date-container__date">{{ date }}</Paragraph>
        </div>
        <div class="p-business-operator-review__container__list">
          <div v-for="(review, index) in item" :key="index" class="p-business-operator-review__container__list__user">
            <div class="p-business-operator-review__container__list__user__info">
              <IconImage
                :src="review.src"
                :width="45"
                :height="45"
                class="p-business-operator-review__container__list__user__info__icon"
              />
              <Paragraph class="p-business-operator-review__container__list__user__info__name">{{
                review.nickname
              }}</Paragraph>
            </div>
            <div class="p-business-operator-review__container__list__user__comment">{{ review.comment }}</div>
            <button
              class="p-business-operator-review__container__list__user__button"
              @click="deleteReview(review.reviewId)"
            >
              データ削除
            </button>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-business-operator-review {
  &__container {
    &__date-container {
      background-color: #eeeeee;
      padding: 10px 20px;
    }
    &__list {
      padding: 20px;
      &__user {
        padding-bottom: 20px;
        &__info {
          display: flex;
          align-items: center;
          &__name {
            font-size: 13px;
            padding-left: 20px;
          }
        }
        &__comment {
          margin-top: 20px;
          padding: 10px;
          font-size: 12px;
          border-radius: 5px;
          background-color: #eeeeee;
        }
        &__button {
          font-size: 13px;
          padding: 5px 8px;
          border: 1px solid #cccccc;
          border-radius: 5px;
          margin-top: 5px;
          display: block;
          margin-left: auto;
        }
      }
    }
  }
}
</style>
