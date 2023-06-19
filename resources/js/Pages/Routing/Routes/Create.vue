<script lang="ts" setup>
import {defineProps, PropType, ref} from "vue";
import PageHeader from "@/Components/PageHeader.vue";
import Card from "@/Components/Card.vue";
import FormInput from "@/Components/FormInput.vue";
import {useForm} from "@inertiajs/vue3";
import FormRadioGroup from "../../../Components/FormRadioGroup.vue";
import FormRadio from "../../../Components/FormRadio.vue";
import SmppConnectionSetup from "@/Partials/routes/SmppConnectionSetupInlineForm.vue";
import Link from "@/Components/Link.vue";
import Button from "../../../Components/Button.vue";
import Modal from "../../../Components/Modal.vue";
import FormFieldSet from "../../../Components/FormFieldSet.vue";
import FormSelect from "../../../Components/FormSelect2.vue";
import {SmsRouteCompany} from "@/../ts/types/model.ts";
import {SmsRoutingCompanyCreateRequest, SmsRoutingRouteCreateRequest} from "@/../ts/types/formRequests.ts";

let props = defineProps({
  routeCreateRequest: {
    type: Object as PropType<SmsRoutingRouteCreateRequest>,
    required: true,
  },
  routeCompanyRequest: {
    type: Object as PropType<SmsRoutingCompanyCreateRequest>,
    required: true,
  },

  routeCompanies: {
    type: Object as PropType<SmsRouteCompany[]>,
    required: true,
  },
  selectedCompanyOption: {
    type: String,
    default: 'new',
  },
  errors: Object
});
let formRouteCreate = useForm(
    props.routeCreateRequest
);
let formCompanyCreate = useForm(
    props.routeCompanyRequest
);

const submitCount = ref(0);

function submitForm() {
  if (props.selectedCompanyOption === "new") {
    formCompanyCreate.post('/api/v1/sms/routing/companies', {
      onSuccess: () => {
        formRouteCreate.selectedCompanyId = formCompanyCreate.data.id;
      },
      onError: () => {
        submitCount.value++;
      },
    });
  } else {
    formRouteCreate.smsRoute.sms_route_company_id = formRouteCreate.selectedCompanyId;
  }
  formRouteCreate.post("/sms/routing/routes", {
    onSuccess: () => {
      formRouteCreate.reset();
    },
    onError: () => {
      submitCount.value++;
    },
  });
}

const ratesOption = ref('manual');
</script>

<template>
  <div class="text-sm">
    <PageHeader title="New route"/>
    <form-route-create @submit.prevent="submitForm">
      <div class="">
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
                    v-model="selectedCompanyOption"
                    label="New company"
                    value="new"
                >
                  <div class="pl-9 pb-6">
                    <FormInput
                        v-model="formRouteCreate.companyCreateData.name"
                        :error="errors['companyCreateData.name']"
                        autofocus
                        label="Company Name"
                        required
                    ></FormInput>
                  </div>
                </FormRadio>

                <FormRadio
                    v-model="formRouteCreate.selectedCompanyOption"
                    :disabled="routeCompanies.data.length === 0"
                    label="Existing company"
                    value="existing"
                >
                  <div class="pl-9 pb-6">
                    <FormSelect v-model="formRouteCreate.selectedCompanyId"
                                :error="formRouteCreate.errors.selectedCompanyId"
                                :items="routeCompanies.data"
                                label="Select company"/>
                  </div>
                </FormRadio>
              </FormRadioGroup>
            </fieldset>
          </Card>
          <!--                  SMS company-->
          <div class="px-4 sm:px-0">
            <h2
                class="text-base font-semibold leading-7 text-gray-900"
            >
              Information
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">

            </p>
          </div>

          <Card class="md:col-span-2">
            <FormFieldSet>
              <FormInput v-model="formRouteCreate.name" :error="formRouteCreate.errors.name" class="mb-3"
                         label="Route Name" required/>
              <FormInput v-model="formRouteCreate.description" :error="formRouteCreate.errors.description"
                         label="Route Description"/>
            </FormFieldSet>
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
            <SmppConnectionSetup v-model="formRouteCreate.smppConnectionData"
                                 :inline_errors="{
              errors: formRouteCreate.errors,
              keyToModify: 'smppConnectionData',
                                 }"/>
            <Modal v-if="!!errors.smppConnectionError"
                   :key="submitCount"
                   :open="!!errors.smppConnectionError"
                   :title="errors.smppConnectionError"
                   message="Please attempt again by modifying the SMPP information. Additionally, it may be necessary to whitelist the IP addresses of the platform to establish a connection. If you need further assistance, please reach out to our support team."
                   type="error"/>
          </Card>

          <!--          Rates-->
          <div class="px-4 sm:px-0">
            <h2
                class="text-base font-semibold leading-7 text-gray-900"
            >
              Rates
            </h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">
              A rate is composed by an account, country, network (optional) and price.
            </p>
          </div>

          <Card class="md:col-span-2">
            <fieldset class="">
              <FormRadioGroup>
                <FormRadio v-model="ratesOption" label="Update rates manually" value="manual">
                  <div class="pl-4 mb-3">
                    Update the rates manually by uploading a CSV file or adding them manually in the
                    <Link href="#">rates screen</Link>
                    .
                  </div>
                </FormRadio>
                <FormRadio v-model="ratesOption" label="Update rates via email" value="email">
                  <div class="pl-4 mb-3">
                    Update the rates by sending an email to the inbox rates email address. (todo) . The email should
                    contain a CSV file with the rates.
                  </div>
                </FormRadio>
              </FormRadioGroup>
            </fieldset>
          </Card>
        </div>

        <div class="mt-10 flex items-center justify-end gap-x-6 md:mr-40">
          <Button type="submit">
            Save
          </Button>
        </div>
      </div>
    </form-route-create>
  </div>
</template>

<style scoped></style>