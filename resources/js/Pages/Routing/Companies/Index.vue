<script setup>

import FilterButton from "../../../../../dev/templates/mosaic-vue/src/components/DropdownFilter.vue";
import DateSelect from "../../../../../dev/templates/mosaic-vue/src/components/DateSelect.vue";
import SmsCompaniesTable from "../../../Components/Routing/SmsCompaniesTable.vue";
import {Link} from "@inertiajs/vue3";
defineProps(['companies'])
</script>

<template>
  <div class="sm:flex sm:justify-between sm:items-center mb-8">
    <div class="mb-6">
      <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">SMS Companies</h1>
    </div>

    <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2" v-if="companies.length > 0">
      <DateSelect/>
      <FilterButton align="right"/>
      <Link href="/sms/routing/routes/create">
        <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
          <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
            <path
                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
          </svg>
          <span class="hidden xs:block ml-2">New Route</span>
        </button>
      </Link>
      <Link href="/sms/routing/companies/create">
        <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
          <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
            <path
                d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
          </svg>
          <span class="hidden xs:block ml-2">New Company</span>
        </button>
      </Link>
    </div>
  </div>

  <div>
    <SmsCompaniesTable :companies="companies"/>
  </div>

</template>