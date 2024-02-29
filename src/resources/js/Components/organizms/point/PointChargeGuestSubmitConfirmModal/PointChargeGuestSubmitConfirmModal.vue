<script lang="ts" setup>
import { PointChargeGuestSubmitConfirmModalProps } from './type';
import Button from '@/Components/atom/button/Button/Button.vue';

const props = defineProps<PointChargeGuestSubmitConfirmModalProps>();

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
      <div class="c-point-charge-submit-modal__section">
        <div class="c-point-charge-submit-modal__detail">
          <p class="c-point-charge-submit-modal__label">応援ポイント</p>
          <p class="c-point-charge-submit-modal__value">{{ props.amount }}CP（￥{{ props.amount }}）</p>
        </div>
        <div class="c-point-charge-submit-modal__detail">
          <p class="c-point-charge-submit-modal__label">応援するスタッフ</p>
          <p class="c-point-charge-submit-modal__value">{{ props.staffName }}</p>
        </div>
        <div class="c-point-charge-submit-modal__detail">
          <p class="c-point-charge-submit-modal__label">名前</p>
          <p class="c-point-charge-submit-modal__value">{{ props.guestNickname }}</p>
        </div>
        <div class="c-point-charge-submit-modal__detail">
          <p class="c-point-charge-submit-modal__label">メッセージ</p>
          <p class="c-point-charge-submit-modal__value">{{ props.message }}</p>
        </div>
      </div>
      <div class="c-point-charge-submit-modal__buttons">
        <Button
          @click="onSubmit"
          class="c-point-charge-submit-modal__buttons-submit"
          text="応援する"
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
  &__buttons {
    display: flex;
    flex-direction: column;
    padding: 16px;
    &-submit {
      margin-bottom: 16px;
    }
  }
  &__section {
    padding: 16px 16px 0 16px;
  }
  &__detail {
    margin-bottom: 16px;
  }
  &__label {
    color: #979797;
  }
}
</style>
