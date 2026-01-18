<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ModuleSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@manage.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        // Create Regular User
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@manage.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole($userRole);

        // Initialize Module Settings for User
        $modules = ['revenues', 'expenses', 'debts', 'claims', 'savings', 'forecasts'];
        foreach ($modules as $module) {
            ModuleSetting::create([
                'user_id' => $user->id,
                'module_name' => $module,
                'is_enabled' => true,
            ]);
        }

        // Run Category Seeder
        $this->call(CategorySeeder::class);
    }
}
