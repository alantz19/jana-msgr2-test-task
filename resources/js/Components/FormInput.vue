<script lang="ts" setup>
import defineProps, { ref } from "vue";

defineEmits(["update:modelValue"]);

interface FormInputProps {
    label: string;
    modelValue: string | Null;
    error: string | Null;
    required?: boolean;
}

const props = defineProps<FormInputProps>();

let model = ref(props.model);
</script>

<template>
    <label
        :for="$.uid"
        class="block text-sm font-medium leading-6 text-gray-900"
        >{{ label }} <span v-if="required" class="text-rose-500">*</span></label
    >
    <div class="mt-2">
        <input
            :id="$.uid"
            :class="{ 'ring-rose-500': error }"
            :value="modelValue"
            autofocus
            class="block w-full rounded-md py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <p v-if="error" class="mt-2 text-sm text-red-600" v-text="error"></p>
    </div>
</template>

<style scoped></style>