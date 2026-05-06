<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    public function run()
    {


        Supervisor::updateOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'username'  => 'supervisor',
                'email'     => 'supervisor@example.com',
                'password'  => Hash::make('12345678'),
                'full_name' => 'Default Supervisor',
                'phone'     => '0000000000',
                'status'    => 'active',
            ]
        );
    }
}
