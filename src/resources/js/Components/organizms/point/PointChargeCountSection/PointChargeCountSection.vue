<script lang="ts" setup>
import { PointChargeCountSectionProps } from './type';
import { Link } from '@inertiajs/vue3';

const props = defineProps<PointChargeCountSectionProps>();

const emit = defineEmits<{
  click: [];
}>();

const onClick = (e: Event): void => {
  e.preventDefault();
  emit('click');
};
</script>
<template>
  <div class="c-point-charge-count-section">
    <p class="c-point-charge-count-section__label">購入予定ポイント</p>
    <div class="c-point-charge-count-section__detail">
      <p class="c-point-charge-count-section__detail-cp">{{ (props.amount + props.freeAmount).toLocaleString() }}CP</p>
      <p class="c-point-charge-count-section__detail-amount">（￥{{ props.amount.toLocaleString() }}）</p>
      <Link v-if="props.href" class="c-point-charge-count-section__detail-link" :href="props.href">変更</Link>
      <a class="c-point-charge-count-section__detail-link" v-else @click="onClick">変更</a>
    </div>
  </div>
</template>

<style lang="scss">
.c-point-charge-count-section {
  display: flex;
  flex-direction: column;
  padding: 30px 20px;
  background: #f9f0ad;
  &__label {
    font-weight: var(--bold);
  }
  &__detail {
    display: flex;
    align-items: center;
    &-cp {
      font-size: 30px;
      font-weight: var(--bold);
    }
    &-amount {
      font-size: 17px;
      font-weight: var(--bold);
    }
    &-link {
      cursor: pointer;
      margin-left: auto;
      background: #333333;
      color: var(--white);
      padding: 6px 15px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: var(--bold);
      font-size: 13px;
    }
  }
}
</style>
