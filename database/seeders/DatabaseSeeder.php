<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Article\Database\Seeders\ArticleDatabaseSeeder;
use Modules\Service\Database\Seeders\ServiceDatabaseSeeder;

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
            // RolePermissionsTableSeeder::class,
            UserTableSeeder::class,
        ]);
    }
}
