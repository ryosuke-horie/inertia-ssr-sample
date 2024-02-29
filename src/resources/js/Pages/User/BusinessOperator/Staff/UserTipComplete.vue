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

const props = defineProps<{
  businessId: number;
  staffId: number;
}>();

const canvas = ref<HTMLCanvasElement | null>(null);

onMounted(() => {
  if (canvas.value) {
    initSnowflakeAnimation(canvas.value);
  }
});
</script>

<template>
  <BaseLayout
    title="応援完了"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      role: RouteRoleName.User,
      text: '応援完了',
      href: RouteName.UserMypage,
    }"
  >
    <section class="canvas__background">
      <canvas ref="canvas"></canvas>
    </section>
    <div class="p-user-business-operator-staff-tip-complete">
      <img :src="ImageThankyou" class="p-user-business-operator-staff-tip-complete__image" alt="Thank You" />
      <div class="p-user-business-operator-staff-tip-complete__content">
        <div class="p-user-business-operator-staff-tip-complete__message">
          <p class="p-user-business-operator-staff-tip-complete__message-title">ギフトを送りました</p>
          <p class="p-user-business-operator-staff-tip-complete__message-subtitle">ご利用ありがとうございます</p>
        </div>
        <AnkerButton
          :href="
            route(RouteName.UserBusinessOperatorStaffShow, { staffId: props.staffId, businessId: props.businessId })
          "
          :text="`スタッフページに戻る`"
          class="p-user-business-operator-staff-tip-complete__staff-top-button"
        />
      </div>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-business-operator-staff-tip-complete {
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.05);
  background: #fff;
  margin: 30px;
  padding-bottom: 30px;
  border-radius: 10px;
  opacity: 0.9;

  &__image {
    width: 100%;
  }

  &__message {
    text-align: center;
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 20px;
  }

  & a.c-anker-button.primary.p-user-business-operator-staff-tip-complete__staff-top-button {
    width: 70%;
    margin: 0 auto;
    border-radius: 30px;
  }
}

.canvas__background {
  width: 100%;
  display: block;
  position: relative;
}

canvas {
  position: absolute;
  top: 0;
  left: 0;
  background: #f3f3f3;
  z-index: -2;
}
</style>
