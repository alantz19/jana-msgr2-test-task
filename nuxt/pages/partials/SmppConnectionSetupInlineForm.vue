<script lang="ts" setup>
import FormInput from "@/Components/FormInput.vue";
import {defineProps, PropType} from "vue";
import FormFieldSet from "@/Components/FormFieldSet.vue";
import {SmsRoutingRoutesSmppConnectionsCreatePayload} from "~/types/data-contracts";

let props = defineProps({
  modelValue: {
    type: Object as PropType<SmsRoutingRoutesSmppConnectionsCreatePayload>,
    required: true,
  },
  errors: {
    type: Object as PropType<any>,
    required: true,
  },
});

</script>

<template>
  <FormFieldSet>
    <FormInput
        v-model="modelValue.url"
        :error="errors.url"
        label="URL"
        required
        @change="$emit('update:modelValue.url', $event.target.value);"
        @input="errors.url = ''"
    />
    <FormInput
        v-model="modelValue.port"
        :error="errors.port"
        label="Port"
        required
        type="number"
        @change="$emit('update:modelValue.port', $event.target.value);"
        @input="errors.port = '';"
    />
    <FormInput
        v-model="modelValue.username"
        :error="errors.username"
        label="Username"
        required
        @change="$emit('update:modelValue.username', $event.target.value);"
        @input="errors.username = '';;"
    />
    <FormInput
        v-model="modelValue.password"
        :error="errors.password"
        label="Password"
        required
        @change="$emit('update:modelValue.password', $event.target.value);"
        @input="errors.password = '';"
    />
    <FormInput
        v-model="modelValue.dlr_url"
        :error="errors.dlr_url"
        label="DLR URL"
        @change="$emit('update:modelValue.dlrUrl', $event.target.value);"
        @input="errors.dlr_url = '';"
    />
    <FormInput
        v-model="modelValue.dlr_port"
        :error="errors.dlr_port"
        label="DLR Port"
        type="number"
        @change="$emit('update:modelValue.dlrPort', $event.target.value);"
        @input="errors.dlr_port = '';"
    />
  </FormFieldSet>
</template>

<style scoped>

</style>