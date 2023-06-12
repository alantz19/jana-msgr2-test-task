<script lang="ts" setup>
import {defineProps, PropType, ref} from "vue";
import PageHeader from "@/Components/PageHeader.vue";
import Card from "@/Components/Card.vue";
import FormInput from "@/Components/FormInput.vue";
import {InertiaForm, useForm} from "@inertiajs/vue3";
import FormRadioGroup from "../../../Components/FormRadioGroup.vue";
import FormRadio from "../../../Components/FormRadio.vue";
import SmppConnectionSetup from "../../../Partials/routes/SmppConnectionSetup.vue";

let props = defineProps({
  routeCompanies: {
    type: Array as PropType<App.Data.SmsRoutingCompanyViewData[]>,
    required: true,
  },
  smsRoutingRouteCreateData: {
    type: Object as PropType<App.Data.SmsRoutingRouteCreateData>,
    // type: Object as PropType<SmsRoutingRouteCreateData>,
    required: true,
  },
  errors: Object
});
const form: InertiaForm<App.Data.SmsRoutingRouteCreateData> = useForm(
    props.smsRoutingRouteCreateData
);
form.selectedCompanyOption = "new";
const smppConnectionSuccess = ref(false);

function submitForm() {
  form.post("/sms/routing/routes", {
    onSuccess: () => {
      form.reset();
    },
  });
}
</script>

<template>
  <div class="text-sm">
    <PageHeader title="New route"/>
    <form @submit.prevent="submitForm">
      <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
          <!--                  SMS company-->
          <div class="px-4 sm:px-0">
            <h2
                class="text-base font-semibold leading-7 text-gray-900"
            >
              Company
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
              The route will belong to this SMS company. The
              company will be used to calculate balances,
              communbications via email and similar.
            </p>
          </div>

          <Card class="md:col-span-2">
            <fieldset class="">
              <FormRadioGroup>
                <FormRadio
                    v-model="form.selectedCompanyOption"
                    label="New company"
                    value="new"
                >
                  <div class="pl-9 pb-6">
                    <FormInput
                        v-model="form.companyCreateData.name"
                        :error="errors['companyCreateData.name']"
                        autofocus
                        label="Name"
                        required
                    ></FormInput>
                  </div>
                </FormRadio>

                <FormRadio
                    v-model="form.selectedCompanyOption"
                    :disabled="routeCompanies.length === 0"
                    label="Existing company"
                    value="existing"
                >
                  <div class="pl-9 pb-6">
                    <select
                        v-model="form.selectedCompanyId"
                        :class="{
                                                'bg-gray-100':
                                                    routeCompanies.length === 0,
                                            }"
                        :disabled="
                                                routeCompanies.length === 0
                                            "
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                      <option
                          v-for="company in routeCompanies"
                          :key="company.id"
                          :value="company.id"
                      >
                        {{ company.name }}
                      </option>
                    </select>
                  </div>
                </FormRadio>
              </FormRadioGroup>
            </fieldset>
          </Card>

          <!--                  SMPP connection-->
          <div class="px-4 sm:px-0">
            <h2
                class="text-base font-semibold leading-7 text-gray-900"
            >
              Connection
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
              The connection information is used to connect to the
              route to send the SMS through. SMPP IP's to
              whitelist - <b/>
              <span class="space-x-2">
                                <span class="bg-gray-100 text-red-500">
                                  127.0.0.1, 127.0.0.1, 127.0.0.1
                                </span>
                            </span>
            </p>
          </div>

          <Card class="md:col-span-2">
            <SmppConnectionSetup v-model="form.smppConnectionData"
                                 :errors="form.errors"></SmppConnectionSetup>
          </Card>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped></style>