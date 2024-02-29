<script lang="ts" setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import { SwiperFavoriteStaffListPorps } from './type';
import SwiperStaffListItem from '@/Components/molecules/list/SwiperStaffListItem/SwiperStaffListItem.vue';

// Swiperのモジュール設定
const modules = [Pagination];

const props = defineProps<SwiperFavoriteStaffListPorps>();
</script>

<template>
  <div class="c-swiper-favorite-staff-list">
    <swiper
      :slides-per-view="3"
      :space-between="10"
      :free-mode="true"
      :pagination="{
        clickable: true,
      }"
      :modules="modules"
      class="mySwiper"
    >
      <swiper-slide v-for="(item, index) in props.list" :key="index">
        <SwiperStaffListItem
          :staff-id="item.staffId"
          :business-id="item.businessId"
          :staff-profile-images="item.staffProfileImages"
          :staff-name="item.staffName"
          :business-name="item.businessName"
          :href="
            isGuestUser
              ? `/guest/business-operator/${item.businessId}/staff/${item.staffId}`
              : `/user/business-operator/${item.businessId}/staff/${item.staffId}`
          "
        />
      </swiper-slide>
    </swiper>
  </div>
</template>

<style lang="scss">
.c-swiper-favorite-staff-list {
  .swiper {
    width: 100%;
    height: 100%;

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 40px;
      margin-top: 10px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }
}
</style>
