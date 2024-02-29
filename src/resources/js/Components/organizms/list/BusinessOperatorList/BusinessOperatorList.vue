<script setup lang="ts">
import { BusinessOperatorListProps } from './type';
import BusinessOperatorListItem from '@/Components/molecules/list/BusinessOperatorListItem/BusinessOperatorListItem.vue';

const props = defineProps<BusinessOperatorListProps>();
</script>

<template>
  <ul class="c-business-operator-list">
    <BusinessOperatorListItem
      v-for="(item, index) of props.list"
      :key="index"
      :id="item.id"
      :name="item.name"
      :images="item.images"
      :city="item.city"
      :href="item.href"
    />
  </ul>
</template>

<style lang="scss"></style>
