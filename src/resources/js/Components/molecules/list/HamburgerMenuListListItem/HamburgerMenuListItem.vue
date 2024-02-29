<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import type { HamburgerMenuListItemProps } from './type';

const props = defineProps<HamburgerMenuListItemProps>();
</script>

<template>
  <Link class="c-hamburger-menu-list-item" :href="props.href">
    <div class="c-hamburger-menu-list-item__content">
      <i v-if="props.icon" :class="props.icon" class="c-hamburger-menu-list-item__content-icon" />
      <span class="c-hamburger-menu-list-item__content-text">{{ props.text }}</span>
    </div>
    <i class="fa-solid fa-chevron-right icon" />
  </Link>
</template>

<style lang="scss">
.c-hamburger-menu-list-item {
  display: flex;
  align-items: center;
  height: 50px;
  padding: 0 16px;
  color: var(--black);
  border-bottom: solid 1px #dddddd;
  &:first-of-type {
    @media screen and (max-width: 1024px) {
      border-top: solid 1px #dddddd;
    }
  }
  &__content {
    &-icon {
      margin-right: 16px;
    }
    &-text {
    }
  }
  .icon {
    margin-left: auto;
  }
}
</style>
