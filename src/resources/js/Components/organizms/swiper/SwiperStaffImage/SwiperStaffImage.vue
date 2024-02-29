<script lang="ts" setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import { SwiperStaffImageProps } from './type';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

// Swiperのモジュール設定
const modules = [Pagination];

const props = defineProps<SwiperStaffImageProps>();

// イベントの定義
const emit = defineEmits(['toggleFavorite', 'toggleUserLike']);

// イベント発火の関数
const onToggleFavorite = (staffId: number) => {
  emit('toggleFavorite', staffId);
};

const onToggleUserLike = (staffId: number) => {
  emit('toggleUserLike', staffId);
};
</script>

<template>
  <div class="c-swiper-business-operator-image">
    <swiper
      :space-between="30"
      :free-mode="true"
      :pagination="{
        clickable: true,
      }"
      :modules="modules"
      class="mySwiper"
    >
      <template v-if="props.images.length > 0">
        <swiper-slide v-for="(image, index) in props.images" :key="index">
          <img class="c-swiper-staff-image__staff-image" :src="image.image" :alt="`Image ${index}`" />
        </swiper-slide>
      </template>
      <template v-else>
        <swiper-slide>
          <img class="c-swiper-staff-image__staff-image" :src="NoImageUser" alt="スタッフ画像" />
        </swiper-slide>
      </template>
      <div class="c-swiper-staff-image__icons">
        <!-- お気に入りアイコン -->
        <div class="c-swiper-staff-image__icon-container">
          <p v-if="favoriteId" @click="onToggleFavorite(props.staffId)" class="c-swiper-staff-image__icon">
            <i class="fa-solid fa-star"></i>
          </p>
          <p
            v-else
            @click="onToggleFavorite(props.staffId)"
            class="c-swiper-staff-image__icon c-swiper-staff-image__icon--inactive"
          >
            <i class="fa-regular fa-star"></i>
          </p>
        </div>

        <!-- いいねアイコン -->
        <div class="c-swiper-staff-image__like-container">
          <p v-if="userLikeId" @click="onToggleUserLike(props.staffId)" class="c-swiper-staff-image__like">
            <i class="fa-solid fa-thumbs-up"></i>
            <span class="c-swiper-staff-image__like-count">{{ userLikeCount }}</span>
          </p>
          <p
            v-else
            @click="onToggleUserLike(props.staffId)"
            class="c-swiper-staff-image__like c-swiper-staff-image__like--inactive"
          >
            <i class="fa-regular fa-thumbs-up"></i>
            <span class="c-swiper-staff-image__like-count">{{ userLikeCount }}</span>
          </p>
        </div>
      </div>
    </swiper>
  </div>
</template>

<style lang="scss">
.c-swiper-staff-image {
  &__icons {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    z-index: 10;
    bottom: 30px;
    right: 10px;
  }
  &__icon-container,
  &__like-container {
    position: relative;
    margin: 0 10px;
    p {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: var(--white);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
      flex-direction: column;
      i {
        font-size: 24px;
      }
      i.fa-regular.fa-thumbs-up {
        color: #555;
      }
      i.fa-solid.fa-thumbs-up {
        color: var(--black);
      }
      i.fa-regular.fa-star {
        color: var(--yellow);
      }
      i.fa-solid.fa-star {
        color: var(--yellow);
      }
      .c-swiper-staff-image__like-count {
        font-size: 11px;
      }
    }

    p:hover {
      background-color: #eee;
      cursor: pointer;
    }
  }
  &__staff {
    &-image {
      width: 100%;
      height: 450px;
      object-fit: cover;
    }
  }
}
</style>
