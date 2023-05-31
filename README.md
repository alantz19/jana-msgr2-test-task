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
