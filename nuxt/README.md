# comm.com client

## Introduction

based on - [amrnn90/breeze-nuxt](https://github.com/amrnn90/breeze-nuxt). Nuxt 3 application connecting to Comm.com API.

## Working with API

**Creating request** - `$larafetch('/api/v1/endpoint', { method: 'POST', body: { foo: 'bar' } })` \

**Generating SDK from OpenAPI** - `npm run sta` - it will generate  `types/data-contracts.ts` you can then use
f.e `SmsRoutingRoutesSmppConnectionsCreatePayload` to get the params required for
the `POST /api/v1/sms/routing/routes/smpp-connections` \



