<script lang="ts" setup>
import {PropType} from "vue";
import {CheckCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XCircleIcon} from '@heroicons/vue/20/solid'

type alertProps = {
  message: string;
  type: string;
  dismissible: boolean;
  dismiss?: () => void;
}

defineProps({
  alert: {
    type: Object as PropType<alertProps>,
    required: true,
  }
});
</script>

<template>
  <div :class="{
    'bg-green-50': alert.type === 'success',
    'bg-yellow-50': alert.type === 'warning',
    'bg-red-50': alert.type === 'error',
    'bg-blue-50': alert.type === 'info',
  }"
       class="rounded-md p-4"
  >
    <div class="flex">
      <div class="flex-shrink-0">
        <CheckCircleIcon v-if="alert.type === 'success'" aria-hidden="true" class="h-5 w-5 text-green-400"/>
        <XCircleIcon v-if="alert.type === 'error'" aria-hidden="true" class="h-5 w-5 text-red-400"/>
        <InformationCircleIcon v-if="alert.type === 'info'" aria-hidden="true" class="h-5 w-5 text-blue-400"/>
        <ExclamationTriangleIcon v-if="alert.type === 'warning'" aria-hidden="true" class="h-5 w-5 text-yellow-400"/>
      </div>
      <div class="ml-3">
        <p :class="{
          'text-green-800': alert.type === 'success',
          'text-red-800': alert.type === 'error',
          'text-blue-800': alert.type === 'info',
          'text-yellow-800': alert.type === 'warning',
        }"
           class="text-sm"
        >{{ alert.message }}</p>
      </div>
      <div v-if="alert.dismissible" class="ml-auto pl-3">
        <div class="-mx-1.5 -my-1.5">
          <button
              :class="{
            'bg-green-50 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50': alert.type === 'success',
            'bg-red-50 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50': alert.type === 'error',
            'bg-blue-50 text-blue-500 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-blue-50': alert.type === 'info',
            'bg-yellow-50 text-yellow-500 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2 focus:ring-offset-yellow-50': alert.type === 'warning',
              }"
              class="inline-flex rounded-md p-1.5"
              type="button">
            <span class="sr-only">Dismiss</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>