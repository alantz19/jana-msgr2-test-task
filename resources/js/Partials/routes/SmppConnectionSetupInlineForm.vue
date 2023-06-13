<script lang="ts" setup>
import FormInput from "@/Components/FormInput.vue";
import {defineProps, PropType, ref, watch} from "vue";
import {inlineErrorsCleaner, inlineErrorsCleanerType} from "@/utils/formUtils";
import FormFieldSet from "../../Components/FormFieldSet.vue";

type smppConnectionData = App.Data.SmppConnectionData;

let props = defineProps({
  modelValue: {
    type: Object as PropType<smppConnectionData>,
    required: true,
  },
  inline_errors: {
    type: Object as PropType<inlineErrorsCleanerType>,
    required: true,
  },
  // errors: Object
});

const res = inlineErrorsCleaner(props.inline_errors);
let errors = ref({});
watch(() => props.inline_errors, (newValue) => {
  console.log('called..');
  errors.value = inlineErrorsCleaner(newValue);
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
        v-model="modelValue.dlrUrl"
        :error="errors.dlrUrl"
        label="DLR URL"
        required
        @change="$emit('update:modelValue.dlrUrl', $event.target.value);"
        @input="errors.dlrUrl = '';"
    />
    <FormInput
        v-model="modelValue.dlrPort"
        :error="errors.dlrPort"
        label="DLR Port"
        required
        type="number"
        @change="$emit('update:modelValue.dlrPort', $event.target.value);"
        @input="errors.dlrPort = '';"
    />
  </FormFieldSet>
</template>

<style scoped>

</style>