# MSGR - SMS and Email marketing platform.

> project docs are at http://v2.local/docs you can access them after starting the project

# Starting project

Create a .env file from .env.example

```bash
cp .env.example .env
```

Check if correct binary for your architecture is specified in /docker/clickhouse/Dockerfile
Look for the following two lines and comment/uncomment the correct one

```bash
#ARG single_binary_location_url="https://builds.clickhouse.com/master/amd64/clickhouse"

# Apple chip solution - unomment the line above and use this instead.
ARG single_binary_location_url="https://builds.clickhouse.com/master/aarch64/clickhouse"
````

Install dependencies, start the project and run tests

```bash
composer install
sail up -d
sail artisan migrate:fresh --seed
sail test
npm install
npm run dev
```

Start storybook by running `npm run storybook` (outside of sail) and access it at http://localhost:6006

# bugs

- I couldn't make laravel dusk run within Sail. for now run `php artisan dusk` or try to fix it.. I spent already 3
  hours on it, good luck.
- run `npm run dev` from outside sail (not - `sail npm run dev`) as the changes won't be reflected in the webpage.
- in `http://v2.local/sms/routing/routes/create` the select FormSelect doesn't update modelValue, maybe because it's an
  object?

# tips #

**Converting Data PHP objects to TypeScript**

when creating a form on frontend create a data object (App/Data/*) add `/** @typescript */` to the top of the class then
run `php artisan typescript:transform` to generate the typescript interface for the data object. (example
in `App/Data/SmsRoutingRouteCreateData` and `resources/types/generated.ts`)

To generate typescript from models we use `https://github.com/7nohe/laravel-typegen`
run `sail npm run typegen --form-requests` to generate typescript interfaces for models.