<script lang="ts" setup>
import { ref, computed, inject } from 'vue';
import Button from '@/Components/atom/button/Button/Button.vue';
import { ButtonVariantProp } from '@/Components/atom/button/Button/type';
import Comments from '@/Pages/Admin/Comments.vue';
import DeleteBusinessOperator from '@/Pages/Admin/DeleteBusinessOperator.vue';

type BusinessOperator = {
  businessName: string;
  corporationName: string;
  businessFormName: string;
  businessStatus: string;
};
const isDeleteBusinessOperatorOpen = ref(false);
const showDeleteBUsinessOperator = () => {
  isDeleteBusinessOperatorOpen.value = true;
};
const hideDeleteBusinessOperator = () => {
  isDeleteBusinessOperatorOpen.value = false;
};

const isCommentsDialogOpen = ref(false);
const showCommentsDialog = () => {
  isCommentsDialogOpen.value = true;
};
const hideCommentsDialog = () => {
  isCommentsDialogOpen.value = false;
};

const props = defineProps<{ listOfBusinessOperators: BusinessOperator }>();

console.log(props.listOfBusinessOperators);

const data = props.listOfBusinessOperators;
console.log('datadata', data);
const itemsPerPage = 3;
const currentPage = ref(1);
const arrayToSlice = data; // Handle undefined or null
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return arrayToSlice.slice(start, end);
});
const isCommentsOpen = ref(false);
const totalPages = computed(() => Math.ceil(arrayToSlice.length / itemsPerPage));

const paginator = computed(() => {
  const pages = [];
  for (let i = Math.max(1, currentPage.value - 1); i <= Math.min(totalPages.value, currentPage.value + 1); i++) {
    pages.push(i);
  }
  return pages;
});

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}

function goToPage(pageNumber) {
  currentPage.value = pageNumber;
}
const showComments = () => {
  isCommentsOpen.value = true;
};

const hideComments = () => {
  isCommentsOpen.value = false;
};
const showDetails = (item) => {};
</script>

<template>
  <DeleteBusinessOperator v-if="isDeleteBusinessOperatorOpen" @close-delete="hideDeleteBusinessOperator" />
  <Comments v-if="isCommentsOpen" @close-comments="hideComments" />
  <div>
    <table class="table-fixed">
      <thead class="table-header">
        <tr>
          <th>事業者名</th>
          <th>法人名</th>
          <th>形態</th>
          <th>稼働状況</th>
          <th>公開状況</th>
          <th><span class="hidden">コメント</span></th>
          <th><span class="hidden">削除</span></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in paginatedData" :key="item.id">
          <td hidden>{{ item.id }}</td>
          <td>{{ item.businessName }}</td>
          <td>{{ item.corporationName }}</td>
          <td>{{ item.businessFormName }}</td>
          <td>
            <span v-if="item.businessStatus === 1">稼働</span>
            <span v-if="item.businessStatus === 2">停止</span>
            <span v-if="item.businessStatus === 3">休業</span>
          </td>
          <td>
            <label class="container">
              <input type="checkbox" :checked="item.isPublish" />
              <span class="checkmark"></span>
            </label>
          </td>
          <td>
            <Button
              class="c-message-fix-phrase-button__button"
              :variant="ButtonVariantProp.Info"
              :text="`コメント`"
              @click="showComments()"
            />
          </td>
          <td>
            <Button
              class="c-message-fix-phrase-button__button"
              :variant="ButtonVariantProp.Danger"
              :text="`削除`"
              @click="showDeleteBUsinessOperator()"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <div class="paginator">
      <button @click="prevPage" :disabled="currentPage === 1">◀</button>
      <button
        v-for="pageNumber in paginator"
        :key="pageNumber"
        @click="goToPage(pageNumber)"
        :class="{ active: pageNumber === currentPage }"
      >
        {{ pageNumber }}
      </button>
      <button @click="nextPage" :disabled="currentPage === totalPages"><span class="triangle">▶</span></button>
    </div>
  </div>
</template>

<style>
/* Add your styles here */
.paginator {
  margin-top: 10px;
}

.paginator button {
  margin: 0 5px;
}

.paginator button.active {
  background-color: #007bff;
  color: #fff;
}
.delete-btn {
  color: red;
}
.show-more-details {
  display: block;
  background-color: rgb(75, 75, 157);
  color: white;
  border: 2px solid black;
}
.table-fixed {
  border: 1px gray solid;
  table-layout: fixed;
  width: 75%;
  border-collapse: collapse;
}
.table-header {
  background-color: #a9a8a2;
}

tr {
  justify-content: space-between;
  border: 1px solid black;
}
th,
td {
  padding: 8px;

  width: 150px;
  text-align: center;
  justify-content: center;
}
.hidden {
  display: none;
}
</style>
