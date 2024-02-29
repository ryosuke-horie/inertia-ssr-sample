<script setup lang="ts">
import { StaffRankingProps } from './types';
import AdminLayout from '@/Layouts/AdminLayout/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<StaffRankingProps>();

const selectedYearMonth = ref(props.yearMonth);

const baseUrl = '/mits-admin/staff-ranking';

// 選択された年月が変更されたときに呼び出される関数
watch(selectedYearMonth, (newValue) => {
  // Inertia.jsを使用してバックエンドにリクエストを送信
  router.visit(`${baseUrl}/${newValue}`, {
    method: 'get',
  });
});
</script>

<template>
  <AdminLayout title="チアペイ管理|" text="スタッフランキング">
    <div class="p-user-ranking__contents">
      <div class="p-user-ranking__month-year">
        <p class="p-user-ranking__month-year-p">対象年月：</p>
        <select v-model="selectedYearMonth">
          <option :value="props.yearMonth">{{ props.yearMonth }}</option>
          <option v-for="option in yearMonthOptions" :value="option.value" :key="option.value">
            {{ option.label }}
          </option>
        </select>
      </div>
      <div class="p-user-ranking__table-container">
        <table class="p-user-ranking__table">
          <thead>
            <tr>
              <th>ランキング</th>
              <th>投げ銭総額</th>
              <th>スタッフ名</th>
              <th>事業者名</th>
            </tr>
          </thead>
          <tbody>
            <!-- TODO: スタッフID、事業者IDを参照してユーザーの画面へ遷移できるようにする(対象画面実装後) -->
            <tr>
              <template v-for="(rank, index) in props.staffRanking" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ rank.totalAmount }}</td>
                <td>{{ rank.staffName }}</td>
                <td>{{ rank.businessName }}</td>
              </template>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<style lang="scss">
.p-user-ranking {
  &__contents {
    margin-top: 24px;
  }
  &__month-year {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    &-p {
      margin-right: 16px;
    }
    &-select {
      width: 120px;
      height: 40px;
      padding: 8px;
      border: 1px solid #e4e7ed;
      border-radius: 4px;
    }
  }
  &__table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    th,
    td {
      text-align: left;
      padding: 12px 16px;
      border-bottom: 1px solid #e4e7ed;
    }
    th {
      background-color: #f5f5f5;
      color: #333;
    }
    tr:hover {
      background-color: #f0f0f0;
    }
    &-actions {
      &--show,
      &--review {
        margin-right: 24px;
      }
    }
  }
}
</style>
