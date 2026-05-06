<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manager;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Hash;

class ManagersTableSeeder extends Seeder
{
    public function run()
    {
        $supervisor = Supervisor::where('email', 'supervisor@example.com')->first();

        Manager::updateOrCreate(
            ['email' => 'manager.financial@example.com'],
            [
                'username'     => 'manager_financial',
                'email'        => 'manager.financial@example.com',
                'password'     => Hash::make('12345678'),
                'full_name'    => 'Financial Manager',
                'phone'        => '0000000001',
                'manager_type' => 'financial',
                'status'       => 'active',
                'created_by'   => $supervisor ? $supervisor->id : null,
            ]
        );

        Manager::updateOrCreate(
            ['email' => 'manager.activities@example.com'],
            [
                'username'     => 'manager_activities',
                'email'        => 'manager.activities@example.com',
                'password'     => Hash::make('12345678'),
                'full_name'    => 'Activities Manager',
                'phone'        => '0000000002',
                'manager_type' => 'activities',
                'status'       => 'active',
                'created_by'   => $supervisor ? $supervisor->id : null,
            ]
        );
    }
}
