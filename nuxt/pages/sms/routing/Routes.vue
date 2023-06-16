<script lang="ts" setup>

import PageHeader from "~/components/PageHeader.vue";

const items = ref([])
const buttons = ref([])
$larafetch('/api/v1/sms/routing/routes').then((data) => {
  items.value = data.data;
  buttons.value = data.links;
});
</script>

<template>
  <NuxtLayout name="app-layout">
    <Head>
      <Title>Companies</Title>
    </Head>

    <PageHeader :buttons="[
        {url: '/sms/routing/routes/create', label: 'New route'}
    ]" title="Routes"/>

    <Table :empty-state="{
        name: 'Route',
        href: '/sms/routing/routes/create'
    }" :headers="[
        {text: 'Name', value: 'name'},
    ]" :items="items"/>
  </NuxtLayout>
</template>

<style scoped>

</style>