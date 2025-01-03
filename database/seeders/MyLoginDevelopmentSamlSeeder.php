<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Slides\Saml2\Models\Tenant;

class MyLoginDevelopmentSamlSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'key'            => 'mylogin',
            'uuid'           => 'b2538b21-6119-43a0-909a-380e5c44d927',
            'idp_entity_id'  => 'https://mylogin.test/saml/metadata',
            'idp_login_url'  => 'https://mylogin.test/saml/login',
            'idp_logout_url' => 'https://mylogin.test/saml/logout',
            'idp_x509_cert'  => 'MIIDDjCCAfYCCQDws6zziD+9EjANBgkqhkiG9w0BAQsFADBJMQswCQYDVQQGEwJVSzESMBAGA1UECAwJTmV3bWFya2V0MRAwDgYDVQQKDAdNeUxvZ2luMRQwEgYDVQQDDAtteWxvZ2luLmNvbTAeFw0yMzAxMTExNzEzNDNaFw00MzAxMDYxNzEzNDNaMEkxCzAJBgNVBAYTAlVLMRIwEAYDVQQIDAlOZXdtYXJrZXQxEDAOBgNVBAoMB015TG9naW4xFDASBgNVBAMMC215bG9naW4uY29tMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzkzGmf4/P3jZzClmPFNjY//ndoOgkEWO+IIVLxEC8KjMw6eVnKj+7KVFZTSd/Ept4FJHFWx+kEf0o2LlnHMXASN1BFsXP0yeZHJqYCZsyCIoqoQX60lzCQJd30f0RwNk7K+wUGejx13frcSuYOn3HT6YX9z0DiYTtxeb3NQWsIYaRiZudluUQsP07eBnIGfVpgOZEDpPtiv3g8UgzcdxgTwiwIhhxGji5Azl/K1djUoMtXKsQkF2Q3bQmU70wmThPb+GDLG80H3PFwi9ALnndqqXrq3l5KvTcmo8Vul0nldgUS4fc80ubp1eDwZTouRbOZ7siyhKyS55vChW+TWVeQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQAoB4cntIMlMfKDzbaQ6tei2ur/QWdtEM3sorIbdCjUFI9seBvUsCOVNhNtS17W7ZlakQyLW2BDTfYogh6bxzpSnbWGgv7XjYte4V74LVDK0/p15lNosAEYbNgnmWPpk4MBFNQickc/mhgDveioj2IZCOYg50+4Z0FmU7qF2uydpLkUyEE1UCTN66nSpqq1Mlof0ccKfYZwP8iv5rEY5jMnWqBlT4uEt2hCQt60Si50RDTs/Ef23jU7cPe5YcY+25VCMD1EJdO0mGhaJ4VU9rui5IIAnRsx+bip6nFv6Rai0oLAeDP1wz5f7Ap3ztHqrVi5oiSZPQpAii7I8DvkqWIw',
            'metadata'       => [],
        ]);
    }
}
