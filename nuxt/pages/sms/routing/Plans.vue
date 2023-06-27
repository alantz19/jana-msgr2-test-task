<script lang="ts" setup>
import PageHeader from "~/components/PageHeader.vue";
import {Api} from "~/types/Api";

const items = ref([])

const api = new Api();

items.value = (await api.smsRoutingPlansList()).data.data;
</script>

<template>
  <NuxtLayout name="app-layout">
    <Head>
      <Title>Plans</Title>
    </Head>

    <PageHeader :buttons="[
        {url: '/sms/routing/plans/create', label: 'New plan'}
    ]" title="Plans"/>

    <Table :empty-state="{
        name: 'Plan',
        href: '/sms/routing/plans/create'
    }" :headers="[
        {text: 'Name', value: 'name'},
    ]" :items="items"/>
  </NuxtLayout>
</template>

<style scoped>

</style>