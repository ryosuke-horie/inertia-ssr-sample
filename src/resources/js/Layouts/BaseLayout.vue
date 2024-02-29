<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Baneers from '@/Components/_lp/Banners/Banners.vue';
import HamburgerMenu from '@/Components/organizms/hamburger/HamburgerMenu.vue';
import Header from '@/Components/_lp/Header/Header.vue';
import type { HeaderProps } from '@/Components/_lp/Header/type';
import { AuthHeaderProps } from '@/Components/organizms/header/AuthHeader/type';
import { HtmlDatasetKeyEnum, RouteRoleName, addHtmlDataset, deleteHtmlDataset } from '@/Utilities';
import Divider from '@/Components/atom/divider/Divider.vue';
import HeaderSubText from '@/Components/atom/header/HeaderSubText/HeaderSubText.vue';
import FlashMessage from '@/Components/molecules/flash/FlashMessage/FlashMessage.vue';
import { useFlashMessages } from '@/Hooks/useFlashMessages';

export type BaseLayoutProps = {
  title: string;
  role?: RouteRoleName;
  isAuth?: boolean;
  authHeader?: AuthHeaderProps;
  headerMenus?: HeaderProps['menus'];
};

const props = defineProps<BaseLayoutProps>();

const { flashMessages } = useFlashMessages();

const isHamburger = ref(false);
const setIsHamburger = (): void => {
  isHamburger.value = !isHamburger.value;
};
watch(isHamburger, () => {
  isHamburger.value
    ? addHtmlDataset(HtmlDatasetKeyEnum.IsSidebar, 'active')
    : deleteHtmlDataset(HtmlDatasetKeyEnum.IsSidebar);
});
</script>
<template>
  <Head v-bind="props" />
  <div class="l-layout">
    <div class="l-layout__container">
      <div class="l-layout__hamburger" :class="{ 'is-open': isHamburger }">
        <HamburgerMenu :is-auth="props.isAuth" :role="props.role" @close="setIsHamburger" />
      </div>
      <div class="l-layout__content">
        <template v-for="(item, index) of flashMessages" :key="item + index">
          <FlashMessage v-if="item.message" :message="item.message.text" :variant="item.message.type" />
        </template>
        <Header
          class="l-layout__header"
          @open="setIsHamburger"
          :role="props.role ?? 'web'"
          :menus="props.headerMenus || []"
        />
        <HeaderSubText
          v-if="props.authHeader"
          :text="props.authHeader.text"
          :href="
            !props.authHeader.href
              ? ''
              : props.authHeader.params
                ? route(props.authHeader.href, props.authHeader.params)
                : route(props.authHeader.href)
          "
        />
        <Divider />
        <main>
          <slot />
        </main>
      </div>
      <div class="l-layout__banners">
        <Baneers />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.l-layout {
  background-color: rgb(235, 235, 235);
  &__container {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
    @media screen and (min-width: 1025px) {
      display: grid;
      grid-template-columns: 375px 1fr 338px;
      gap: 8px;
      max-width: 1320px;
    }
  }
  &__content {
    max-width: 600px;
    background-color: var(--white);
  }
  &__header {
    position: sticky;
    top: 0;
    z-index: 3;
  }
  &__hamburger {
    max-width: 600px;
    @media screen and (max-width: 1024px) {
      position: fixed;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100vh;
      z-index: 1000;
      overflow-y: scroll;
      scrollbar-width: none;
      -ms-overflow-style: none;
      &::-webkit-scrollbar {
        display: none;
      }
      &.is-open {
        top: 0;
        left: unset;
      }
    }
  }
  &__banners {
    display: none;
    @media screen and (min-width: 1025px) {
      display: block;
    }
    img {
      width: 100%;
    }
  }
}
</style>
