import CampaignsTable from './CampaignsTable.vue';

// More on how to set up stories at: https://storybook.js.org/docs/vue/writing-stories/introduction
export default {
    component: CampaignsTable,
    title: 'Sms/Campaigns/Table',
    render: (args) => ({
        components: { CampaignsTable },
        template: '<div class="bg-slate-100 p-6"> <div class="card p-5 bg-white"> <CampaignsTable /> </div> </div>',
    }),
};

// More on writing stories with args: https://storybook.js.org/docs/vue/writing-stories/args
export const Primary = {
    args: {
        primary: true,
        page: {
            url: '/',
        },
        label: 'Button',
    },
};