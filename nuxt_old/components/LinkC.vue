<script lang="ts" setup>
defineProps({
  href: {
    type: String,
    required: true,
  },
});
</script>

<template>
  <NuxtLink :href="href" class="font-medium leading-6 text-indigo-600 hover:text-indigo-500">
    <slot/>
  </NuxtLink>
</template>

<style scoped>

</style>