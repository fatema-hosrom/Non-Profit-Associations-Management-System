<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            SupervisorSeeder::class,
            // ManagersTableSeeder::class,
            // VolunteerSeeder::class,
            // OrganizationActivitySeeder::class,
            // OrganizationsTableSeeder::class,
            // OrganizationEventsTableSeeder::class,

        ]);
    }
}
