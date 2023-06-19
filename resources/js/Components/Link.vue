<script lang="ts" setup>
import {Link} from "@inertiajs/vue3";

type Props = {
  href: string,
}

const props = defineProps<Props>();
</script>

<template>
  <Link :href="href" class="text-sm text-indigo-500 hover:text-indigo-600">
    <slot/>
  </Link>
</template>

<style scoped>

</style>