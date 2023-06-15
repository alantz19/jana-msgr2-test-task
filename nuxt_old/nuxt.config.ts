// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    devtools: {enabled: true},
    modules: ['@nuxtjs/tailwindcss', '@nuxt/image', '@nuxtjs/google-fonts',
        '@nuxtjs/auth-next', '@pinia/nuxt'],
    image: {},
    css: [
        '@/assets/css/main.css',
    ],
    axios: {
        proxy: true
    },
    proxy: {
        '/api': {
            target: 'http://v2.local',
            pathRewrite: {'^/api': '/'}
        }
    },
    auth: {
        strategies: {
            'laravelJWT': {
                provider: 'laravel/jwt',
                url: 'http://localhost:3000/api',
                endpoints: {
                    login: {url: '/api/auth/login', method: 'post'},
                    refresh: {url: '/api/auth/refresh', method: 'post'},
                    user: {url: '/api/auth/user', method: 'get'},
                    logout: {url: '/api/auth/logout', method: 'post'}
                },
                token: {
                    property: 'access_token',
                    maxAge: 60 * 60
                },
                refreshToken: {
                    maxAge: 20160 * 60
                },
            },
        }
    },
    googleFonts: {
        families: {
            Roboto: true,
            'Josefin+Sans': true,
            Lato: [100, 300],
            Raleway: {
                wght: [100, 400],
                ital: [100]
            },
        }
    },
})
