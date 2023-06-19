<script lang="ts" setup>
import PageHeader from "@/Components/PageHeader.vue";
import Table from "@/Components/Table.vue";

let props = defineProps({
  routes: Object
});

const headers = [
  {text: "Name", value: "name"},
  {text: "Company", value: "company.name", sortable: true},
];
</script>

<template>
  <PageHeader :buttons="routes.links" title="Routes"/>

  <Table :empty-state="{name: 'Route', href: '/sms/routing/routes/create'}" :headers="headers"
         :items="routes.data"></Table>
</template>
