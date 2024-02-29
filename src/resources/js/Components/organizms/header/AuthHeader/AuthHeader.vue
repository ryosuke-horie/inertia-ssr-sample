<script lang="ts" setup>
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import { type AuthHeaderProps } from './type';
import Divider from '@/Components/atom/divider/Divider.vue';

const props = defineProps<AuthHeaderProps>();
</script>

<template>
  <header class="c-auth-header">
    <HeaderSubText
      :text="props.text"
      :href="!props.href ? '' : props.params ? route(props.href, props.params) : route(props.href)"
    />
    <Divider />
  </header>
</template>

<style lang="scss">
.c-auth-header {
  background-color: var(--white);
  &__main {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
    // &-title {
    //   font-size: 16px;
    // }
    // &-toggle,
    // &-search,
    // &-user {
    //   cursor: pointer;
    //   display: inline-block;
    //   position: absolute;
    //   top: 50%;
    //   transform: translateY(-50%);
    //   width: 20px;
    //   height: 20px;
    // }
    // &-toggle {
    //   left: 16px;
    // }
    // &-search {
    //   right: 46px;
    // }
    // &-user {
    //   right: 16px;
    // }
  }
}
</style>
