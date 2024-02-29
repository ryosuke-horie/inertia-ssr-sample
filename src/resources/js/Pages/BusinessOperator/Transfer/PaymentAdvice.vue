<script lang="ts" setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { RouteName, RouteRoleName } from '@/Utilities';
import FormSelectBox from '@/Components/atom/form/FormSelectBox/FormSelectBox.vue';
import { ref, onMounted, computed, watch } from 'vue';
import Paragraph from '@/Components/atom/typograph/Paragraph/Paragraph.vue';
import Divider from '@/Components/atom/divider/Divider.vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import LoadingModal from '@/Components/atom/loading/LoadingModal/LoadingModal.vue';
import { BUSINESSOPERATORApi } from '@/api';
import { configuration } from '@/lib/configuration';

const props = defineProps<{
  transferRequests: {
    requestId: number;
    yearMonth: string;
    updatedAt: string;
    transferAmount: number;
    transactionPeriodFrom: string;
    transactionPeriodTo: string;
    transferRequestAmount: number;
    paymentFee: number;
    usageFee: number;
    transferFeeAmount: number;
    paymentDate: string;
    bankName: string;
    branchName: string;
    accountNumber: string;
  }[];
}>();

const options = computed(() =>
  props.transferRequests.map((item) => ({
    value: item.yearMonth,
    label: item.yearMonth,
  })),
);

const selectedTransferRequest = ref<{
  requestId: number;
  yearMonth: string;
  updatedAt: string;
  transferAmount: number;
  transactionPeriodFrom: string;
  transactionPeriodTo: string;
  transferRequestAmount: number;
  paymentFee: number;
  usageFee: number;
  transferFeeAmount: number;
  paymentDate: string;
  bankName: string;
  branchName: string;
  accountNumber: string;
}>();

const targetDate = ref();
const isLoading = ref(false);

watch(targetDate, (newVal) => {
  const selectedData = props.transferRequests.find((item) => item.yearMonth === newVal);
  if (selectedData) {
    selectedTransferRequest.value = { ...selectedData };
  }
});

onMounted(() => {
  if (props.transferRequests.length > 0) {
    targetDate.value = props.transferRequests[0].yearMonth;
  }
});

const createPdf = async (): Promise<void> => {
  if (selectedTransferRequest.value == null) {
    return;
  }
  await new BUSINESSOPERATORApi(configuration)
    .postBusinessOperatorTransferPaymentAdvicePdf({
      requestId: selectedTransferRequest.value.requestId,
    })
    .then((res) => {
      // ダウンロード用のリンクを作成し、クリックしてダウンロード
      console.log(res);
      const link = document.createElement('a');
      link.href = res.data.filePath;
      link.setAttribute('download', res.data.fileName);
      document.body.appendChild(link);
      link.click();

      // ファイル削除
      deleteFile(res.data.filePath);
    });
};

