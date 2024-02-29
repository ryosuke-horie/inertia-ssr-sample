<script lang="ts" setup>
import { BusinessOperatorListItemProps } from './type';
import { Link } from '@inertiajs/vue3';
import Divider from '@/Components/atom/divider/Divider.vue';
import Image from '@/Components/atom/image/Image/Image.vue';
import NoImageShop from '@/../assets/images/noimage/noimage_shop.png';

const props = defineProps<BusinessOperatorListItemProps>();

const imageStyle = {
  width: '100%',
  height: '100%',
};
</script>

<template>
  <li class="c-business-operator-list-item">
    <Link :href="props.href" class="c-business-operator-list-item__link">
      <Image
        :src="props.images.length ? props.images[0].image : NoImageShop"
        alt="事業者画像"
        :width="imageStyle.width"
        :height="imageStyle.height"
        class="c-business-operator-list-item__image"
      />
      <div class="c-business-operator-list-item__info">
        <p class="c-business-operator-list-item__name">{{ props.name }}</p>
        <p class="c-business-operator-list-item__city">{{ props.city }}</p>
      </div>
    </Link>
    <Divider />
  </li>
</template>

<style lang="scss">
.c-business-operator-list-item {
  margin-bottom: 20px;
  &__image img {
    width: 100%;
    height: 300px;
    object-fit: cover;
  }
  &__link {
    color: var(--black);
  }
  &__info {
    margin: 0 10px;
  }
  &__name {
    font-weight: bold;
  }
  &__city {
    font-size: 13px;
    margin-bottom: 10px;
    color: var(--gray);
  }
}
</style>
