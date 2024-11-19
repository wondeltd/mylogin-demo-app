<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(config('app.run-internal-mylogin-development-seeder')) {
            $this->call([
                InternalMyLoginDevelopmentSeeder::class,
            ]);
        }
    }
}
