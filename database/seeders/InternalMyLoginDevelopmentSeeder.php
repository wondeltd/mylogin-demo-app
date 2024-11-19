<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InternalMyLoginDevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
           MyLoginDevelopmentSamlSeeder::class,
        ]);
    }
}
