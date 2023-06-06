# MSGR - SMS and Email marketing platform.


> project docs are at http://v2.local/docs you can access them after starting the project

# Starting project
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
- I couldn't make laravel dusk run within Sail. for now run `php artisan dusk` or try to fix it.. I spent already 3 hours on it, good luck.
- run `npm run dev` from outside sail (not - `sail npm run dev`) as the changes won't be reflected in the webpage.