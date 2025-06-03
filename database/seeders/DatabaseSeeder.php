<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the Super-Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'Super-Admin', 'guard_name' => 'web']);

        // Create Camilo Alvarado user
        $user = User::create([
            'name' => 'Camilo Alvarado',
            'email' => 'camilo.alvarado0501@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('milogaqw12'),
            'phone' => '1234567890',
            'photo' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 1000 random users
        User::factory(10)->create();

        // Call the roles and permissions seeder
        $this->call(RolesAndPermissionsSeeder::class);
          // Assign the Super-Admin role to Camilo Alvarado
        $user->assignRole($superAdminRole);
    }
}