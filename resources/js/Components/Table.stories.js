import Table from './Table.vue';
import {Link} from "@inertiajs/vue3";

// More on how to set up stories at: https://storybook.js.org/docs/vue/writing-stories/introduction
export default {
    component: Table,
    title: 'Components/Table',

};

// More on writing stories with args: https://storybook.js.org/docs/vue/writing-stories/args
export const Default = {
    render: (args, {argTypes}) => ({
        components: { Table, Link },
        setup() {
            return { args };
        },
        template: `
          <Table v-bind="args">
              <template #item-name="item">
                <Link :href="'/' + item.age" class="text-indigo-600 hover:text-indigo-900">{{ item.name }}</Link>
              </template>
          </Table>
        `,
    }),
    args: {
        headers: [
            { text: "Name", value: "name" },
            { text: "Height (cm)", value: "height", sortable: true },
            { text: "Weight (kg)", value: "weight", sortable: true },
            { text: "Age", value: "age", sortable: true }
        ],
        items: [
            { "name": "Curry", "height": 178, "weight": 77, "age": 20 },
            { "name": "James", "height": 180, "weight": 75, "age": 21 },
            { "name": "Jordan", "height": 181, "weight": 73, "age": 22 }
        ],
    },
};

// const WithLinks = {
//     args: {
//         headers: [
//             { text: "Name", value: "name" },
//             { text: "Height (cm)", value: "height", sortable: true },
//             { text: "Weight (kg)", value: "weight", sortable: true },
//             { text: "Age", value: "age", sortable: true }
//         ],
//         items: [
//             { "name": "Curry", "height": 178, "weight": 77, "age": 20 },
//             { "name": "James", "height": 180, "weight": 75, "age": 21 },
//             { "name": "Jordan", "height": 181, "weight": 73, "age": 22 }
//         ],
//     },
// };

// export {Default, WithLinks}