<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { BusinessReviewProps } from './type';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

const props = defineProps<{
  businessReviews: BusinessReviewProps[];
  userId: number;
}>();

const displayedReviewCount = ref(3);
const showFullComment: Record<number, boolean> = reactive({});
const selectReviewId = ref<number | null>(null);
const setSelectReviewId = (val: number | null): void => {
  selectReviewId.value = val;
};

// 表示する口コミの数
function showMoreReviews() {
  displayedReviewCount.value += 3;
}

// 表示する口コミの数をリセット
function resetReviews() {
  displayedReviewCount.value = 3;
}

// 「続きを読む」「一部表示」の切り替え
function toggleComment(index: number) {
  showFullComment[index] = !showFullComment[index];
}

// 表示する口コミ
const displayedReviews = computed(() => {
  return props.businessReviews.slice(0, displayedReviewCount.value);
});

// 3件以上の口コミがあれば「もっと見る」ボタンを表示
const showMoreReviewButton = computed(() => {
  return props.businessReviews.length > displayedReviewCount.value;
});

const emit = defineEmits(['onDeleteReview']);

function deleteReviewAndCloseModal() {
  emit('onDeleteReview', selectReviewId.value);
  setSelectReviewId(null);
}
</script>

<template>
  <div class="c-business-review__review" v-if="props.businessReviews">
    <div class="c-business-review__review-item" v-for="(review, index) in displayedReviews" :key="index">
      <img
        class="c-business-review__review-image"
        :src="review.userProfileImage ?? NoImageUser"
        :alt="`Image of ${review.nickname}`"
      />
      <div class="c-business-review__review-content">
        <div class="c-business-review__review-info">
          <p class="c-business-review__review-nickname">{{ review.nickname }}</p>
          <p class="c-business-review__review-time">（{{ review.createdAgo }}）</p>
        </div>
        <div class="c-business-review__review-comment">
          <p v-if="!showFullComment[index]">
            {{ review.comment.slice(0, 50) }}<span v-if="review.comment.length > 50">... </span>
            <button v-if="review.comment.length > 50" @click="toggleComment(index)">▽続きを読む</button>
          </p>
          <p v-else>
            {{ review.comment }}
            <button @click="toggleComment(index)">△一部表示</button>
          </p>
        </div>
        <button
          v-if="props.userId !== 1 && props.userId === review.userId"
          class="c-business-review__review-delete-button"
          @click="setSelectReviewId(review.reviewId)"
        >
          削除
        </button>
      </div>
    </div>
    <div class="c-business-review__review-more-button">
      <button v-if="showMoreReviewButton" @click="showMoreReviews">－もっと見る－</button>
    </div>
    <div class="c-business-review__review-reset-button">
      <button v-if="displayedReviewCount > 3" @click="resetReviews">－元に戻す－</button>
    </div>
    <!-- 削除確認モーダル -->
    <div class="c-business-review__modal" v-if="selectReviewId">
      <div class="c-business-review__modal-overlay" @click="setSelectReviewId(null)" />
      <div class="c-business-review__modal-content">
        <Paragraph class="c-business-review__modal-content-text"> 口コミを削除しますか？ </Paragraph>
        <div class="c-business-review__modal-content-button">
          <button class="c-business-review__modal-content-button-ok" @click="deleteReviewAndCloseModal">はい</button>
          <button class="c-business-review__modal-content-button-cancel" @click="setSelectReviewId(null)">
            いいえ
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.c-business-review {
  &__review {
    &-button {
      margin: 20px;
    }
    &-delete-button {
      color: var(--red);
    }
    &-more-button {
      text-align: center;
      font-size: 13px;
    }
    &-reset-button {
      text-align: center;
      font-size: 13px;
    }
    &-link {
      margin-left: 40%;
      margin-bottom: 20px;
    }
    &-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 10px;
    }
    &-item {
      display: flex;
      padding: 20px;
      font-size: 13px;
    }
    &-info {
      display: flex;
    }
  }
  &__modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9;
    background-color: rgba(0, 0, 0, 0.5);
    &-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 260px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px #00000050;
      background-color: var(--white);
      &-text {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        height: 120px;
        border-bottom: 0.2px solid #d9d9d9;
      }
      &-button {
        display: flex;
        width: 100%;
        height: 80px;
        &-ok {
          border-right: 0.2px solid #d9d9d9;
        }
        &-ok,
        &-cancel {
          cursor: pointer;
          display: block;
          width: 100%;
          height: 100%;
          &:hover {
            background-color: #d9d9d9;
          }
        }
      }
    }
  }
}
</style>
