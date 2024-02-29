<script lang="ts" setup>
import { PointChargeSubmitConfirmModalProps } from './type';
import Button from '@/Components/atom/button/Button/Button.vue';

const props = defineProps<PointChargeSubmitConfirmModalProps>();

const emit = defineEmits<{
  (e: 'submit'): void | Promise<void>;
  (e: 'close'): void | Promise<void>;
}>();

const onSubmit = () => {
  emit('submit');
};

const onClose = () => {
  emit('close');
};
</script>

<template>
  <div class="c-point-charge-submit-modal">
    <div class="c-point-charge-submit-modal__content">
      <div class="c-point-charge-submit-modal__point">
        <div class="c-point-charge-submit-modal__point-label">購入予定ポイント</div>
        <div class="c-point-charge-submit-modal__point-detail">
          <p class="c-point-charge-submit-modal__point-detail-cp">
            {{ (props.amount + props.freeAmount).toLocaleString() }}CP
          </p>
          <p class="c-point-charge-submit-modal__point-detail-amount">（￥{{ props.amount.toLocaleString() }}）</p>
        </div>
      </div>
      <div class="c-point-charge-submit-modal__buttons">
        <Button
          @click="onSubmit"
          class="c-point-charge-submit-modal__buttons-submit"
          text="支払う"
          :is-loading="props.isSubmitLoading"
        />
        <Button
          @click="onClose"
          text="キャンセル"
          variant="secondary"
          class="c-point-charge-submit-modal__buttons-cancel"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.c-point-charge-submit-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  background-color: rgba(0, 0, 0, 0.5);
  &__content {
    width: 100%;
    margin: 0 16px;
    max-width: 375px;
    background: var(--white);
  }
  &__point {
    padding: 16px 20px;
    background: #f9f0ad;
    &-label {
      font-weight: var(--bold);
    }
    &-detail {
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
    }
  }
  &__buttons {
    display: flex;
    flex-direction: column;
    padding: 16px;
    &-submit {
      margin-bottom: 16px;
    }
  }
}
</style>
