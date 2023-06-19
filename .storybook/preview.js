/** @type { import('@storybook/vue3').Preview } */
import('../resources/js/css/style.css');

const preview = {
        parameters: {
            actions: {argTypesRegex: "^on[A-Z].*"},
            controls: {
                matchers: {
                    color: /(background|color)$/i,
                    date: /Date$/,
                },
            },
        },
        decorators: [
            (story) => ({
                components: {story},
                template: '<div class="bg-slate-100 p-6"><div class="card p-5 bg-white"><story/></div></div>',
            })]
    };

export default preview;
