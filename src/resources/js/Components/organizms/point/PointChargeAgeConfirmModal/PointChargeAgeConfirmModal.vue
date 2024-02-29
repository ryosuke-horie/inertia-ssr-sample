<script lang="ts" setup>
import { computed } from 'vue';
import Modal from '@/Components/molecules/modal/Modal/Modal.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import { RouteName } from '@/Utilities';

const props = defineProps<{
  isGuestUser: boolean;
}>();

const emit = defineEmits<{
  (e: 'click'): void | Promise<void>;
}>();

const onClick = () => {
  emit('click');
};

// ゲストユーザーと会員ユーザーで表示するボタンを変更
const buttonItems = computed(() => {
  const items = [
    { text: `15歳未満\n（1ヵ月 5,000円まで）` },
    { text: `16歳から19歳\n（1ヵ月 20,000円まで）` },
    { text: `20歳以上\n（制限なし）` },
  ];

  // isGuestUserがtrueの場合は、20歳以上の項目のみを含む配列を返す
  if (props.isGuestUser) {
    return items.filter((item) => item.text.includes('20歳以上'));
  }

  return items;
});
</script>

<template>
  <Modal class="c-point-charge-age-confirm-modal" :is-background-close="false">
    <div class="c-point-charge-age-confirm-modal__content" @click.stop>
      <p class="c-point-charge-age-confirm-modal__message">年齢確認</p>
      <div class="c-point-charge-age-confirm-modal__details">
        <div class="c-point-charge-age-confirm-modal__details-label">
          <template v-if="props.isGuestUser">
            <div class="c-point-charge-age-confirm-modal__guest-text">
              20歳以上の方のみCP購入が可能です。<br />
              会員登録することにより、年齢に関わらず全ての機能をご利用できます。
            </div>
            <AnkerButton
              text="会員登録する"
              class="c-point-charge-age-confirm-modal__register-button"
              :href="route(RouteName.UserSignup)"
              :variant="ButtonVariantProp.Warning"
            />
          </template>
          <template v-if="!props.isGuestUser">
            あなたの年齢によって<br />
            毎月のCP購入額に上限があります。<br />
            あなたの年齢を選んでください。
          </template>
        </div>
      </div>
      <div class="c-point-charge-age-confirm-modal__actions">
        <Button
          v-for="(item, index) of buttonItems"
          :key="item.text + index"
          :variant="ButtonVariantProp.Warning"
          :text="item.text"
          @click="onClick"
          class="c-point-charge-age-confirm-modal__actions-button"
        />
      </div>
      <p class="c-point-charge-age-confirm-modal__warning">
        年齢詐称により、お客様に何らかの不利益が生じた場合であっても、 当社では責任を負いかねます。
        あらかじめご了承くださいますようお願い申し上げます。
      </p>
    </div>
  </Modal>
</template>

<style lang="scss">
.c-point-charge-age-confirm-modal {
  &__actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
    &-button {
      white-space: pre-line;
      border-radius: 100px;
      height: 70px !important;
    }
  }
  &__message {
    background: #f5d9d1;
    color: #f44912;
    border: 1px solid #f3cac0;
    padding: 5px 0;
    border-radius: 5px;
  }
  &__details {
    display: flex;
    justify-content: space-around;
    margin: 20px 0;
    flex-direction: column;
    font-weight: bold;
  }
  &__guest-text {
    text-align: left;
    margin: 0 20px;
  }
  &__register-button {
    margin-top: 20px;
  }
  &__warning {
    font-size: 12px;
    color: var(--gray);
    margin-top: 15px;
  }
}
</style>
