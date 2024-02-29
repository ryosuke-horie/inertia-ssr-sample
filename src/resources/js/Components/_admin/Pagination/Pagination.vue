<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { PaginationProps } from './type';

const props = defineProps<PaginationProps>();

const cleanLabel = (label: string) => {
  return label.replace('&laquo;', '').replace('&raquo;', '');
};
</script>

<template>
  <div class="c-pagination">
    <div class="c-pagination__container">
      <template v-if="props.links.length > 3">
        <Link
          v-for="(link, index) in props.links"
          :key="index"
          :class="['c-pagination__link', { 'c-pagination__link--active': link.active }]"
          :href="link.url"
          :text="cleanLabel(link.label)"
        />
      </template>
    </div>
    <div class="c-pagination__info">Page {{ props.currentPage }} of {{ props.total }}</div>
  </div>
</template>

<style lang="scss" scoped>
.c-pagination {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  &__container {
    display: flex;
    justify-content: center;
    padding: 16px;
  }

  &__link {
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    color: #333;
    border-radius: 4px;
    transition: background-color 0.3s ease;

    &:hover {
      background-color: #e2e6ea;
    }

    &--active {
      background-color: #007bff;
      color: white;
      border-color: #007bff;

      &:hover {
        background-color: #0056b3;
      }
    }
  }

  &__info {
    text-align: end;
    padding: 0.5rem;
    font-size: 0.9rem;
    color: #666;
  }
}
</style>
