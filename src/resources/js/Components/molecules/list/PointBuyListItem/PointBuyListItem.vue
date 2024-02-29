<script lang="ts" setup>
import { computed } from 'vue';
import { PointByListItemProps, PointBuyListItemCategoryProp } from './type';

const props = defineProps<PointByListItemProps>();

const computedLabelTag = computed<string>(() => {
  switch (props.category) {
    case PointBuyListItemCategoryProp.paid:
      return '有償';
    case PointBuyListItemCategoryProp.free:
      return '無償';
    default:
      return '';
  }
});

const computedLabelName = computed<string>(() => {
  switch (props.category) {
    case PointBuyListItemCategoryProp.paid:
      return 'ポイント購入';
    case PointBuyListItemCategoryProp.free:
      return '新規登録キャンペーン';
    default:
      return '';
  }
});
</script>

<template>
  <li class="c-point-buy-list-item">
    <div class="c-point-buy-list-item__wrap">
      <div class="c-point-buy-list-item__left">
        <div class="c-point-buy-list-item__date">{{ props.date }}</div>
        <div class="c-point-buy-list-item__label">
          <div class="c-point-buy-list-item__label-tag" :class="`c-point-buy-list-item__label-tag--${props.category}`">
            {{ computedLabelTag }}
          </div>
          <div class="c-point-buy-list-item__label-name">{{ computedLabelName }}</div>
        </div>
        <div v-if="props.expiredAt" class="c-point-buy-list-item__expired">有効期限：{{ props.expiredAt }}</div>
      </div>
      <div class="c-point-buy-list-item__right">
        <div class="c-point-buy-list-item__point">
          <div class="c-point-buy-list-item__point-count">{{ props.points.toLocaleString() }}</div>
          <div class="c-point-buy-list-item__point-unit">CP</div>
        </div>
      </div>
    </div>
  </li>
</template>

<style lang="scss">
.c-point-buy-list-item {
  padding: 16px;
  & + .c-point-buy-list-item {
    border-top: solid 1px #dddddd;
  }
  &__date {
    font-size: 12px;
  }
  &__wrap {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
  }
  &__point {
    display: flex;
    align-items: center;
    font-weight: var(--bold);
    &-count {
      font-size: 34px;
      line-height: 34px;
    }
    &-unit {
      font-size: 10px;
      margin-top: 10px;
      margin-left: 4px;
    }
  }
  &__label {
    display: flex;
    align-items: center;
    &-tag {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 16px;
      font-size: 10px;
      color: var(--white);
      background-color: #ff5693;
      &--2 {
        background-color: #36df98;
      }
    }
    &-name {
      font-size: 16px;
      margin-left: 4px;
      font-weight: var(--bold);
    }
  }
  &__expired {
    margin-top: 4px;
    font-size: 12px;
    margin-top: 0px;
  }
}
</style>
