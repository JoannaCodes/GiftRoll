# GiftRoll

GiftRoll is a free, accountless gift exchange website. Hosts create an exchange, share an invite link, and use a private creator code to manage the exchange and close sign-ups. Participants use their invite and private reveal links without creating accounts.

## Technology stack

- **Laravel 12 / PHP 8.2+** for pages, exchange logic, and protected creator actions.
- **Vite** for building the front-end assets used by Laravel.
- **Supabase Postgres** for hosted exchange data. Laravel connects to the database server-side; database credentials stay in environment variables and are never sent to browsers.
- **GitHub** for source control and collaboration.
- **A PHP-capable host** for the live website. GitHub Pages cannot execute Laravel/PHP, so it is not the web host for this stack.

The site does not require accounts for hosts or participants. The creator access code is an exchange-specific secret, and each participant receives a private reveal link. Codes and reveal tokens must be generated securely and stored as hashes.

## Local setup

Requirements: PHP 8.2+, Composer, and Node.js with npm.

```sh
composer install
copy .env.example .env
php artisan key:generate
npm install
npm run build
php artisan serve
```

Set the database connection values in `.env` before running migrations. Never commit `.env` or production database credentials.

## Deployment

Push the repository to GitHub, then configure the PHP host to deploy the Laravel application with its document root set to `public/`. Configure the host's environment variables for the app key, URL, and Supabase Postgres connection. Build Vite assets with `npm run build` during deployment. A provider-specific GitHub Actions deployment workflow can be added after the PHP host is chosen.
