# MyLogin Demo SSO App

The MyLogin Demo SSO App provides a working example of a Laravel application which has implemented the MyLogin SSO API.

## Installation

Install PHP and Javascript dependencies

```shell
composer install &&
npm install
```

Configure environment
Edit the contents of `.env` to connect to your database.

```shell
cp .env.example .env
```

Generate an application key

```shell
php artisan key:generate
```

Create tables in the database

```shell
php artisan migrate
```

Bundle the front-end assets

```shell
npm run build
```

## Accessing the Web Interface

If you have Laravel Valet available, you can link the app so it's available at a friendly domain locally

```shell
valet link mylogin-demo-app --secure
```

By default, this will make the app accessible at https://mylogin-demo-app.test but this may be different if you have
configured valet to use a different top level domain

## Configure MyLogin

Get your `client_id` from the [MyLogin Developer Portal](https://mylogin.com/developer/login)

Generate a secret and store it in `.env` `MYLOGIN_CLIENT_SECRET`

Generate a redirect URI and store it in `.env` `MYLOGIN_CLIENT_SECRET`, this will likely need to
be https://mylogin-demo-app.test/callback