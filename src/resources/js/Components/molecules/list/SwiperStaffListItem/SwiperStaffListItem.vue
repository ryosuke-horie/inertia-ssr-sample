<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Image from '@/Components/atom/image/Image/Image.vue';
import type { SwiperStaffListItemPorps } from './type';
import NoImageUser from '@/../assets/images/noimage/noimage_user.png';

const props = defineProps<SwiperStaffListItemPorps>();

const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 500);

// ウィンドウ幅を更新
const updateWindowWidth = () => {
  windowWidth.value = window.innerWidth;
};

onMounted(() => {
  if (typeof window !== 'undefined') {
    window.addEventListener('resize', updateWindowWidth);
  }
});
onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('resize', updateWindowWidth);
  }
});

const imageStyle = computed(() => {
  if (windowWidth.value <= 500) {
    return { width: 115, height: 130 };
  } else {
    return { width: 150, height: 150 };
  }
});
</script>

<template>
  <Link :href="props.href" class="c-favorite-staff__staff-list-item">
    <Image
      :src="props.staffProfileImages.length ? props.staffProfileImages[0].image : NoImageUser"
      alt="スタッフ画像"
      :width="imageStyle.width"
      :height="imageStyle.height"
      class="c-favorite-staff__staff-image"
    />
    <div class="c-favorite-staff__staff-info">
      <p class="c-favorite-staff__staff-name">{{ props.staffName }}</p>
      <p class="c-favorite-staff__business-name">{{ props.businessName }}</p>
    </div>
  </Link>
</template>

<style lang="scss">
.c-favorite-staff {
  &__staff {
    &-list-item {
      color: var(--black);
    }
    &-info {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin: 10px 0 0 5px;
    }
    &-name {
      font-size: 14px;
      font-weight: bold;
    }
  }

  &__business {
    &-name {
      font-size: 12px;
      color: var(--gray);
    }
  }
}
</style>
