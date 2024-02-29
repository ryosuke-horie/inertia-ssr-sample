<script lang="ts" setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import { SwiperBusinessOperatorImageProps } from './type';
import Image from '@/Components/atom/image/Image/Image.vue';
import NoImageShop from '@/../assets/images/noimage/noimage_shop.png';

// Swiperのモジュール設定
const modules = [Pagination];

const props = defineProps<SwiperBusinessOperatorImageProps>();
</script>

<template>
  <div class="c-swiper-business-operator-image">
    <swiper :space-between="30" :free-mode="true" :pagination="{ clickable: true }" :modules="modules" class="mySwiper">
      <!-- 画像が存在する場合の処理 -->
      <template v-if="props.images.length > 0">
        <swiper-slide v-for="(image, index) in props.images" :key="`image-${index}`">
          <Image
            class="c-swiper-business-operator-image__shop-image"
            :src="image.image"
            :alt="`Image ${index}`"
            :width="`100%`"
            :height="`100%`"
          />
        </swiper-slide>
      </template>
      <template v-else>
        <swiper-slide>
          <Image
            class="c-swiper-business-operator-image__shop-image"
            :src="NoImageShop"
            alt="お店画像"
            :width="`100%`"
            :height="`100%`"
          />
        </swiper-slide>
      </template>
    </swiper>
  </div>
</template>

<style lang="scss">
.c-swiper-business-operator-image {
  .mySwiper {
    .swiper-slide {
      display: flex;
      justify-content: center;
      align-items: center;

      .c-swiper-business-operator-image__shop-image img {
        width: 100%;
        height: 300px;
        object-fit: cover;
      }
    }
  }
}
</style>
