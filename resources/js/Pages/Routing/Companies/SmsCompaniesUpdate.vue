<script setup>
import PageHeader from "@/Components/PageHeader.vue";
import {useForm} from "@inertiajs/vue3";
import {defineProps} from "vue";

let props = defineProps({
  company: Object
});
let form = useForm(props.company.data)

function submit() {
  form.patch('/sms/routing/companies/' + props.company.id, {
    onSuccess: () => {
      form.reset();
    }
  });
}

function back() {
  window.history.back();
}
</script>

<template>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
    <div class="mx-auto max-w-3xl">
      <PageHeader>
        <template #title>
          Update SMS Company - <span v-text="form.name"></span>
        </template>
      </PageHeader>
      <!-- Content goes here -->
      <div class=" bg-white border p-8 rounded-xl">
        <form @submit.prevent="submit">
          <label class="block text-sm font-medium leading-6 text-gray-900" for="name">Name <span
              class="text-rose-500">*</span></label>
          <div class="mt-2">
            <input id="name" v-model="form.name" :class="{'border-rose-500': form.errors.name}" autofocus
                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                   name="name"/>
            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600" v-text="form.errors.name"></p>
          </div>

          <div class="mt-6 flex items-center justify-end gap-x-6">
            <button class="text-sm font-semibold leading-6 text-gray-900" type="button" @click="back">Cancel</button>
            <button class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    type="submit">
              Save
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>
</template>