<script lang="ts" setup>
import {Link} from "@inertiajs/vue3";
import Button from "@/Components/Button.vue";

type HeaderButtons = {
  label: string;
  url: string;
  icon?: string;
}

defineProps({
  title: String,
  buttons: Object as () => HeaderButtons[] || Object as () => string[],
});


</script>

<template>
  <div class="sm:flex sm:justify-between sm:items-center mb-12">

    <div class="">
      <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{ title }}</h1>
    </div>
    <div class="grid grid-flow-col sm:auto-cols-max justify-end sm:justify-end space-x-2">
      <div v-for="(button, name ) in buttons">
        <div v-if="!(button instanceof String)">
          <Link :href="button.url">
            <Button :label="button.label"/>
          </Link>
        </div>
        <div v-else>
          <Link :href="button">
            <Button>
              <span class="hidden xs:block ml-2">{{ name }}</span>
              <span v-if="button.icon" v-html="button.icon">
            </span>
            </Button>
          </Link>
        </div>
      </div>
    </div>

  </div>

</template>