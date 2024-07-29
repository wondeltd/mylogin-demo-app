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

## Configure OAuth

Get your `client_id` from the [MyLogin Developer Portal](https://mylogin.com/developer/login) and store it in `.env` `MYLOGIN_CLIENT_ID`

Generate a secret and store it in `.env` `MYLOGIN_CLIENT_SECRET`

Generate a redirect URI and store it in `.env` `MYLOGIN_REDIRECT_URI`, this will likely need to
be https://mylogin-demo-app.test/callback

## Configure SAML

### Setup Tenant
The first record in `saml_tenants` is used to POST a SAML Authentication Assertion. This can be created using the
following command. You may need to update `loginUrl` if using this demo app locally, and you want to connect to a local
instance of MyLogin

```shell
php artisan saml2:create-tenant \
  --key=mylogin \
  --entityId="https://app.mylogin.com/saml/metadata" \
  --loginUrl="https://app.mylogin.com/saml/login" \
  --logoutUrl="https://app.mylogin.com/saml/logout" \
  --x509cert="MIIDDjCCAfYCCQDws6zziD+9EjANBgkqhkiG9w0BAQsFADBJMQswCQYDVQQGEwJVSzESMBAGA1UECAwJTmV3bWFya2V0MRAwDgYDVQQKDAdNeUxvZ2luMRQwEgYDVQQDDAtteWxvZ2luLmNvbTAeFw0yMzAxMTExNzEzNDNaFw00MzAxMDYxNzEzNDNaMEkxCzAJBgNVBAYTAlVLMRIwEAYDVQQIDAlOZXdtYXJrZXQxEDAOBgNVBAoMB015TG9naW4xFDASBgNVBAMMC215bG9naW4uY29tMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzkzGmf4/P3jZzClmPFNjY//ndoOgkEWO+IIVLxEC8KjMw6eVnKj+7KVFZTSd/Ept4FJHFWx+kEf0o2LlnHMXASN1BFsXP0yeZHJqYCZsyCIoqoQX60lzCQJd30f0RwNk7K+wUGejx13frcSuYOn3HT6YX9z0DiYTtxeb3NQWsIYaRiZudluUQsP07eBnIGfVpgOZEDpPtiv3g8UgzcdxgTwiwIhhxGji5Azl/K1djUoMtXKsQkF2Q3bQmU70wmThPb+GDLG80H3PFwi9ALnndqqXrq3l5KvTcmo8Vul0nldgUS4fc80ubp1eDwZTouRbOZ7siyhKyS55vChW+TWVeQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQAoB4cntIMlMfKDzbaQ6tei2ur/QWdtEM3sorIbdCjUFI9seBvUsCOVNhNtS17W7ZlakQyLW2BDTfYogh6bxzpSnbWGgv7XjYte4V74LVDK0/p15lNosAEYbNgnmWPpk4MBFNQickc/mhgDveioj2IZCOYg50+4Z0FmU7qF2uydpLkUyEE1UCTN66nSpqq1Mlof0ccKfYZwP8iv5rEY5jMnWqBlT4uEt2hCQt60Si50RDTs/Ef23jU7cPe5YcY+25VCMD1EJdO0mGhaJ4VU9rui5IIAnRsx+bip6nFv6Rai0oLAeDP1wz5f7Ap3ztHqrVi5oiSZPQpAii7I8DvkqWIw" 
```

```
--key is just a name to identify the tenant within the demo app, it can be any string
--entityId is the globally unique identifier for this SAML tenant 
--loginUrl is the url where the SAML assertion should be posted to
--logoutUrl is the url where users should be sent when they log out so they are logged out of MyLogin
--x509cert is available at https://app.mylogin.com/saml/metadata
```

### Configure App in MyLogin Developer Portal

Ensure `SAML2_LOGIN_URL` in `.env`is set to the correct domain, this will probably be something like
https://mylogin.test/saml/login

In [MyLogin Developer Portal](https://mylogin.com/developer/login), ensure the Authentication protocol is set to SAML

Entity ID: https://mylogin-demo-app.valet/saml2/{$app_uuid}/metadata

Assertion Consumer Service URL (Reply URL): https://mylogin.test/saml2/{$app_uuid}/acs

To find the App UUID

```sql
SELECT uuid
FROM saml2_tenants
WHERE `key` = 'mylogin'
```
