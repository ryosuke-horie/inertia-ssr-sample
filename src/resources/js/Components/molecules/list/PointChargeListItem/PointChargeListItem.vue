<script lang="ts" setup>
import Button from '@/Components/atom/button/Button/Button.vue';
import Image from '@/Components/atom/image/Image/Image.vue';
import { PointChargeListItemProps } from './type';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';

const props = defineProps<PointChargeListItemProps>();

const emit = defineEmits(['click']);

const onClick = (): void => {
  emit('click');
};
</script>

<template>
  <div class="c-point-charge-list-item">
    <Image class="c-point-charge-list-item__image" :src="props.src" />
    <div>
      <p class="c-point-charge-list-item__amount">{{ props.amount.toLocaleString() }}CP</p>
      <p v-if="props.freeAmount" class="c-point-charge-list-item__free-amount">
        {{ props.freeAmount.toLocaleString() }}CPお得！
      </p>
    </div>
    <Button
      class="c-point-charge-list-item__button"
      :variant="ButtonVariantProp.Warning"
      :text="`￥${props.amount.toLocaleString()}`"
      :disabled="props.ageLimitPoint <= props.amount"
      @click="onClick"
    />
  </div>
</template>

<style lang="scss">
.c-point-charge-list-item {
  display: flex;
  align-items: center;
  padding: 20px 30px;
  & + .c-point-charge-list-item {
    border-top: solid 1px #ddd;
  }
  &__image img {
    width: 50px;
    height: 50px;
  }
  &__amount {
    font-weight: var(--bold);
    margin-left: 20px;
  }
  &__free-amount {
    color: var(--red);
    font-size: 14px;
    font-weight: var(--bold);
    margin-left: 20px;
  }
  &__button {
    margin-left: auto;
  }
}
</style>
