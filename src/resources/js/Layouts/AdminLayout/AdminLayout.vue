<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import { AdminLayoutProps } from './type';
import Sideber from '@/Components/_admin/Sidebar/Sidebar.vue';
import AdminHeader from '@/Components/_admin/Header/AdminHeader.vue';
import FlashMessage from '@/Components/molecules/flash/FlashMessage/FlashMessage.vue';
import { useFlashMessages } from '@/Hooks/useFlashMessages';

const props = defineProps<AdminLayoutProps>();

const { flashMessages } = useFlashMessages();
</script>

<template>
  <Head v-bind="props" />
  <div class="l-layout">
    <template v-for="(item, index) of flashMessages" :key="item + index">
      <FlashMessage v-if="item.message" :message="item.message.text" :variant="item.message.type" />
    </template>
    <div class="l-layout__container">
      <Sideber />
      <AdminHeader
        :text="props.text"
        :href="!props.href ? '' : props.params ? route(props.href, props.params) : route(props.href)"
      />

      <main class="l-layout__main">
        <slot />
      </main>
    </div>
  </div>
</template>
<style lang="scss">
.l-layout {
  box-sizing: border-box;
  &__container {
    flex-direction: column;
    display: flex;
    padding-left: 250px;
  }
  &__main {
    max-width: 1260px;
    width: 100%;
    margin: 0 auto;
    padding: 0px 28px;
  }
}
</style>
