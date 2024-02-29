<script lang="ts" setup>
import { onMounted, onUnmounted } from 'vue';
import { ModalProps, ModalVariantProp } from './type';
import Divider from '@/Components/atom/divider/Divider.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';

const props = withDefaults(defineProps<ModalProps>(), {
  variant: ModalVariantProp.Black,
  contentWidth: '600px',
});

const disableBodyScroll = () => {
  document.body.style.overflow = 'hidden';
};

const enableBodyScroll = () => {
  document.body.style.overflow = '';
};

onMounted(() => {
  disableBodyScroll();
});

onUnmounted(() => {
  enableBodyScroll();
});

const emit = defineEmits(['close']);

const onClick = (): void => {
  emit('close');
  enableBodyScroll();
};
</script>
<template>
  <div class="c-modal" :class="[props.variant]">
    <div class="c-modal__container" @click="onClick">
      <div class="c-modal__content" @click.stop :style="{ width: props.contentWidth }">
        <div class="c-modal__header" @click="onClick">
          <div class="c-modal__header-content">
            <Heading2 :text="props.headerText" />
            <p>×</p>
          </div>

          <Divider />
        </div>
        <div class="c-modal__modal-main">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.c-modal {
  &__container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    &.black {
      color: var(--white);
      background-color: var(--black);
    }
    &.white {
      color: var(--black);
      background-color: var(--white);
    }
  }
  &__content {
    background: var(--white);
    padding: 20px;
    border-radius: 5px;
    text-align: center;
  }
  &__header {
    &-content {
      display: flex;
      align-items: flex-start;
      cursor: pointer;
      font-weight: bold;
    }
  }
}
</style>
