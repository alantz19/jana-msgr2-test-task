<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PageHeader from "@/Components/PageHeader.vue";
import Table from "@/Components/Table.vue";

defineProps({
  routes: Array
});

const headers = [
  { text: "Name", value: "name" },
  { text: "Company", value: "smsCompany.name", sortable: true },
];
const buttons = [
  { text: "New Route", href: "/sms/routing/routes/create" },
]
</script>

<template>
  <PageHeader title="Routes" :buttons="buttons" />

  <Table :items="routes" :headers="headers" :empty-state="{name: 'Route', href: '/sms/routing/routes/create'}">
    <template #item-name="item">
      <Link :href="'/' + item.id" class="text-indigo-600 hover:text-indigo-900">{{ item.id }}</Link>
    </template>
  </Table>
</template>
