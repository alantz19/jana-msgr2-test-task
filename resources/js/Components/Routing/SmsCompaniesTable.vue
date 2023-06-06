<script setup>
import {Link} from "@inertiajs/vue3";
import {PlusIcon} from '@heroicons/vue/20/solid'
defineProps(['companies'])
</script>

<template>
  <div v-if="companies.length === 0" class="flex min-h-full justify-center flex-1 flex-col">
    <div class="text-center">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
           aria-hidden="true">
        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
      </svg>
      <h3 class="mt-2 text-sm font-semibold text-gray-900">No companies</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by creating a new company.</p>
      <div class="mt-6">
        <Link href="/sms/routing/companies/create">
          <button type="button"
                  class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <PlusIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true"/>
            New Company
          </button>
        </Link>
      </div>
    </div>
  </div>
  <div class="bg-white shadow-lg rounded-xl border border-slate-200 relative" v-if="companies.length > 0">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
              <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                </th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
              <tr v-for="company in companies" :key="company.id">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ company.name }}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                  <Link :href="'/sms/routing/companies/' + company.id + '/edit'" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>