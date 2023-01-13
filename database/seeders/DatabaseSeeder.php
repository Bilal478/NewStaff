<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AccountSeeder;
use Database\Seeders\LocalAccountSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LocalAccountSeeder::class,
            //AccountSeeder::class,
        ]);
    }
}
