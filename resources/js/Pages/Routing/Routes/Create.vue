<script setup lang="ts">
import {ref} from "vue";
import PageHeader from "../../../Components/PageHeader.vue";
import Card from "@/Components/Card.vue";

function submitForm() {
  console.log('submitting form');
}

defineProps({
  selectedCompany: String,
  routeCompanies: Array,
});

// let companyType = 'new';
const selectedCompanyOption = ref('');
</script>

<template>
  <div>
    <PageHeader title="Create route" />

    <Card >
      <form @submit.prevent="submitForm">

        <div class="">
          <label class="text-base font-semibold text-gray-900">Route SMS company</label>
          <p class="text-sm text-gray-500">Select the company the route belong to</p>
          <fieldset class="mt-4">
            <legend class="sr-only">Notification method</legend>
            <div class="space-y-4">
              <div class="flex items-center">
                <input v-model="selectedCompanyOption" value="new" id="new-company" checked type="radio" class="h-4 w-4 border-gray-300 text-indigo-600" />
                <label for="new-company" class="ml-3 block text-sm font-medium leading-6 text-gray-900">New company</label>
              </div>
              <div v-if="selectedCompanyOption === 'new'" class="pl-9 text-sm">
                new options
              </div>
              <div class="flex items-center">
                <input :disabled="routeCompanies.length === 0" :class="{'bg-gray-100': routeCompanies.length === 0 }" v-model="selectedCompanyOption" value="existing" id="existing-company" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600" />
                <label :class="routeCompanies.length === 0 ? 'text-gray-400' : 'text-gray-900'" for="existing-company" class="ml-3 block text-sm font-medium leading-6">Existing</label>
              </div>
              <div v-if="selectedCompanyOption === 'existing'">
                existing options
              </div>
            </div>
          </fieldset>
        </div>

      </form>
    </Card>
  </div>
</template>

<style scoped>

</style>