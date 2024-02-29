<script lang="ts" setup>
import { ModalProps, ModalVariantProp } from './type';
import ModalHeader from '@/Components/atom/modal/ModalHeader/ModalHeader.vue';
import { onMounted, onUnmounted } from 'vue';
import { addHtmlDataset, deleteHtmlDataset, HtmlDatasetKeyEnum } from '@/Utilities';

const props = withDefaults(defineProps<ModalProps>(), {
  isBackgroundClose: true,
  variant: ModalVariantProp.Black,
});

const emit = defineEmits(['close']);
const onClose = (): void => {
  emit('close');
};

onMounted(() => {
  addHtmlDataset(HtmlDatasetKeyEnum.IsModal, 'active');
});

onUnmounted(() => {
  deleteHtmlDataset(HtmlDatasetKeyEnum.IsModal);
});
</script>

<template>
  <div class="c-modal" :class="[props.variant]">
    <div v-if="props.isBackgroundClose" class="c-modal__background" @click="onClose" />
    <div v-else class="c-modal__background" />
    <div class="c-modal__container" @click.stop>
      <ModalHeader v-if="props.headerText" :text="props.headerText" @click="onClose" class="c-modal__header" />
      <div class="c-modal__content">
        <slot />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.c-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  &__header {
    margin-bottom: 16px;
  }
  &__background {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 1000;
  }
  &__container {
    position: relative;
    z-index: 1001;
    background: var(--white);
    padding: 24px 16px;
    border-radius: 5px;
    text-align: center;
    width: 360px;
    @media screen and (max-width: 360px) {
      width: 340px;
    }
  }
  &__content {
    max-height: calc(100vh - 150px);
    overflow: hidden;
    overflow-y: scroll;
    /*IE(Internet Explorer)・Microsoft Edgeへの対応*/
    -ms-overflow-style: none;
    /*Firefoxへの対応*/
    scrollbar-width: none;
    &::-webkit-scrollbar {
      display: none;
    }
  }
}
</style>
