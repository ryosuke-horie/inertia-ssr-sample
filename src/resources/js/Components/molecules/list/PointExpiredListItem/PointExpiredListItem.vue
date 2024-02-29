<script lang="ts" setup>
import { computed } from 'vue';
import { PointExpiredListItemProps, PointExpiredListItemCategoryProp } from './type';

const props = defineProps<PointExpiredListItemProps>();

const computedLabelTag = computed<string>(() => {
  switch (props.category) {
    case PointExpiredListItemCategoryProp.paid:
      return '有償';
    case PointExpiredListItemCategoryProp.free:
      return '無償';
    default:
      return '';
  }
});

const computedLabelName = computed<string>(() => {
  switch (props.category) {
    case PointExpiredListItemCategoryProp.paid:
      return 'ポイント購入';
    case PointExpiredListItemCategoryProp.free:
      return 'ボーナスポイント';
    default:
      return '';
  }
});
</script>

<template>
  <li class="c-point-expired-list-item">
    <div class="c-point-expired-list-item__wrap">
      <div class="c-point-expired-list-item__left">
        <div class="c-point-expired-list-item__label">
          <div
            class="c-point-expired-list-item__label-tag"
            :class="`c-point-expired-list-item__label-tag--${props.category}`"
          >
            {{ computedLabelTag }}
          </div>
          <div class="c-point-expired-list-item__label-name">{{ computedLabelName }}</div>
        </div>
        <div class="c-point-expired-list-item__expired">
          <span>{{ props.expiredAt }}</span>
          に失効
        </div>
      </div>
      <div class="c-point-expired-list-item__right">
        <div class="c-point-expired-list-item__point">
          <div class="c-point-expired-list-item__point-count">{{ props.points.toLocaleString() }}</div>
          <div class="c-point-expired-list-item__point-unit">CP</div>
        </div>
      </div>
    </div>
  </li>
</template>

<style lang="scss">
.c-point-expired-list-item {
  padding: 16px;
  & + .c-point-expired-list-item {
    border-top: solid 1px #dddddd;
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
      font-size: 12px;
      margin-left: 4px;
      font-weight: var(--bold);
    }
  }
  &__expired {
    font-size: 12px;
    margin-top: 4px;
    font-weight: var(--bold);
    span {
      font-size: 14px;
    }
  }
}
</style>
