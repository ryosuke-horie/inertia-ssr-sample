<script setup lang="ts">
// Vue関連のインポート
import { onMounted, ref } from 'vue';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import AnkerButton from '@/Components/atom/button/AnkerButton/AnkerButton.vue';
import ImageThankyou from '@/../assets/images/img_thankyou.png';
// ユーティリティ
import { initSnowflakeAnimation } from '@/Utilities/snowFlakeAnimation';

const props = defineProps<{ businessId: number; staffId: number }>();

const canvas = ref<HTMLCanvasElement | null>(null);

onMounted(() => {
  if (canvas.value) initSnowflakeAnimation(canvas.value);
});
</script>

<template>
  <BaseLayout
    title="投げ銭完了"
    :is-auth="false"
    :role="RouteRoleName.GuestUser"
    :auth-header="{
      text: '投げ銭完了',
    }"
  >
    <div class="p-user-business-operator-staff-tip-complete">
      <canvas ref="canvas" />
      <div class="p-user-business-operator-staff-tip-complete__container">
        <img :src="ImageThankyou" class="p-user-business-operator-staff-tip-complete__image" alt="Thank You" />
        <div class="p-user-business-operator-staff-tip-complete__content">
          <div class="p-user-business-operator-staff-tip-complete__message">
            <p class="p-user-business-operator-staff-tip-complete__message-title">ギフトを送りました</p>
            <p class="p-user-business-operator-staff-tip-complete__message-subtitle">ご利用ありがとうございます</p>
          </div>
          <AnkerButton
            :href="
              route(RouteName.GuestUserBusinessOperatorStaffShow, {
                staffId: props.staffId,
                businessId: props.businessId,
              })
            "
            text="スタッフページに戻る"
            class="p-user-business-operator-staff-tip-complete__staff-top-button"
          />
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-business-operator-staff-tip-complete {
  position: relative;
  overflow: hidden;
  padding: 16px;
  &__container {
    position: relative;
    max-width: 400px;
    width: 100%;
    margin: 30px auto;
    padding-bottom: 30px;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.05);
    background-color: #ffffff70;
    border-radius: 10px;
    img {
      width: 100%;
    }
  }
  canvas {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: #f3f3f3;
    z-index: 0;
  }

  &__message {
    text-align: center;
    font-weight: var(--bold);
    font-size: 20px;
    margin-bottom: 20px;
  }

  & a.c-anker-button.primary.p-user-business-operator-staff-tip-complete__staff-top-button {
    width: 70%;
    margin: 0 auto;
    border-radius: 30px;
  }
}
</style>
