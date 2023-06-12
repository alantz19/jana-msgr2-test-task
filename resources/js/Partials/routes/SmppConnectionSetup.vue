<script lang="ts" setup>

import Button from "@/Components/Button.vue";
import FormInput from "@/Components/FormInput.vue";

import {defineProps, PropType, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import axios from "axios";
import Alert from "../../Components/Alert.vue";

type smppConnectionData = App.Data.SmppConnectionData;
const processing = ref(false);
const smppConnectionFail = ref('');

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

const disableConnectionTestButton = ref(false);

function updatedInput(val) {
  errors.value[val] = '';
  smppConnectionFail.value = '';
  smppConnectionSuccess.value = '';
  disableConnectionTestButton.value = false;
}

function testSmppConnection() {
  smppConnectionFail.value = '';
  processing.value = true;
  axios.post("/api/v1/sms/routing/routes/test-smpp-connection", props.modelValue).then(function (response) {
    errors.value = {};

    if (response.data.success) {
      smppConnectionSuccess.value = response.data.message;
      disableConnectionTestButton.value = true;

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
          @input="updatedInput('url')"
      />
      <FormInput
          v-model="modelValue.port"
          :error="errors.port"
          label="Port"
          type="number"
          @change="$emit('update:modelValue.port', $event.target.value);"
          @input="errors.port = ''; smppConnectionFail = ''; smppConnectionSuccess = '';"
      />
      <FormInput
          v-model="modelValue.username"
          :error="errors.username"
          label="Username"
          @change="$emit('update:modelValue.username', $event.target.value);"
          @input="errors.username = ''; smppConnectionFail = ''; smppConnectionSuccess = '';"
      />
      <FormInput
          v-model="modelValue.password"
          :error="errors.password"
          label="Password"
          @change="$emit('update:modelValue.password', $event.target.value);"
          @input="errors.password = ''; smppConnectionFail = ''; smppConnectionSuccess = '';"
      />
    </div>
    <div v-if="smppConnectionFail"
         class="p-3 border-gray-200 bg-rose-100 mt-5 rounded-xl text-gray-900">
      {{ smppConnectionFail }}
    </div>
    <div v-if="smppConnectionSuccess" class="mt-3">
      <Alert :alert="{
        message: smppConnectionSuccess,
        type: 'success',
        dismissible: true,
      }"/>
    </div>
    <div class="float-right">
      <Button :disabled="disableConnectionTestButton"
              :loading="processing"
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