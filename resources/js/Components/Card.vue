<script setup lang="ts">
import defineProps from "vue";

defineProps({
  title: String,
});
</script>

<template>
  <div class="flex justify-center">

    <div class="border-b border-gray-200 bg-white p-3 rounded-xl w-3/4">

      <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6" v-if="title">
        <h3 class="text-base font-semibold leading-6 text-gray-900">{{ title }}</h3>
      </div>

      <div class="p-3">
        <slot/>
      </div>
    </div>
  </div>

</template>

<style scoped>

</style>