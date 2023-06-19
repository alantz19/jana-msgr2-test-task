<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";
import {defineProps} from "vue";
import FormInput from "../../../Components/FormInput.vue";

interface SmsCompany {
  name: string;
}
let props = defineProps({
  company: Object as () => SmsCompany
});

let form = useForm(props.company)

function submit(){
  form.post('/sms/routing/companies', {
    onSuccess: () => {
      form.reset();
    }
  });
}
</script>

<template>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-pagehstori8">
    <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
    <div class="mx-auto max-w-3xl">
      <PageHeader>
        <template #title>
          Create SMS Company
        </template>
      </PageHeader>
      <!-- Content goes here -->
      <div class=" bg-white border p-8 rounded-xl">
        <form @submit.prevent="submit">
          <FormInput label="Name" :error="form.errors.name" v-model="form.name"></FormInput>
          <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>

          </div>
        </form>
      </div>
    </div>
  </div>
</template>