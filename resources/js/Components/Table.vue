<script setup>
import Vue3EasyDataTable from "vue3-easy-data-table";
defineProps({
  items: Array,
  headers: Array
});
</script>

<template>
  <Vue3EasyDataTable :items="items" :headers="headers"
                     :theme-color="'rgb(99 102 241)'"
  >
    <slot/>
  </Vue3EasyDataTable>
</template>

<style scoped>
.vue3-easy-data-table {
  --easy-table-border: 1px solid #e5e7eb;
  --easy-table-row-border: 1px solid #e5e7eb;

  --easy-table-header-font-size: 0.875rem;
  --easy-table-header-height: 1.25rem;
  --easy-table-header-font-color: #111827;
  --easy-table-header-background-color: white;

  --easy-table-header-item-padding: 0.875rem 0.75rem;

  --easy-table-body-even-row-font-color: #111827;
  --easy-table-body-even-row-background-color: white;

  --easy-table-body-row-font-color: #111827;
  --easy-table-body-row-background-color: white;
  --easy-table-body-row-height: 1.25rem;
  --easy-table-body-row-font-size: 0.875rem;

  --easy-table-body-row-hover-font-color: #111827;
  --easy-table-body-row-hover-background-color: white;

  --easy-table-body-item-padding: 1rem 0.75rem;

  --easy-table-footer-background-color: white;
  --easy-table-footer-font-color: #111827;
  --easy-table-footer-font-size: 0.875rem;
  --easy-table-footer-padding: 0px 10px;
  --easy-table-footer-height: 50px;

  --easy-table-rows-per-page-selector-width: 70px;
  --easy-table-rows-per-page-selector-option-padding: 10px;
  --easy-table-rows-per-page-selector-z-index: 1;

  --easy-table-scrollbar-track-color: #2d3a4f;
  --easy-table-scrollbar-color: #2d3a4f;
  --easy-table-scrollbar-thumb-color: #4c5d7a;;
  --easy-table-scrollbar-corner-color: #2d3a4f;

  --easy-table-loading-mask-background-color: #2d3a4f;
  border-radius: 10px;
}
.vue3-easy-data-table__main{
  border-radius: 10px 10px 0 0;
}
.vue3-easy-data-table__footer{
  border-radius: 0 0 10px 10px;
}
</style>