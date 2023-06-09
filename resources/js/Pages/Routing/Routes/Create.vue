<script lang="ts" setup>
import { defineProps, PropType } from "vue";
import PageHeader from "@/Components/PageHeader.vue";
import Card from "@/Components/Card.vue";
import FormInput from "@/Components/FormInput.vue";
import { InertiaForm, useForm } from "@inertiajs/vue3";
import FormSubmitButton from "../../../Components/FormSubmitButton.vue";

type SmsRoutingRouteCreateData = {
    selected_company_option: string;
    selected_company_id: number;
    companyCreateData: App.Data.SmsRoutingCompanyCreateData;
};

interface Props {
    routeCompanies: App.Data.SmsRoutingCompanyViewData[];
    smsRoutingRouteCreateData: App.Data.SmsRoutingRouteCreateData;
}

// const props = defineProps<Props>()
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
});
const form: InertiaForm<App.Data.SmsRoutingRouteCreateData> = useForm(
    props.smsRoutingRouteCreateData
);

function submitForm() {
    form.post("/sms/routing/routes", {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <div>
        <PageHeader title="New route" />
        <form @submit.prevent="submitForm">
            <div class="space-y-10 divide-y divide-gray-900/10">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
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
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input
                                        id="new-company"
                                        v-model="form.selectedCompanyOption"
                                        checked
                                        class="h-4 w-4 border-gray-300 text-indigo-600"
                                        type="radio"
                                        value="new"
                                    />
                                    <label
                                        class="ml-3 block text-sm font-medium leading-6 text-gray-900"
                                        for="new-company"
                                        >New company</label
                                    >
                                </div>
                                <div
                                    v-if="form.selectedCompanyOption === 'new'"
                                    class="pl-9 pb-6 text-sm"
                                >
                                    <FormInput
                                        v-model="form.companyCreateData.name"
                                        :error="
                                            form.errors[
                                                'companyCreateData.name'
                                            ]
                                        "
                                        label="Name"
                                        required
                                    ></FormInput>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        id="existing-company"
                                        v-model="form.selectedCompanyOption"
                                        :class="{
                                            'bg-gray-100':
                                                routeCompanies.length === 0,
                                        }"
                                        :disabled="routeCompanies.length === 0"
                                        class="h-4 w-4 border-gray-300 text-indigo-600"
                                        type="radio"
                                        value="existing"
                                    />
                                    <label
                                        :class="
                                            routeCompanies.length === 0
                                                ? 'text-gray-400'
                                                : 'text-gray-900'
                                        "
                                        class="ml-3 block text-sm font-medium leading-6"
                                        for="existing-company"
                                        >Existing</label
                                    >
                                </div>
                                <div
                                    v-if="
                                        form.selectedCompanyOption ===
                                        'existing'
                                    "
                                >
                                    existing options
                                </div>
                            </div>
                            <FormSubmitButton
                                :disabled="form.processing"
                                class="mt-8"
                            />
                        </fieldset>
                    </Card>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped></style>