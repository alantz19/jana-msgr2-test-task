<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
import Button from "@/Components/Button.vue";
interface HeaderButtons {
  text: string;
  href: string;
  icon?: string;
}

defineProps({
  title: String,
  buttons: Array as () => HeaderButtons[],
});


</script>

<template>
  <div class="sm:flex sm:justify-between sm:items-center mb-8">

    <div class="mb-6">
      <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{ title }}</h1>
    </div>
    <div class="grid grid-flow-col sm:auto-cols-max justify-end sm:justify-end space-x-2">
      <div v-if="buttons" v-for="button in buttons">
        <Link :href="button.href">
          <Button>
            <span v-if="button.icon" v-html="button.icon">
            </span>
            <span class="hidden xs:block ml-2">{{ button.text }}</span>
          </Button>
        </Link>
      </div>
    </div>

  </div>

</template>