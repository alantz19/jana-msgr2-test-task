<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PageHeader from "@/Components/PageHeader.vue";
import Table from "@/Components/Table.vue";
defineProps({
  routes: Array
});

const headers = [
  { text: "Name", value: "name" },
  { text: "Height (cm)", value: "height", sortable: true },
  { text: "Weight (kg)", value: "weight", sortable: true },
  { text: "Age", value: "age", sortable: true }
];
const items = [
  { "name": "Curry", "height": 178, "weight": 77, "age": 20 },
  { "name": "James", "height": 180, "weight": 75, "age": 21 },
  { "name": "Jordan", "height": 181, "weight": 73, "age": 22 }
];
const buttons = [
  { text: "New account", href: "/sms/routing/routes/create" },
]
</script>

<template>
  <PageHeader title="Routes" :buttons="buttons" />

  <Table :items="items" :headers="headers">
    <template #item-name="item">
      <Link :href="'/' + item.age" class="text-indigo-600 hover:text-indigo-900">{{ item.name }}</Link>
    </template>
  </Table>
</template>
