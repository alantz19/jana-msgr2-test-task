# MSGR - SMS and Email marketing platform.

API docs are automatically generated at

- http://localhost/docs/api#/ (using [scramble package](https://scramble.dedoc.co/installation))
- generate api's into typescript (runs from frontend) with `openapi-typescript` (further reading at nuxt/readme.md)

# Starting project

Create a .env file from .env.example

# Github

- we use (https://nvie.com/posts/a-successful-git-branching-model/) branching model
-
    - working on feature/<feature> name
-
    - create a PR to merge to development branch when done
-
    - we deploy to production from master branch

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
sail artisan jwt:secret
sail test
npm install
npm run dev
```

# queues

`sail php artisan horizon` to start
`http://v2.local/horizon/dashboard` to view

make sure queues are not retrying automatically

# logs

I use https://lnav.org/ for it
run `lnav storage/logs/laravel.log`
