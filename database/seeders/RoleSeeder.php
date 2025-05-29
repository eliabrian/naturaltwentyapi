<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Chef'],
            ['name' => 'Cashier'],
            ['name' => 'Game Master'],
            ['name' => 'Dungeon Master'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
