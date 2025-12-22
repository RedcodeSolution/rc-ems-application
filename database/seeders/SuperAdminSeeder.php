<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $email = Config::get('services.super_admin.email');
        $name = Config::get('services.super_admin.name');
        $password = Config::get('services.super_admin.password');

        if (!$email || !$password) {
            $this->command->error('Super Admin credentials not set in config/services.php. Check your .env file.');
            return;
        }

        // 1. Create/Update User (for Login)
        $user = User::where('email', $email)->first();
        if (!$user) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'contact_no' => '0000000000', // Default placeholder
            ]);
            $this->command->info("User '{$name}' created in users table.");
        } else {
            $this->command->info("User '{$name}' already exists in users table.");
        }

        // 2. Create/Update SuperAdmin (for Profile/Permissions)
        $superAdmin = SuperAdmin::where('super_admin_email', $email)->first();
        if (!$superAdmin) {
            SuperAdmin::create([
                'super_admin_id' => Str::uuid(),
                'super_admin_name' => $name,
                'super_admin_email' => $email,
                'password' => Hash::make($password),
                'status' => 'active',
                'role' => 'super_admin',
                'permissions' => json_encode(['user_management', 'system_settings', 'security', 'reports']),
            ]);
            $this->command->info("Super Admin '{$name}' created in super_admins table.");
        } else {
            $this->command->info("Super Admin '{$name}' already exists in super_admins table.");
        }
    }
}
