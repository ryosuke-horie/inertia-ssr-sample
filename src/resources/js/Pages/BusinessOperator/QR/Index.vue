<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import { Link } from '@inertiajs/vue3';
import IconQrShop from '@/../assets/images/icon/icon_qr_shop.png';
import IconQrStaff from '@/../assets/images/icon/icon_qr_staff.png';
</script>

<template>
  <BaseLayout
    title="QRコード"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: 'QRコード',
      href: RouteName.BusinessOperatorMypage,
    }"
  >
    <div class="p-qr-index">
      <Link class="p-qr-index__link" :href="route(RouteName.BusinessOperatorQRBusinessOperator)">
        <div>
          <img :src="IconQrShop" class="p-qr-index__link__image" />
          <p class="p-qr-index__link__text">ショップQRコード</p>
        </div>
      </Link>
      <Link class="p-qr-index__link" :href="route(RouteName.BusinessOperatorQRStaff)">
        <div>
          <img :src="IconQrStaff" class="p-qr-index__link__image" />
          <p class="p-qr-index__link__text">スタッフQRコード</p>
        </div>
      </Link>
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-qr-index {
  width: 100%;
  height: 100vh;
  background: linear-gradient(0deg, #dddddd, #dddddd);
  padding: 20px 20px;
  &__link {
    width: 100%;
    height: 150px;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0px 4px 4px 0px #0000000d;
    cursor: pointer;
    text-align: center;
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    align-items: center;

    &__image {
      margin-bottom: 12px;
    }
    &__text {
      font-size: 16px;
      font-weight: 700;
      line-height: 18px;
      letter-spacing: -0.015em;
      color: #000000;
    }
  }
}
</style>