const deleteFile = async (filePath: string): Promise<void> => {
  await new BUSINESSOPERATORApi(configuration)
    .deleteBusinessOperatorFile({ filePath })
    .then((res) => {
      console.log(res);
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const onClick = () => {
  isLoading.value = true;
  createPdf();
};
</script>
<template>
  <LoadingModal v-if="isLoading" />
  <BaseLayout
    title="支払通知書"
    :is-auth="true"
    :role="RouteRoleName.Business"
    :auth-header="{
      text: '支払通知書',
      href: RouteName.BusinessOperatorTransferSelect,
    }"
  >
    <Paragraph v-if="props.transferRequests.length === 0" class="p-payment-advice-null"
      >振込申請の履歴がございません。</Paragraph
    >
    <div class="p-payment-advice" v-if="props.transferRequests.length > 0">
      <div class="p-payment-advice__search">
        <FormSelectBox
          id="sizeSelectBox"
          class="p-payment-advice__search__select-box"
          :options="options"
          v-model="targetDate"
        />
      </div>
      <div class="p-payment-advice__container" v-if="selectedTransferRequest">
        <Paragraph class="p-payment-advice__container__created-at"
          >発行日：{{ selectedTransferRequest.updatedAt }}</Paragraph
        >
        <div class="p-payment-advice__container__payment">
          <Paragraph class="p-payment-advice__container__payment__title">お支払金額（税込）</Paragraph>
          <Paragraph class="p-payment-advice__container__payment__value"
            >￥{{ selectedTransferRequest.transferAmount.toLocaleString() }}</Paragraph
          >
        </div>
        <Divider class="p-payment-advice__divider" />
        <div class="p-payment-advice__container__detail">
          <Paragraph>投げ銭（全件）</Paragraph>
          <div class="p-payment-advice__container__detail__container">
            <Paragraph
              >{{ selectedTransferRequest.transactionPeriodFrom }}～{{
                selectedTransferRequest.transactionPeriodTo
              }}</Paragraph
            >
            <Paragraph class="p-payment-advice__container__detail__container__value"
              >￥{{ selectedTransferRequest.transferRequestAmount.toLocaleString() }}</Paragraph
            >
          </div>
          <div class="p-payment-advice__container__detail__container">
            <Paragraph>利用料（20%）</Paragraph>
            <Paragraph class="p-payment-advice__container__detail__container__red-value"
              >￥-{{ selectedTransferRequest.usageFee.toLocaleString() }}</Paragraph
            >
          </div>
          <div class="p-payment-advice__container__detail__container">
            <Paragraph>決済手数料（3.6%）</Paragraph>
            <Paragraph class="p-payment-advice__container__detail__container__red-value"
              >￥-{{ selectedTransferRequest.paymentFee.toLocaleString() }}</Paragraph
            >
          </div>
          <div class="p-payment-advice__container__detail__container">
            <Paragraph>振込手数料</Paragraph>
            <Paragraph class="p-payment-advice__container__detail__container__red-value"
              >￥-{{ selectedTransferRequest.transferFeeAmount.toLocaleString() }}</Paragraph
            >
          </div>
          <div class="p-payment-advice__container__detail__container">
            <Paragraph>消費税（振込手数料）</Paragraph>
            <Paragraph class="p-payment-advice__container__detail__container__red-value">￥-30</Paragraph>
          </div>
        </div>
        <div class="p-payment-advice__container__schedule">
          <Paragraph>お支払予定日</Paragraph>
          <Paragraph class="p-payment-advice__container__schedule__value">{{
            selectedTransferRequest.paymentDate
          }}</Paragraph>
        </div>
        <Divider class="p-payment-advice__divider" />
        <div class="p-payment-advice__container__bank-account">
          <Paragraph>指定口座</Paragraph>
          <div class="p-payment-advice__container__bank-account__value">
            <Paragraph
              >{{ selectedTransferRequest.bankName }}&nbsp;&nbsp;{{ selectedTransferRequest.branchName }}</Paragraph
            >
            <Paragraph class="p-payment-advice__container__bank-account__value__number">{{
              selectedTransferRequest.accountNumber
            }}</Paragraph>
          </div>
        </div>
        <Divider class="p-payment-advice__divider" />
        <Paragraph class="p-payment-advice__container__annotation">
          ※支払通知書の詳細はPDFをダウンロードの上、ご確認ください。
        </Paragraph>
        <Button class="p-payment-advice__container__button" text="PDFダウンロード" @click="onClick" />
      </div>
    </div>
  </BaseLayout>
</template>
<style lang="scss">
.p-payment-advice {
  &__search {
    background-color: #eeeeee;
    width: 100%;
    padding: 10px 20px;
    text-align: center;
    &__select-box {
      text-align: center;
      background-color: #eeeeee;
      box-shadow: 0px 0px 10px 0px #0000000d;
      border-radius: 5px;
      padding: 10px;
      width: 100%;
      font-size: 20px;
      font-weight: 700;
      line-height: 22px;
      letter-spacing: -0.015em;
    }
  }
  &__container {
    padding: 20px 20px;
    &__created-at {
      font-size: 16px;
      font-weight: 700;
      letter-spacing: -0.015em;
    }
    &__payment {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      padding-top: 20px;
      &__value {
        font-size: 32px;
        font-weight: 700;
        letter-spacing: -0.015em;
      }
    }
    &__detail {
      background-color: #eeeeee;
      margin-top: 20px;
      padding: 10px;
      &__container {
        display: flex;
        justify-content: space-between;
        &__value {
          font-weight: 700;
          letter-spacing: -0.015em;
        }
        &__red-value {
          font-weight: 700;
          letter-spacing: -0.015em;
          color: #ff0000 !important;
        }
      }
    }
    &__schedule {
      display: flex;
      justify-content: space-between;
      padding-top: 30px;
      &__value {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.015em;
      }
    }
    &__bank-account {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      padding-top: 30px;
      &__value {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: -0.015em;
        &__number {
          text-align: right;
        }
      }
    }
    &__remarks {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      padding-top: 30px;
      &__value {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.015em;
      }
    }
    &__annotation {
      padding: 20px 0px;
      font-size: 14px;
      font-weight: 300;
      letter-spacing: -0.015em;
    }
    &__button {
      width: 100%;
      background-color: #333333 !important;
    }
  }
  &__divider {
    border-bottom: 2px solid #000000;
  }
}
.p-payment-advice-null {
  padding-top: 20px;
}
</style>
