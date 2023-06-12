<script setup lang="ts">

import Button from "../../Components/Button.vue";
import FormInput from "../../Components/FormInput.vue";
</script>

<template>

  <FormInput
      v-model="form.smppConnectionData.url"
      :error="form.errors['smppConnectionData.url']"
      label="URL"
  />
  <FormInput
      v-model="form.smppConnectionData.port"
      :error="form.errors['smppConnectionData.port']"
      label="Port"
  />
  <FormInput
      v-model="form.smppConnectionData.username"
      :error="form.errors['smppConnectionData.username']"
      label="Username"
  />
  <FormInput
      v-model="form.smppConnectionData.password"
      :error="form.errors['smppConnectionData.password']"
      label="Password"
  />
  </div>
  <div v-if="form.errors['smppConnectionData.connection_test']"
       class="p-3 border-gray-200 bg-rose-100 mt-5 rounded-xl text-gray-900">
    {{ form.errors['smppConnectionData.connection_test'] }}
  </div>
  <div v-if="smppConnectionSuccess">
    {{ smppConnectionSuccess }}
  </div>
  <div class="float-right">
    <Button :loading="form.processing"
            class="mt-8"
            label="Test connection"
            type="button"
            @click="testSmppConnection"
    />
</template>

<style scoped>

</style>