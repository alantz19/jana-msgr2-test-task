<script lang="ts" setup>
import {
  SmsRouteCompaniesCollection,
  SmsRoutingCompaniesCreatePayload,
  SmsRoutingRoutesCreatePayload,
  SmsRouteCompanyResource,
  SmsRoutingRoutesSmppConnectionsCreatePayload
} from "~/types/data-contracts";
import {useModal, useToast} from "tailvue";
import {Api} from "~/types/Api";

const title = 'Create route';

const selectedCompanyOption = ref('new');
const routeCreate = ref({} as SmsRoutingRoutesCreatePayload)
const routeCreateErrors = ref({})
const companyCreate = ref({} as SmsRoutingCompaniesCreatePayload)
const companyCreateErrors = ref({})
const smppCreate = ref({} as SmsRoutingRoutesSmppConnectionsCreatePayload)
const smppCreateError = ref({})
const routeCompanies = ref({} as SmsRouteCompaniesCollection);
const ratesOption = ref('manual')

const api = new Api();

function submit() {
  if (selectedCompanyOption.value === 'new') {
    createCompany(
        testSmppConnection
    );
  } else {
    testSmppConnection();
  }
}

let createRoute = () => {
  api.smsRoutingRoutesCreate(routeCreate.value).then((data) => {
    useToast().success('Route created!')
    useRouter().push("/sms/routing/routes")
  }).catch((error) => {
    routeCreateErrors.value = error.data.errors;
  });
}

let createSmppConnection = (callback) => {
  api.smsRoutingRoutesSmppConnectionsCreate(smppCreate.value).then((response) => {
    useToast().success('SMPP connection created!')
    routeCreate.value.connection_id = response.data.id
    callback();
  }).catch((error) => {
    smppCreateError.value = error.data.errors
  });
}
const refCreateCompanyNameInput = ref(null)

let createCompany = (callback) => {
  api.smsRoutingCompaniesCreate(companyCreate.value).then((response) => {
    useToast().success('Company created!')
    routeCreate.value.sms_route_company_id = response.data.id
    console.log(routeCreate.value.sms_route_company_id)
    selectedCompanyOption.value = 'existing';

    callback();
  }).catch((error) => {
    companyCreateErrors.value = error.data.errors;
    console.log('refCreateCompanyNameInput', refCreateCompanyNameInput.value)
    refCreateCompanyNameInput.value.focus();
  });
}

let testSmppConnection = () => {
  api.smsRoutingRoutesSmppConnectionsTestCreate(smppCreate.value).then((data) => {
    console.log(data)
    createSmppConnection(createRoute);
  }).catch((response) => {
    smppCreateError.value = response.data.errors
    useModal().show({
      type: 'danger',
      title: 'SMPP Connection Error',
      body: 'There was an error with the SMPP connection. Please check the credentials and try again.',
      primary: {
        label: 'OK',
        theme: 'red',
        action: () => {
        },
      }
    });
  });
}

api.smsRoutingCompaniesList().then((response) => {
  routeCompanies.value = response;
}).catch((error) => {
  useToast().warning('Error fetching companies')
});
</script>

<template>
  <NuxtLayout name="app-layout">
    <Head>
      <Title>{{ title }}</Title>
    </Head>

    <PageHeader :title="title"/>
    <div>
      <form @submit.prevent="submit">
        <div class="">
          <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
            <HorizontalForm>
              <template #title>
                Company
              </template>
              <template #description>
                The route will belong to this SMS company. The
                company will be used to calculate balances,
                communbications via email and similar.
              </template>
              <template #content>
                <fieldset class="">
                  <FormRadioGroup>
                    <FormRadio
                        v-model="selectedCompanyOption"
                        label="New company"
                        value="new"
                    >
                      <div ref="refCreateCompanyNameInput" class="pl-9 pb-6" tabindex="0">
                        <FormInput
                            v-model="companyCreate.name"
                            :error="companyCreateErrors.name?.[0]"
                            autofocus
                            label="Company Name"
                            required
                        ></FormInput>
                      </div>
                    </FormRadio>

                    <FormRadio
                        v-model="selectedCompanyOption"
                        :disabled="routeCompanies.data?.length === 0"
                        label="Existing company"
                        value="existing"
                    >
                      <div class="pl-9 pb-6">
                        <FormSelect v-model="routeCreate.sms_route_company_id"
                                    :error="routeCreateErrors.sms_route_company_id"
                                    :items="routeCompanies.data"
                                    label="Select company"/>
                      </div>
                    </FormRadio>
                  </FormRadioGroup>
                </fieldset>
              </template>
            </HorizontalForm>

            <HorizontalForm>
              <template #title>
                Information
              </template>
              <template #description>
              </template>
              <template #content>
                <FormFieldSet>
                  <FormInput v-model="routeCreate.name" :error="routeCreateErrors.name" class="mb-3"
                             label="Route Name" required/>
                </FormFieldSet>
              </template>
            </HorizontalForm>

            <HorizontalForm>
              <template #title>
                Connection
              </template>
              <template #description>
                The connection information is used to connect to the
                route to send the SMS through. SMPP IP's to
                whitelist - <b/>
                <span class="space-x-2">
                    <span class="bg-gray-100 text-red-500">
                      127.0.0.1, 127.0.0.1, 127.0.0.1
                    </span>
                </span>
              </template>
              <template #content>
                <SmppConnectionSetupInlineForm v-model="smppCreate"
                                               :errors="smppCreateError"/>
              </template>
            </HorizontalForm>

            <HorizontalForm>
              <template #title>
                Rates
              </template>
              <template #description>
                A rate is composed by an account, country, network (optional) and price.
              </template>
              <template #content>
                <fieldset class="">
                  <FormRadioGroup>
                    <FormRadio v-model="ratesOption" label="Update rates manually" value="manual">
                      <div class="pl-4 mb-3">
                        Update the rates manually by uploading a CSV file or adding them manually in the
                        <LinkC href="#">rates screen</LinkC>
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
              </template>
            </HorizontalForm>
            <!--          Rates-->
          </div>

          <div class="mt-10 flex items-center justify-end gap-x-6 md:mr-40">
            <Button type="submit">
              Save
            </Button>
          </div>
        </div>
      </form>
    </div>
  </NuxtLayout>
</template>

<style scoped>

</style>