import {Link} from "@inertiajs/vue3";
import PageHeader from "@/Components/PageHeader.vue";

export default {
    component: PageHeader,
    title: 'Components/Page/Header',
    args: {
        title: 'Page header',
    },

};

export const Title = {
    render: (args, {argTypes}) => ({
        components: {PageHeader, Link},
        setup() {
            return {args};
        },
        template: `
          <PageHeader v-bind="args"></PageHeader>
        `,
    }),
};

export const WithButtons = {
    args: {
        ...Title.args,
        buttons: [
            {
                text: 'Button 1 (icon - HeroIcons)', href: '#', icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
</svg>
`
            },
            {text: 'Button 2', href: '#'},
        ],
    },
};