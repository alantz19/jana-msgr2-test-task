import AccordionBasic from '../resources/js/Components/AccordionBasic.vue';

// More on how to set up stories at: https://storybook.js.org/docs/vue/writing-stories/introduction
export default {
    components: {AccordionBasic},
    title: 'Layout/Sidebar/Sidebar',
    component: AccordionBasic,
    tags: ['autodocs'],
    render: (args) => ({
        component: AccordionBasic,
        setup() {
            return { args };
        },
        template: '<AccordionBasic v-bind="args"> some content </AccordionBasic>',
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