<script lang="ts" setup>
import { AdminHeaderProps } from './type';
import { Link } from '@inertiajs/vue3';

const props = defineProps<AdminHeaderProps>();
</script>
<template>
  <div class="c-header">
    <div class="c-header__container">
      <Link class="c-header__link" v-if="props.href" :href="props.href">
        <i class="fa-solid fa-chevron-left"></i>戻る
      </Link>
      <p class="c-header__text">{{ props.text }}</p>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.c-header {
  max-width: 1260px;
  width: 100%;
  margin: 0 auto;
  padding: 28px 28px 0;
  &__container {
    display: flex;
    gap: 20px;
    align-items: center;
  }
  &__text {
    font-size: 18px;
    font-weight: bold;
  }
}
</style>
