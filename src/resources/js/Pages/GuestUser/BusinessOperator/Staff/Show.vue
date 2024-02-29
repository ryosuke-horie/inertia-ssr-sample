<script lang="ts" setup>
// Vue関連のインポート
import { ref, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
import { setSessionStorageBase64, deleteSessionStorage } from '@/Utilities/storage';
// 型定義
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import { ModalVariantProp } from '@/Components/molecules/modal/Modal/type';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Divider from '@/Components/atom/divider/Divider.vue';
import Heading2 from '@/Components/atom/heading/Heading2/Heading2.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import ColorSection from '@/Components/atom/section/ColorSection/ColorSection.vue';
import FormGroupTextarea from '@/Components/molecules/form/FormGroupTextarea/FormGroupTextarea.vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import LinkGuidLine from '@/Components/atom/link/Link/Link.vue';
import FormGroupInput from '@/Components/molecules/form/FormGroupInput/FormGroupInput.vue';
import Modal from '@/Components/molecules/modal/Modal/Modal.vue';
import SwiperStaffImage from '@/Components/organizms/swiper/SwiperStaffImage/SwiperStaffImage.vue';

const props = defineProps<{
  aiCount: number;
  totalPoints: number;
  staffId: number;
  staffName: string;
  comment: string;
  images: { image: string; order: number }[];
  businessId: number;
  businessName: string;
  businessSettings: { amount: number }[];
  favoriteId: number;
  userLikeId: number;
  userLikeCount: number;
}>();

type formProps = {
  message: string;
  amount: number;
  freeAmount: number;
  staffId: number;
  staffName: string;
  businessId: number;
  guestNickname: string;
};

const form = useForm<formProps>({
  message: '',
  amount: 0,
  freeAmount: 0, // ゲストユーザーは無償ポイントが発生しないため0固定
  staffId: props.staffId,
  staffName: props.staffName,
  businessId: props.businessId,
  guestNickname: 'ゲスト',
});

// 画面に戻ってきたときに状態を復元
onMounted(() => {
  // sessionStorageをクリア
  deleteSessionStorage('guestRedirectUrl');
  // URLからクエリパラメータを取得（金額以外）
  const urlParams = new URLSearchParams(window.location.search);
  form.message = urlParams.get('message') || form.message;
  form.guestNickname = urlParams.get('guestNickname') || form.guestNickname;
});

// sessionStorageにURLを保存
const setUrlSession = (url: string) => {
  setSessionStorageBase64('guestRedirectUrl', `/guest/business-operator/${props.businessId}/staff/${props.staffId}`);
  router.get(route(url));
};

const isOpen = ref<boolean>(false);

// 投げ銭金額選択
const selectAmount = (index: number) => {
  form.amount = props.businessSettings[index].amount;
};

const setIsOpen = (val: boolean) => {
  isOpen.value = val;
};

const onSubmit = (): void => {
  form.post(
    route(RouteName.GuestUserBusinessOperatorStaffUserTipPayment, {
      businessId: props.businessId,
      staffId: props.staffId,
    }),
  );
};
</script>

<template>
  <BaseLayout
    title="スタッフ応援"
    :is-auth="false"
    :role="RouteRoleName.GuestUser"
    :auth-header="{
      role: RouteRoleName.GuestUser,
      href: RouteName.GuestUserBusinessOperatorShow,
      params: { businessId: props.businessId },
      text: 'スタッフ応援',
    }"
  >
    <div class="p-user-business-operator-staff-show">
      <SwiperStaffImage
        :images="props.images"
        :staff-id="props.staffId"
        :favorite-id="favoriteId"
        :user-like-id="userLikeId"
        :user-like-count="userLikeCount"
        @toggle-favorite="setIsOpen(true)"
        @toggle-user-like="setIsOpen(true)"
      />
      <div class="p-user-business-operator-staff-show__staff-contents">
        <p class="p-user-business-operator-staff-show__staff-name">{{ props.staffName }}</p>

        <div v-if="props.comment" class="p-user-business-operator-staff-show__staff-comment">
          <Divider />
          <p>{{ props.comment }}</p>
        </div>
      </div>

      <Divider />
      <Heading2 class="p-user-business-operator-staff-show__gift-title" text="ギフトを選ぶ" />
      <Divider />

      <ColorSection class="p-user-business-operator-staff-show__form">
        <div class="p-user-business-operator-staff-show__gift-contents">
          <p class="p-user-business-operator-staff-show__gift-text">
            ＼ <span>送るポイントを選択してスタッフを応援</span> ／
          </p>
          <div
            v-for="(amount, index) in props.businessSettings"
            :key="index"
            class="p-user-business-operator-staff-show__gift-button-wrapper"
          >
            <Button
              class="p-user-business-operator-staff-show__gift-button--shadow"
              :class="{ selected: props.businessSettings[index].amount === form.amount }"
              :text="`${amount.amount}pt`"
              :icon="`fa-solid fa-coins`"
              :variant="ButtonVariantProp.Derk"
              :is-outlined="true"
              @click="selectAmount(index)"
            />
          </div>
        </div>
        <div v-if="form.errors.amount" class="p-user-business-operator-staff-show__form-error">
          {{ form.errors.amount }}
        </div>
      </ColorSection>

      <Divider />
      <Heading2 class="p-user-business-operator-staff-show__cheer-title" text="応援メッセージ" />
      <Divider />

      <ColorSection class="p-user-business-operator-staff-show__form">
        <FormGroupInput
          id="nickname"
          type="text"
          required-type="required"
          placeholder="ニックネーム"
          v-model="form.guestNickname"
          class="p-user-business-operator-staff-show__guest-nickname"
          :error="form.errors.guestNickname"
          :is-count="true"
        />
        <FormGroupTextarea
          id="message"
          class="p-user-business-operator-staff-show__textarea"
          placeholder="メッセージを送ってスタッフを応援しよう！"
          v-model="form.message"
          :error="form.errors.message"
          :is-count="true"
          :count-limit="200"
        />

        <Button
          :text="`定形文から選ぶ`"
          :variant="ButtonVariantProp.Derk"
          class="p-user-business-operator-staff-show__fix-phrase"
          @click="setIsOpen(true)"
        />
        <Button
          :text="`AIで作成する`"
          :variant="ButtonVariantProp.Derk"
          class="p-user-business-operator-staff-show__ai"
          @click="setIsOpen(true)"
        />

        <Paragraph class="p-user-business-operator-staff-show__member-message">
          「定形文から選ぶ」「AIで作成する」機能を使うためには、会員登録が必要です。
        </Paragraph>

        <Paragraph class="p-user-business-operator-staff-show__message">
          他の人を傷つける恐れのある内容や個人を特定す るコメントは固く禁止されております。 詳しくは
          <LinkGuidLine text="ガイドライン" :href="RouteName.StaffMyPage" />
          をご確認ください。
        </Paragraph>
        <Button
          class="p-user-business-operator-staff-show__submit-button"
          @click="onSubmit"
          text="確認画面へ"
          :is-loading="form.processing"
        />
      </ColorSection>
      <Modal
        v-if="isOpen"
        :variant="ModalVariantProp.White"
        header-text="この機能を使うためには会員登録が必要です。"
        class="p-user-business-operator-staff-show__modal"
        @close="setIsOpen(false)"
      >
        <Button
          :variant="ButtonVariantProp.Info"
          :is-outlined="true"
          class="p-user-business-operator-staff-show__modal-guest-signup"
          text="無料会員登録"
          @click="setUrlSession(RouteName.UserSignup)"
        />
        <Button
          :variant="ButtonVariantProp.Info"
          class="p-user-business-operator-staff-show__modal-guest-login"
          text="ログイン"
          @click="setUrlSession(RouteName.UserLoginMethod)"
        />
      </Modal>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-business-operator-staff-show {
  &__icons {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    z-index: 10;
    bottom: 30px;
    right: 10px;
  }
  &__icon-container,
  &__like-container {
    position: relative;
    margin: 0 10px;
    p {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: var(--white);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
      flex-direction: column;
      i {
        font-size: 24px;
      }
      i.fa-regular.fa-thumbs-up {
        color: #555;
      }
      i.fa-solid.fa-thumbs-up {
        color: var(--black);
      }
      i.fa-regular.fa-star {
        color: var(--yellow);
      }
      i.fa-solid.fa-star {
        color: var(--yellow);
      }
      .p-user-business-operator-staff-show__like-count {
        font-size: 11px;
      }
    }
    p:hover {
      background-color: #eee;
      cursor: pointer;
    }
  }
  &__staff {
    &-image {
      width: 100%;
      height: 80%;
      object-fit: cover;
    }
    &-contents {
      margin: 5px 20px 20px 20px;
    }
    &-name {
      font-size: 25px;
      margin-bottom: 10px;
    }
    &-comment p {
      margin-top: 20px;
    }
  }
  &__gift {
    &-contents {
      display: flex;
      flex-wrap: wrap;
      &--background {
        background: #e0e0e0;
      }
      & button.c-button {
        color: var(--black);
        width: 100%;
      }
      & .fa-solid.fa-coins {
        color: var(--yellow);
      }
    }
    &-title {
      margin: 10px 0;
      text-align: center;
      font-size: 20px;
    }
    &-button-wrapper {
      flex-basis: 50%;
      padding: 10px;
    }
    &-text {
      margin: 20px auto 10px;
      & span {
        font-weight: bold;
      }
    }
  }
  &__cheer-title {
    margin: 10px 0;
    text-align: center;
    font-size: 20px;
  }
  &__textarea textarea {
    background: var(--white);
  }
  &__guest-nickname {
    margin-bottom: 10px;
    input {
      background: var(--white);
    }
  }
  &__ai {
    margin-bottom: 10px;
    width: 100%;
  }
  &__fix-phrase {
    margin: 10px 0;
    width: 100%;
  }
  &__submit-button {
    margin: 20px auto;
    width: 100%;
  }
  &__form-error {
    font-size: 13px;
    color: #ff0000;
    margin-left: 10px;
  }
  &__points {
    color: var(--yellow);
    &-icon {
      color: #c0deff;
      margin-right: 5px;
    }
    &-label {
      color: var(--white);
      font-size: 13px;
      margin-bottom: 5px;
    }
    &-value {
      color: var(--yellow);
    }
  }
  &__purchase {
    &-link {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    &-icon {
      color: var(--yellow);
      font-size: 30px;
      margin-bottom: 5px;
    }
    &-text {
      color: var(--white);
      font-size: 11px;
    }
  }
  &__modal {
    &-guest {
      &-signup {
        width: 100%;
        margin-bottom: 16px;
      }
      &-login {
        width: 100%;
      }
    }
  }
  & .c-button.p-user-business-operator-staff-show {
    &__gift-button--shadow {
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.05);
      border: none;

      & span i {
        color: #ffeb3b;
      }
      &.derk.selected {
        background: #ff5694;
        color: var(--white);
      }
    }
  }
}
</style>
