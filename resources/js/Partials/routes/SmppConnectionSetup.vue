<script lang="ts" setup>

import Button from "@/Components/Button.vue";
import FormInput from "@/Components/FormInput.vue";

import {defineProps, PropType, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import axios from "axios";

type smppConnectionData = App.Data.SmppConnectionData;
const processing = ref(false);
const smppConnectionFail = ref(false);

let props = defineProps({
  modelValue: {
    type: Object as PropType<smppConnectionData>,
    required: true,
  },
  errors: Object
});
const smppConnectionSuccess = ref('')
const form = useForm({
  smppConnectionData: props.smppConnectionData
});

let errors = ref(props.errors);

function testSmppConnection() {
  smppConnectionFail.value = false;
  processing.value = true;
  axios.post("/api/v1/sms/routing/routes/test-smpp-connection", props.modelValue).then(function (response) {
    errors.value = {};

    if (response.data.success) {
      smppConnectionSuccess.value = response.data.message;
    } else {
      smppConnectionFail.value = response.data.message;
    }
  }).catch(function (error) {
    console.log(error.response.data);
    errors.value = Object.fromEntries(
        Object.entries(error.response.data.errors).map(([key, value]) => [key, value[0]])
    )
  }).finally(function () {
    processing.value = false;
  });
}
</script>

<template>
  <fieldset class="">
    <div class="space-y-4">
      <FormInput
          v-model="modelValue.url"
          :error="errors.url"
          label="URL"
          @change="$emit('update:modelValue.url', $event.target.value);"
          @input="errors.url = ''"
      />
      <FormInput
          v-model="modelValue.port"
          :error="errors.port"
          label="Port"
          @change="$emit('update:modelValue.port', $event.target.value);"
          @input="errors.port = ''"
      />
      <FormInput
          v-model="modelValue.username"
          :error="errors.username"
          label="Username"
          @change="$emit('update:modelValue.username', $event.target.value);"
          @input="errors.username = ''"
      />
      <FormInput
          v-model="modelValue.password"
          :error="errors.password"
          label="Password"
          @change="$emit('update:modelValue.password', $event.target.value);"
          @input="errors.password = ''"
      />
    </div>
    <div v-if="smppConnectionFail"
         class="p-3 border-gray-200 bg-rose-100 mt-5 rounded-xl text-gray-900">
      {{ smppConnectionFail }}
    </div>
    <div v-if="smppConnectionSuccess">
      {{ smppConnectionSuccess }}
    </div>
    <div class="float-right">
      <Button :loading="processing"
              class="mt-8"
              label="Test connection"
              type="button"
              @click="testSmppConnection"
      />
    </div>
  </fieldset>
</template>

<style scoped>

</style>