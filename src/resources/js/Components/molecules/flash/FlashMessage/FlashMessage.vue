<script setup lang="ts">
import { computed } from 'vue';
import { FlashVariantProp, type FlashProps } from './type';

const props = withDefaults(defineProps<FlashProps>(), {
  variant: FlashVariantProp.Success,
});

const computedClasses = computed<string[]>(() => {
  const classes: string[] = [];

  classes.push(props.variant);

  return classes;
});
</script>
<template>
  <transition name="c-flash-message">
    <div v-if="props.message" class="c-flash-message" :class="computedClasses">
      {{ props.message }}
    </div>
  </transition>
</template>
<stype lang="scss">
.c-flash-message {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  color: #fff;
  padding: 15px 16px;
  font-size: 16px;
  text-align: center;
  animation: slideDownFadeOut 3s forwards;
  z-index: 1050;
  &.success {
    background-color: #61c1bd;
  }
  background-color: #61c1bd;

  &.failed {
    background-color: #f44336;
  }

  &.blue {
    background-color: #2196f3;
  }

  &__icon {
    position: absolute;
    top: 50%;
    right: 32px;
    transform: translateY(-50%);
    z-index: 1;
    i {
      color: var(--white);
    }
  }
}

@keyframes slideDownFadeOut {
  0% {
    transform: translateY(-100%);
    opacity: 0;
  }
  20% {
    /* よりゆっくり表示させる */
    transform: translateY(0);
    opacity: 1;
  }
  80% {
    /* 表示を長く保つ */
    transform: translateY(0);
    opacity: 1;
  }
  100% {
    transform: translateY(-100%);
    opacity: 0;
  }
}
</stype>
