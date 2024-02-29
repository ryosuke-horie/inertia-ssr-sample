<script setup lang="ts">
// Vue関連のインポート
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
// ユーティリティ
import { RouteName, RouteRoleName } from '@/Utilities';
// レイアウトコンポーネント
import BaseLayout from '@/Layouts/BaseLayout.vue';
// UIコンポーネント
import Alert from '@/Components/atom/alert/Alert.vue';
import PointChargeListItem from '@/Components/molecules/list/PointChargeListItem/PointChargeListItem.vue';
import PointChargeAgeConfirmModal from '@/Components/organizms/point/PointChargeAgeConfirmModal/PointChargeAgeConfirmModal.vue';
import iconCoin1 from '@/../assets/images/icon/coin/icon_coin1.png';
import iconCoin2 from '@/../assets/images/icon/coin/icon_coin2.png';
import iconCoin3 from '@/../assets/images/icon/coin/icon_coin3.png';
import iconCoin4 from '@/../assets/images/icon/coin/icon_coin4.png';
import iconCoin5 from '@/../assets/images/icon/coin/icon_coin5.png';
import iconCoin6 from '@/../assets/images/icon/coin/icon_coin6.png';

type amount = {
  settingId: number;
  amount: number;
  freeAmount: number;
};

const props = defineProps<{ totalPoint: number; ageLimitPoint: number; amounts: amount[] }>();

const form = useForm<{ amount: number; freeAmount: number; ageLimitPoint: number }>({
  amount: 0,
  freeAmount: 0,
  ageLimitPoint: props.ageLimitPoint,
});

const ageLimitPointText = `今月の残り購入限度額：￥${props.ageLimitPoint}`;
const maxPoint = 20000;

const showModal = ref(true);
const setShowModal = (val: boolean): void => {
  showModal.value = val;
};

const onSubmit = (amount: number, freeAmount: number): void => {
  form.amount = amount;
  form.freeAmount = freeAmount;
  form.post(route(RouteName.UserPointChargePaymentSelect));
};

const getSrc = (index: number) => {
  const src = [iconCoin1, iconCoin2, iconCoin3, iconCoin4, iconCoin5, iconCoin6];
  return src[index] ?? iconCoin6;
};
</script>

<template>
  <BaseLayout
    title="ポイント購入"
    :is-auth="true"
    :role="RouteRoleName.User"
    :auth-header="{
      text: 'ポイント購入',
      href: RouteName.UserMypage,
    }"
  >
    <div class="p-user-point-charge">
      <div v-if="props.ageLimitPoint <= maxPoint">
        <Alert :text="ageLimitPointText" />
      </div>
      <div v-if="form.errors.amount || form.errors.freeAmount" class="p-user-point-charge__error">
        <Alert text="表示されている金額を選択してください。" />
      </div>
      <div v-if="form.errors.ageLimitPoint" class="p-user-point-charge__error">
        <Alert text="年齢制限以上の金額は購入できません。" />
      </div>
      <PointChargeListItem
        v-for="(item, index) in props.amounts"
        :key="item.amount + index"
        :src="getSrc(index)"
        :amount="item.amount"
        :free-amount="item.freeAmount"
        :age-limit-point="props.ageLimitPoint"
        @click="onSubmit(item.amount, item.freeAmount)"
      />
      <div class="p-user-point-charge__points">
        <div class="p-user-point-charge__points-detail">
          <span class="p-user-point-charge__points-label">保有ポイント</span>
          <div class="p-user-point-charge__points-count">
            <i class="p-user-point-charge__points-count-icon fa-brands fa-product-hunt" />
            <span class="p-user-point-charge__points-count-value">{{ props.totalPoint.toLocaleString() }}</span>
          </div>
        </div>
        <Link :href="route(RouteName.UserPointHistory)" class="p-user-point-charge__points-link">ポイント履歴</Link>
      </div>
      <PointChargeAgeConfirmModal v-if="showModal" :is-guest-user="false" @click="setShowModal(false)" />
    </div>
  </BaseLayout>
</template>

<style lang="scss">
.p-user-point-charge {
  position: relative;
  &__error {
    padding: 24px 16px;
    border-bottom: solid 1px #ddd;
  }
  &__points {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1;
    padding: 10px 30px;
    box-sizing: border-box;
    opacity: 0.9;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    background: var(--black);
    color: var(--white);
    &-label {
      color: var(--white);
      font-size: 13px;
      margin-bottom: 5px;
    }
    &-count {
      &-icon {
        color: #c0deff;
        margin-right: 5px;
      }
      &-value {
        color: var(--yellow);
      }
    }
    &-link {
      background: var(--white);
      color: #000;
      padding: 5px 10px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      font-size: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 1);
    }
  }
}
</style>
